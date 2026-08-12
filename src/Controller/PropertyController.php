<?php

namespace aintreallydown\DepositBundle\Controller;

use aintreallydown\DepositBundle\Form\PropertyFormType;
use aintreallydown\DepositBundle\Security\Voter\PropertyVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class PropertyController extends AbstractController
{
    private const STATES = [
        'address', 'infos', 'rooms', 'energy', 'furnished', 'equipments',
        'benefits', 'rent', 'charges', 'services', 'extrafields',
        'availability', 'description', 'title', 'images',
    ];

    public function __construct(
        private EntityManagerInterface $em,
        private string $propertyClass,
        private array $methods,
    ) {}

    #[Route('/property/{uid}/deposit/', name: 'property.deposit', priority: 1)]
    public function deposit(string $uid, Request $request): Response
    {
        $property = $this->em->getRepository($this->propertyClass)
            ->findOneBy(['uid' => $uid]);

        if ($property === null) {
            throw new NotFoundHttpException('Property not found.');
        }

        $this->denyAccessUnlessGranted(PropertyVoter::EDIT, $property);

        $step = $property->{$this->methods['get_state']}();

        $slug = 'deposit';
        $nextStep = 'availability';
        $states = self::STATES;

        if ($step && array_search($step, $states) < array_search($slug, $states)) {
            return $this->redirectToRoute("property.$step", ['uid' => $uid]);
        }

        $form = $this->createForm(PropertyFormType::class);
        $form->handleRequest($request);

        $extrafields = $property->{$this->methods['get_extrafields']}() ?? [];

        if ($form->isSubmitted() && $form->isValid()) {

            $extrafields['deposit'] = $form->get('deposit')->getData();
            $property->{$this->methods['set_extrafields']}($extrafields);

            $isSave = $form->get('save')->isClicked();

            if ($step === $slug && !$isSave) {
                $property->{$this->methods['set_state']}($nextStep);
            }

            $this->em->flush();

            return $isSave
                ? $this->redirectToRoute('properties')
                : $this->redirectToRoute("property.$nextStep", ['uid' => $uid]);
        }

        $default = ($property->{$this->methods['get_rent']}()) - ($property->{$this->methods['get_charges']}());
        
        $max = ($property->{$this->methods['is_furnished']}())
        ? $default * 2 
        : $default;

        $deposit = $default === null
            ? $max
            : $extrafields['deposit'] ?? null;

        $form->get('deposit')->setData($deposit);

        return $this->render('@DepositBundle/deposit.html.twig', [
            'form' => $form->createView(),
            'backlink' => $this->generateUrl('property.services', ['uid' => $uid]),
            'uid' => $uid,
            'max' => $max,
            'step' => $step,
            'current' => 4,
            'progress' => 11 / count($states),
        ]);
    }
}