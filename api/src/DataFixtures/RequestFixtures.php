<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class RequestFixtures extends Fixture  implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $lodgers = $this->userRepository->findByRole(User::ROLE_LODGER);
        $properties = $manager->getRepository(Property::class)->findAll();
        $faker = Factory::create();

        for ($i = 0; $i < count($lodgers); $i++) {
            $object = (new Request())
                ->setLodger($faker->randomElement($lodgers))
                ->setProperty($faker->randomElement($properties))
                ->setIsAccepted($faker->boolean())
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