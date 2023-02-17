<?php

namespace App\DataFixtures;
use App\Entity\User;
use App\Enums\UserValidationStatusEnum;
use App\Enums\WorkSituationEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordHasher = null;
    
    public function __construct (UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getGroups(): array
    {
        return ['minimal'];
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
        $situations = [WorkSituationEnum::Employee, WorkSituationEnum::Student, WorkSituationEnum::AlterningStudent, WorkSituationEnum::PublicOfficial];
        $faker = Factory::create();

        $admin = new User();
        $admin->setFirstname('admin')
        ->setLastname('admin')
        ->setEmail('admin@test.com')
        ->setRoles([User::ROLE_AGENCY])
        ->setSalary(null)
        ->setValidationStatus(UserValidationStatusEnum::Validated)
        ->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $tenant = new User();
        $tenant->setFirstname('tenant')
        ->setLastname('tenant')
        ->setEmail('tenant@test.com')
        ->setRoles([User::ROLE_TENANT])
        ->setSalary(null)
        ->setSituation($faker->randomElement($situations))
        ->setPassword($this->passwordHasher->hashPassword($tenant, 'tenant'));
        $manager->persist($tenant);

        $owner = new User();
        $owner->setFirstname('owner')
        ->setLastname('owner')
        ->setEmail('owner@test.com')
        ->setRoles([User::ROLE_HOMEOWNER])
        ->setSalary(null)
        ->setSituation($faker->randomElement($situations))
        ->setPassword($this->passwordHasher->hashPassword($owner, 'owner'));
        $manager->persist($owner);

        for ($i = 0; $i < 10; $i++) {
            $userRole = [$faker->randomElement($roles)];
            $user = (new User())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setRoles([$faker->randomElement($roles)])
                ->setSalary(userSalary($userRole, $faker))
                ->setSituation($faker->randomElement($situations));
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}