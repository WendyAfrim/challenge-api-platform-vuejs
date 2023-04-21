<?php

namespace App\Tests\Api\Unit\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Request;
use App\Factory\RequestFactory;
use App\Factory\SlotFactory;
use App\Service\SlotService;
use Doctrine\ORM\EntityManager;

class SlotServiceTest extends ApiTestCase
{
    private RequestFactory $requestFactory;
    private SlotFactory $slotFactory;
    public function setup(): void
    {
        $this->requestFactory = new RequestFactory();
        $this->slotFactory    = new SlotFactory();
    }

    public function testIsSlotConform(Request $request, array $slots, bool $expected): void
    {
        $managerMock = $this->createMock(EntityManager::class);
        $slotService = new SlotService($managerMock);

        $actual = $slotService->isSlotConform($request, $slots);

        $this->assertEquals($expected, $actual);
    }

    public function getSlots(): array
    {
        return [
            [$this->requestFactory->createRequest(2), $this->slotFactory->createSlots(1), true],
            [$this->requestFactory->createRequest(3), $this->slotFactory->createSlots(2), false],
        ];
    }
}
