<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Pokemon;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
//            ->add('category', EntityType::class, array(
//                'class' => Category::class,
//                'choice_label' => 'name',
//                'expanded' => true,
//                'multiple' => false,
//            ))
            ->add('size',TextType::class)
            ->add('weight', TextType::class)
            ->add('sex', TextType::class)
            ->add('catch_rate', TextType::class)
            ->add('description', TextareaType::class)
            ->add('attitude', TextareaType::class)
            ->add('differences', TextareaType::class)
            ->add('evolution', TextareaType::class)
            ->add('talent', TextareaType::class)
            ->add('num_pokedex', IntegerType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}