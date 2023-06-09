<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\AllergenRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
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
            ->add('default_covers', IntegerType::class ,options:[
                'label' => 'Nombre de couvert par défaut',
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
                    ]),
                ]
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
            ->add('RGPDConsent', CheckboxType::class, [
                'label' => 'Consentir aux l\'utilisation des données',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Il faut accepter les termes sur les conditions d\'utilisation.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
