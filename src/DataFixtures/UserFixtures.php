<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder){}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@2i.com')
        ->setLastname('Doe')
        ->setFirstname('John')
        ->setAddress('22 rue de la Rose')
        ->setZipcode('75006')
        ->setCity('Paris')
        ->setCountry('France')
        ->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);

        $faker = Factory::create('fr_FR');

        for($u = 1; $u <= 10; $u++) {
            $user = new User();
            $user->setEmail($faker->email)
            ->setLastname($faker->lastname)
            ->setFirstname($faker->firstname)
            ->setAddress($faker->streetAddress)
            ->setZipcode(str_replace(" ","",$faker->postcode))
            ->setCity($faker->city)
            ->setCountry('France')
            ->setPassword(
                $this->passwordEncoder->hashPassword($user, 'password')
            );

        $manager->persist($user);

        }

        $manager->flush();
    }
}
