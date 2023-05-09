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
            ['Lundi', '00:00', '00:00', false, true, 'monday', 5],
            ['Lundi', '00:00', '00:00', false, false, 'monday', 5],
            ['Mardi', '00:00', '00:00', false, true, 'tuesday', 5],
            ['Mardi', '18:30', '22:00', true, false, 'tuesday', 5],
            ['Mercredi', '12:00', '14:00', true, true, 'wednesday', 5],
            ['Mercredi', '18:30', '22:00', true, false, 'wednesday', 5],
            ['Jeudi', '12:00', '14:00', true, true, 'thursday', 5],
            ['Jeudi', '18:30', '22:00', true, false, 'thursday', 5],
            ['Vendredi', '12:00', '14:00', true, true, 'friday', 5],
            ['Vendredi', '18:30', '22:00', true, false, 'friday', 5],
            ['Samedi', '11:20', '14:30', true, true, 'saturday', 5],
            ['Samedi', '18:30', '22:30', true, false, 'saturday', 5],
            ['Dimanche', '11:20', '14:30', true, true, 'sunday', 5],
            ['Dimanche', '00:00', '00:00', false, false, 'sunday', 5]
        ];

        foreach( $all_hours as $hours) {
            $manager->persist($this->addHours($hours[0], $hours[1], $hours[2], $hours[3], $hours[4], $hours[5], $hours[6]));
        }

        $manager->flush();
    }

    private function addHours(string $day, string $opening, string $closure, bool $open, bool $lunch, string $label, int $max) : Hours{
        $hours = new Hours();

        $hours->setLabelDay($day);
        $hours->setOpening($opening);
        $hours->setClosure($closure);
        $hours->setOpen($open);
        $hours->setLunch($lunch);
        $hours->setLabel($label);
        $hours->setMaxBooking($max);
        
        return $hours;
    }
}
