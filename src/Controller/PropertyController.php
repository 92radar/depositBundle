<?php

namespace _92radar\DepositBundle\Controller;

use _92radar\DepositBundle\Entity\PropertyInterface;
use _92radar\DepositBundle\Form\PropertyFormType;
use App\Security\Voter\PropertyVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PropertyController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    #[Route('/property/{uid}/deposit/', name: 'property.deposit', priority: 1)]
    #[IsGranted(PropertyVoter::EDIT, subject: 'property')]
    public function deposit(PropertyInterface $property, Request $request): Response
    {
        $uid = $property->getUid();
        $step = $property->getState();
        $slug = 'deposit';
        $nextStep = 'availability';
        $states = PropertyInterface::STATES;

        if ($step && array_search($step, $states) < array_search($slug, $states)) {
            return $this->redirectToRoute("property.$step", ['uid' => $uid]);
        }

        $form = $this->createForm(PropertyFormType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isSave = $form->get('save')->isClicked();

            if ($step === $slug && !$isSave) {
                $property->setState($nextStep);
            }

            $this->em->flush();

            return $isSave
                ? $this->redirectToRoute('properties')
                : $this->redirectToRoute("property.$nextStep", ['uid' => $uid]);
        }

        $deposit = $property->getRent() - $property->getCharges();
        $max = $property->isFurnished() ? $deposit * 2 : $deposit;

        if ($property->getDeposit() === null) {
            $form->get('deposit')->setData($max);
        }

        return $this->render('property/deposit.html.twig', [
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