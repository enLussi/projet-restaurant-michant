<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Booking;
use App\Repository\AllergenRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('booking_date', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        "value" => date('Y-m-d', strtotime('+1 day')),
                        "message" => "Vous pouvez réservez à partir de demain."
                    ])
                ]
            ])
            ->add('covers', options:[
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 20,
                        'notInRangeMessage' => 'Le nombre de convive doit être compris entre 1 et 20.'
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'Au moins un couvert par réservation.'
                    ]),
                    new LessThanOrEqual([
                        'value' => 20,
                        'message' => 'Pour plus de convives, veuillez nous contacter pour téléphone pour réserver.'
                    ])
                ]
            ])
            ->add('customer_firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('customer_lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('customer_phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        "pattern" => '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/',
                        "message" => 'Il nous faut un numéro de téléphone valide.'
                    ]),
                ],
            ])
            ->add('customer_mail', TextType::class, [
                'label' => 'Adresse Mail',
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        "pattern" => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        "message" => 'Il nous faut une adresse mail valide.'
                    ]),
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
            ->add('message', options:[
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
