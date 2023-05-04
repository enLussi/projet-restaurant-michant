<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\User;
use App\Repository\AllergenRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ModifyCustomerInfoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/')
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(),
                    new Regex('/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/')
                ],
            ])
            ->add('default_covers', NumberType::class, [
                'label' => 'Nombre de couvert par défaut'
            ])
            ->add('customer_allergens', EntityType::class, [
                'class' => Allergen::class,
                'choice_label' => 'label',
                'label' => 'Allergènes',
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
            'data_class' => User::class,
        ]);
    }
}
