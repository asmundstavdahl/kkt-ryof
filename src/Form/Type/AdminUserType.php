<?php

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'Foresatt' => 'ROLE_PARENT',
                    'Deltaker' => 'ROLE_PARTICIPANT',
                    'Veileder' => 'ROLE_TUTOR',
                    'Admin' => 'ROLE_ADMIN',
                ),
                'label' => 'Brukertype',
                'multiple' => false,
                'mapped' => false,
            ))
            ->add('firstName', TextType::class, array(
                'label' => 'Fornavn',
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Etternavn',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'E-post',
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Telefon',
                'data' => '-',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Entity\User',
        ));
    }
}
