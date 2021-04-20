<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifiant', TextType::class, ['label' => '* Identifiant : ', 'attr' => ['placeholder' => 'LOGIN']])
                ->add('motDePasse', PasswordType::class, ['label' => '* Mot de passe : ', 'attr' => ['placeholder' => 'PASSWORD']])
                ->add('nom', TextType::class, ['label' => 'Nom : ', 'required' => false])
                ->add('prenom', TextType::class, ['label' => 'Prénom : ', 'required' => false])
                ->add('anniversaire', BirthdayType::class, ['label' => 'Date de Naissance : ', 'widget' => 'single_text', 'required' => false]);
        /*Je trouve l'option 'single_text' la plus esthétique pour renseigner la date de naissance*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Utilisateur::class,]);
    }
}
