<?php

namespace App\DataFixtures;

use App\Entity\Availaibility;
use App\Entity\Property;
use App\Entity\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AvailaibilityFixtures extends Fixture  implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $tenants = $this->userRepository->findByRole(User::ROLE_TENANT);
        $properties = $manager->getRepository(Property::class)->findAll();
        $faker = Factory::create();

        for ($i = 0; $i < 4; $i++) {
            $object = (new Availaibility())
                ->setLodger($faker->randomElement($tenants))
                ->setProperty($faker->randomElement($properties))

            ;
            $manager->persist($object);
        }
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PropertyFixtures::class
        ];
    }
}