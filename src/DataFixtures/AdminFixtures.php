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

        $params = json_decode(file_get_contents('newAdmin.json'), true);
        
        $admin = new Admin();
    
        $admin->setEmail($params['email']);
        $admin->setLastname($params['lastname']);
        $admin->setFirstname($params['firstname']);
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, $params['password'])
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
