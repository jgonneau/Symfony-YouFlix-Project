<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', TextType::class, ['label' => 'Votre nom d\'utilisateur :', 'attr' => ['class' => 'form-control']])
            ->add('email', EmailType::class, ['label' => 'Votre E-mail :' , 'attr' => ['class' => 'form-control']])
            ->add('birthday', BirthdayType::class, ['label' => 'Votre date d\'anniversaire :', 'attr' => ['class' => 'dropdown']])
            ->add('Submit', SubmitType::class, ['label' => 'Enregistrer les modifications !', 'attr' => [
                'class' => 'btn btn-primary'
            ] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
