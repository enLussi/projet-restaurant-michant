<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('monday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('monday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false,
            ])
            ->add('monday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('monday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('monday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('tuesday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('tuesday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('tuesday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('tuesday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('tuesday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('tuesday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('wednesday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('wednesday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('wednesday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('wednesday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('wednesday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('wednesday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('thursday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('thursday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('thursday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('thursday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('thursday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('thursday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('friday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('friday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('friday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('friday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('friday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('friday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('saturday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('saturday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('saturday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('saturday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('saturday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('saturday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('sunday_lunch_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('sunday_lunch_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('sunday_lunch_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])
            ->add('sunday_dinner_opening', TimeType::class, [
                'label' => 'Ouverture'
            ])
            ->add('sunday_dinner_closure', TimeType::class, [
                'label' => 'Fermeture'
            ])
            ->add('sunday_dinner_open', CheckboxType::class, [
                'label' => 'Ouvert ?',
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
