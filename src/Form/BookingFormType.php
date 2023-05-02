<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Booking;
use App\Repository\AllergenRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('booking_date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('covers')
            ->add('customer_firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('customer_lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('customer_phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/')
                ],
            ])
            ->add('customer_mail', TextType::class, [
                'label' => 'Adresse Mail',
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/')
                ],
            ])
            ->add('allergens', EntityType::class, [
                'class' => Allergen::class,
                'choice_label' => 'label',
                'label' => 'Allergies dans le groupe',
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function(AllergenRepository $ar)
                {
                    return $ar->createQueryBuilder('a')
                    ->orderBy('a.label', 'ASC');
                },
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
