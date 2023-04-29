<?php

namespace App\DataFixtures;

use App\Entity\CourseCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $all_categories = [
            'Entrées',
            'Plat',
            'Dessert',
            'Vins',
            'Apéritifs'
        ];

        foreach( $all_categories as $category ) {
            $manager->persist($this->addCategory($category));
        }

        $manager->flush();
    }

    private function addCategory(string $label) : CourseCategory{
        $category = new CourseCategory();
        $category->setLabel($label);

        return $category;
    }
}
