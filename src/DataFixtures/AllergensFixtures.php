<?php

namespace App\DataFixtures;

use App\Entity\Allergen;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AllergensFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $all_allergens = [
            'Produit à base de céréales',
            'Produit à base de crustacées',
            'Produit à base d\'oeufs',
            'Produit à base d\'arachides',
            'Produit à base de poissons',
            'Produit à base de soja',
            'Produit laitiers',
            'Produit à base de fruits à coque',
            'Produit à base de Céleri',
            'Produit à base de moutarde',
            'Produit à base de graines de sésame',
            'Anhydride sulfureux et sulfites',
            'Produit à base de lupin',
            'Produit à base de mollusques'
        ];

        foreach( $all_allergens as $allergen) {
            $manager->persist($this->addAllergen($allergen));
        }

        $manager->flush();
    }

    private function addAllergen(string $label) : Allergen{
        $allergen = new Allergen();
        $allergen->setLabel($label);

        return $allergen;
    }
}
