<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', TextType::class, ['label' => 'Libellé : ', 'attr' => ['placeholder' => 'libellé']])
                ->add('prix', MoneyType::class, ['label' => 'Prix : ', 'currency' => 'EUR', 'invalid_message' => 'Entrez une valeur (en €)'])
                ->add('qteStock', IntegerType::class, ['label' => 'Quantité en Stock : '])
                ->add('valider', SubmitType::class, ['label' => 'valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Produit::class,]);
    }
}

/*Fichier par josué Raad et Florian Portrait*/
