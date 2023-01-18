<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        function userSalary($userRole, $faker){

            if ($userRole == 'ROLE_LODGER'){
                return $faker->numberBetween($min=600, $max=3000);
            }
            return null;
        }
        $roles = [User::ROLE_OWNER, User::ROLE_LODGER, User::ROLE_AGENCY];
        $faker = Factory::create();
        for ($i = 0; $i < 40; $i++) {
            $userRole = $roles[$faker->numberBetween($min=0, $max=count($roles)-1)];

            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles($userRole)
                ->setSalary(userSalary($userRole, $faker))

            ;
            $manager->persist($object);
        }
         $manager->flush();
    }
}