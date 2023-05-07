<?php

namespace App\Tests\Api\Unit\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Request;
use App\Factory\RequestFactory;
use App\Factory\AvailabilityFactory;
use App\Service\SlotService;
use Doctrine\ORM\EntityManager;

class SlotServiceTest extends ApiTestCase
{

    /**
     * @dataProvider provideSlots
     */
    public function testIsSlotConform(Request $request, array $slots, bool $expected): void
    {
        $managerMock = $this->createMock(EntityManager::class);
        $slotService = new SlotService($managerMock);

        $actual = $slotService->isSlotConform($request, $slots);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider provideSlotsForCounting
     */
    public function testCountSlotToAdd(Request $request, array $slots, int $expected): void
    {
        $managerMock = $this->createMock(EntityManager::class);
        $slotService = new SlotService($managerMock);

        $actual = $slotService->countSlotToAdd($request, $slots);

        $this->assertEquals($expected, $actual);
    }

    public static function provideSlots(): array
    {
        $requestFactory = new RequestFactory();
        $slotFactory    = new AvailabilityFactory();

        return [
            [$requestFactory->createRequest(2), $slotFactory->createSlots(), true],
            [$requestFactory->createRequest(3), $slotFactory->createSlots(), false],
        ];
    }

    public static function provideSlotsForCounting(): array
    {
        $requestFactory = new RequestFactory();
        $slotFactory    = new AvailabilityFactory();

        return [
            [$requestFactory->createRequest(2), $slotFactory->createSlots(), 3],
            [$requestFactory->createRequest(1), $slotFactory->createSlots(), 1],
        ];
    }
}
