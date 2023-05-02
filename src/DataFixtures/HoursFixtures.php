<?php

namespace App\DataFixtures;

use App\Entity\Hours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HoursFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $all_hours = [
            ['Lundi', '00:00', '00:00', false, true, 'monday'],
            ['Lundi', '00:00', '00:00', false, false, 'monday'],
            ['Mardi', '00:00', '00:00', false, true, 'tuesday'],
            ['Mardi', '18:30', '22:00', true, false, 'tuesday'],
            ['Mercredi', '12:00', '14:00', true, true, 'wednesday'],
            ['Mercredi', '18:30', '22:00', true, false, 'wednesday'],
            ['Jeudi', '12:00', '14:00', true, true, 'thursday'],
            ['Jeudi', '18:30', '22:00', true, false, 'thursday'],
            ['Vendredi', '12:00', '14:00', true, true, 'friday'],
            ['Vendredi', '18:30', '22:00', true, false, 'friday'],
            ['Samedi', '11:20', '14:30', true, true, 'saturday'],
            ['Samedi', '18:30', '22:30', true, false, 'saturday'],
            ['Dimanche', '11:20', '14:30', true, true, 'sunday'],
            ['Dimanche', '00:00', '00:00', false, false, 'sunday']
        ];

        foreach( $all_hours as $hours) {
            $manager->persist($this->addHours($hours[0], $hours[1], $hours[2], $hours[3], $hours[4], $hours[5]));
        }

        $manager->flush();
    }

    private function addHours(string $day, string $opening, string $closure, bool $open, bool $lunch, string $label) : Hours{
        $hours = new Hours();

        $hours->setLabelDay($day);
        $hours->setOpening($opening);
        $hours->setClosure($closure);
        $hours->setOpen($open);
        $hours->setLunch($lunch);
        $hours->setLabel($label);
        
        return $hours;
    }
}
