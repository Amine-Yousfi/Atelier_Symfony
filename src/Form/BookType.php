<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Cassandra\Date;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref',IntegerType::class, [
                'label' => 'Ref',
                'required' => true, // Le champ est obligatoire
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true, // Le champ est obligatoire
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'required' => true,'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ], // Le champ est obligatoire
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Published',
            ])
            ->add('publicationDate', DateType::class, [
                'label' => 'Publication date',
                'required' => true, // Le champ est obligatoire
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class, // L'entité à partir de laquelle vous souhaitez charger les auteurs
                'label' => 'Author',
                'required' => true,
                'choice_label' => 'username',
                'placeholder'=> 'Choose an author',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
