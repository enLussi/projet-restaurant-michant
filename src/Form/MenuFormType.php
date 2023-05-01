<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Menu;
use App\Entity\SetMenu;
use App\Repository\CourseRepository;
use App\Repository\SetMenuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options:[
                'label' => 'Nom du Menu'
            ])
            ->add('setmenu', EntityType::class, [
                'class' => SetMenu::class,
                'choice_label' => 'title',
                'label' => 'Formule',
                'multiple' => false,
                'query_builder' => function(SetMenuRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                }
            ])
            // ->add('courses', EntityType::class, [
            //     'class' => Course::class,
            //     'choice_label' => 'title',
            //     'label' => 'Plats',
            //     'multiple' => true,
            //     'expanded' => false,
            //     'query_builder' => function(CourseRepository $cr) {
            //         return $cr->createQueryBuilder('c')
            //             ->orderBy('c.title', 'ASC');
            //     },
            //     'choices' => [],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
