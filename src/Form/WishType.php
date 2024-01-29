<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => false,
                'attr' => [ 'placeholder' => 'Votre titre'],
                /*'help' => 'Renseigner le titre (max. 250 caractères)'*/
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [ 'placeholder' => 'votre description'],
            ])
            ->add('author',TextType::class, [
                'label' => 'Auteur',
                'required' => false,
                'attr' => [ 'placeholder' => 'Votre pseudo'],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label'=> 'name'
            ])
            ->add('dateCreated', DateType::class, [
                'label' => 'Crée le',
                'html5'=> true,
                'widget' => 'single_text'
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [ 'class' => 'btn' ]
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => [ 'class' => 'btn' ]
            ])
          ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
