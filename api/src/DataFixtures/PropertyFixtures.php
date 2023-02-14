<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Provider\Address;

class PropertyFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public static function getGroups(): array
    {
        return ['minimal'];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $types = array("Apartment", "House");
        $heatTypes = array("Electricity", "Gaz");
        $states = array("Available now", "Available soon", "Available in two weeks");
        $owners = $this->userRepository->findByRole(User::ROLE_HOMEOWNER);

        $faker = Factory::create();
        for ($i = 0; $i < count($owners); $i++) {
            $rooms = $faker->numberBetween($min=1, $max=6);
            $type = $faker->randomElement($types);
            $title = $type.": ".$rooms." room";
            $object = (new Property())
                ->setAddress($faker->address)
                ->setTitle($title)
                ->setCountry('France')
                ->setAddress($faker->streetAddress)
                ->setZipcode(Address::postcode())
                ->setPrice($faker->numberBetween($min=500, $max=2000))
                ->setHasBalcony($faker->boolean())
                ->setHasCave($faker->boolean())
                ->setHasParking($faker->boolean())
                ->setHasElevator($faker->boolean())
                ->setIsFurnished($faker->boolean())
                ->setHasTerrace($faker->boolean())
                ->setRooms($rooms)
                ->setSurface($faker->numberBetween($min=15, $max=100))
                ->setType($type)
                ->setOwner($faker->randomElement($owners))
                ->setHeatType($faker->randomElement($heatTypes))
                ->setState($faker->randomElement($states))
            ;
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}