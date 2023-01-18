<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Faker\Provider\Address;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $types = array("apartment", "House");
        $owners = $manager->getRepository(User::class)->findBy(['roles' => User::ROLE_OWNER]);

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