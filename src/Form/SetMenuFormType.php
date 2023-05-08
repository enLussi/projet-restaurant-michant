<?php

namespace App\Form;

use App\Entity\CourseCategory;
use App\Entity\SetMenu;
use App\Repository\CourseCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class SetMenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options:[
                'label' => 'Nom de la Formule'
            ])
            ->add('summary', options:[
                'label' => 'Description de la Formule'
            ])
            ->add('price', MoneyType::class, options:[
                'label' => 'Prix €',
                'divisor' => 100,
                'currency' => '',
                'constraints' => [
                    new Positive(
                        message: 'Prix non valide'
                    )
                    ],
            ])
            ->add('courseCategory', EntityType::class, [
                'class' => CourseCategory::class, 
                'choice_label' => 'label',
                'label' => 'Catégories de Plats acceptées',
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function(CourseCategoryRepository $ccr) {
                    return $ccr->createQueryBuilder('c')
                        ->orderBy('c.label', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SetMenu::class,
        ]);
    }
}
