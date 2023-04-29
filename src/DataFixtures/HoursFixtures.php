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
            ['Lundi', '00:00', '00:00', false, false],
            ['Lundi', '00:00', '00:00', false, true],
            ['Mardi', '00:00', '00:00', false, true],
            ['Mardi', '18:30', '22:00', true, false],
            ['Mercredi', '12:00', '14:00', true, true],
            ['Mercredi', '18:30', '22:00', true, false],
            ['Jeudi', '12:00', '14:00', true, true],
            ['Jeudi', '18:30', '22:00', true, false],
            ['Vendredi', '12:00', '14:00', true, true],
            ['Vendredi', '18:30', '22:00', true, false],
            ['Samedi', '11:20', '14:30', true, true],
            ['Samedi', '18:30', '22:30', true, false],
            ['Dimanche', '11:20', '14:30', true, true],
            ['Dimanche', '00:00', '00:00', false, false]
        ];

        foreach( $all_hours as $hours) {
            $manager->persist($this->addHours($hours[0], $hours[1], $hours[2], $hours[3], $hours[4]));
        }

        $manager->flush();
    }

    private function addHours(string $day, string $opening, string $closure, bool $open, bool $lunch) : Hours{
        $hours = new Hours();

        $hours->setLabelDay($day);
        $hours->setOpening($opening);
        $hours->setClosure($closure);
        $hours->setOpen($open);
        $hours->setLunch($lunch);
        
        return $hours;
    }
}
