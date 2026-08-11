<?php

namespace _92radar\DepositBundle\Form;

use _92radar\DepositBundle\Entity\PropertyInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('extrafields', CollectionType::class, [
            'entry_type' => IntegerType::class,
            'allow_add' => true,
            'allow_delete' => true,
        ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyInterface::class,
            'method' => 'PATCH',
        ]);
    }
}