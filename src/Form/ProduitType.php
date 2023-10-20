<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'required' => true, // Le champ est obligatoire
            ])
            ->add('datepublication', DateType::class, [
                'label' => 'Publication date',
                'required' => true, // Le champ est obligatoire
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, // L'entité à partir de laquelle vous souhaitez charger les auteurs
                'label' => 'Categorie',
                'required' => true,
                'choice_label' => 'name',
                'placeholder'=> 'Choose an categorie',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
