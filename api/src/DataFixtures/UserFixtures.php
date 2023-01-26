<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct (UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        function userSalary($userRole, $faker){

            if ($userRole == 'ROLE_TENANT'){
                return $faker->numberBetween($min=600, $max=3000);
            }
            return null;
        }
        $roles = [User::ROLE_HOMEOWNER, User::ROLE_TENANT, User::ROLE_AGENCY];
        $situations = ["Student", "Employee", "Freelancer"];
        $incomeSources = ["Student job", "Scholarship", "Full time Job"];
        $faker = Factory::create();
        for ($i = 0; $i < 40; $i++) {
            $userRole = [$faker->randomElement($roles)];

            $object = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles($userRole)
                ->setSalary(userSalary($userRole, $faker))
                ->setSituation($faker->randomElement($situations))
                ->setIncomeSource($faker->randomElement($incomeSources))
            ;
            $plainPassword = "Password@dev";
            $encoded = $this->passwordHasher->hashPassword(
                $object,
                $plainPassword
            );

            $object->setPassword($encoded);

            $manager->persist($object);
        }
         $manager->flush();
    }
}