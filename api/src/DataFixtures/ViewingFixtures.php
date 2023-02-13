<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Viewing;
use App\Repository\AvailaibilityRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class ViewingFixtures extends Fixture  implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository, private AvailaibilityRepository $availaibilityRepository)
    {
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $agents = $this->userRepository->findByRole(User::ROLE_AGENCY);
        $availabilities = $this->availaibilityRepository->findAll();
        $faker = Factory::create();

        foreach ($availabilities as $availability) {
            $object = (new Viewing())
                ->setLodger($availability->getLodger())
                ->setAgent($faker->randomElement($agents))
                ->setAvailaibility($availability)
            ;
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PropertyFixtures::class,
            RequestFixtures::class,
            AvailabilityFixtures::class,
        ];
    }
}