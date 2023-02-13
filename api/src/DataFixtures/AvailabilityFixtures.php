<?php

namespace App\DataFixtures;

use App\Entity\Availaibility;
use App\Repository\RequestRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AvailabilityFixtures extends Fixture  implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository, private RequestRepository $requestRepository)
    {
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $requests = $this->requestRepository->findAll();
        $faker = Factory::create();

        foreach($requests as $request) {
            if ($request->getLodger()->getAvailaibilities()->isEmpty()) {
                $object = (new Availaibility())
                    ->setLodger($request->getLodger())
                    ->setProperty($request->getProperty())
                    ->setSlot($faker->dateTimeBetween('now', '+1 month'))
                ;
                $manager->persist($object);
            }
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
        ];
    }
}