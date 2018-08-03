<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', null, ['label' => 'Votre nom d\'utilisateur :'])
            ->add('email', EmailType::class, ['label' => 'Votre E-mail :'])
            ->add('birthday', BirthdayType::class, ['label' => 'Votre date d\'anniversaire :'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Entrez le mot de passe'),
                'second_options' => array('label' => 'Repeter le mot de passe'),
            ])
            ->add('Submit', SubmitType::class, ['label' => 'S\'enregistrer !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
