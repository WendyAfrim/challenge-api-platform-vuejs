<?php

namespace App\Tests\Api\Unit\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Availaibility;
use App\Entity\Viewing;
use App\Factory\AvailabilityFactory;
use App\Factory\AvailaibilityFactory;
use App\Service\LocationService;
use App\Service\SlotService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Test\TestCase;

class LocationServiceTest extends ApiTestCase
{

    public function testCreateViewing()
    {
        $locationService = new LocationService();
        $availaibilityFactory = new AvailabilityFactory();

        $choosenAvailability = $availaibilityFactory->createAvailability(false);
        $expected = Viewing::class;

        $actual = $locationService->createViewing($choosenAvailability);
        $this->assertInstanceOf($expected, $actual);
    }

    public function testCreateViewingFailing()
    {
        $locationService = new LocationService();
        $availaibilityFactory = new AvailabilityFactory();

        $choosenAvailability = $availaibilityFactory->createAvailability(true);

        $this->expectException(\Exception::class);
        $locationService->createViewing($choosenAvailability);
    }
}
