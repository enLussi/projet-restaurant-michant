<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordEncoder){}

    public function load(ObjectManager $manager): void
    {
        
        $admin = new Admin();
    
        $admin->setEmail('reception-quaiantique@mail.com');
        $admin->setFirstname('Jean');
        $admin->setLastname('Martin');

        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin@reception')
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
