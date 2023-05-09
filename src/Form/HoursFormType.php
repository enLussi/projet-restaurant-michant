<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class HoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $days = [
            'monday_lunch', 'monday_dinner',
            'tuesday_lunch', 'tuesday_dinner',
            'wednesday_lunch', 'wednesday_dinner',
            'thursday_lunch', 'thursday_dinner',
            'friday_lunch', 'friday_dinner',
            'saturday_lunch', 'saturday_dinner',
            'sunday_lunch', 'sunday_dinner',
        ];


        foreach($days as $day) {
            $builder            
                ->add($day.'_opening', TimeType::class, [
                    'label' => 'Ouverture'
                ])
                ->add($day.'_closure', TimeType::class, [
                    'label' => 'Fermeture'
                ])
                ->add($day.'_open', CheckboxType::class, [
                    'label' => 'Ouvert ?',
                    'required' => false,
                ])
                ->add($day.'_maxBookings', IntegerType::class, [
                    'label' => 'Réservations Max',
                    'attr' => [
                        'class' => 'max-bookings'
                    ],
                    'constraints' => [
                        new PositiveOrZero([
                            'message' => 'La valeur doit être positive'
                        ]),
                    ],
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
