<?php

namespace App\Service;

use App\Entity\Availaibility;
use App\Entity\Request;
use App\Entity\Viewing;
use App\Enums\RequestEnum;
use App\Helpers\DateFormatterHelper;
use App\Repository\AvailaibilityRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;

class SlotService
{
    public const SLOT_LIMIT = 3;
    public function __construct(
        private readonly EntityManagerInterface  $manager
    )
    {
    }

    public function isSlotConform(Request $request, array $slots): bool
    {
        $existingSlotsNumber = $request->getAvailaibilities()->count();

        if ($existingSlotsNumber >= 3 || count($slots) > 3) {
            return false;
        }

        return true;
    }

    public function countSlotToAdd(Request $request, array $slots): int
    {
        $existingSlotsNumber = $request->getAvailaibilities()->count();
        $addingSlots = count($slots);

        $totalSlots = $addingSlots + $existingSlotsNumber;
        if($totalSlots <= 3) {
            $limit = $totalSlots;
        } else {
            $limit = $addingSlots - $existingSlotsNumber;
        }

        return $limit;
    }

    public function createSlot(array $slot, Request $request): void
    {
        $slot = ((new Availaibility())
            ->setRequest($request)
            ->setSlot(DateFormatterHelper::stringToDatetime(current($slot)))
        );

        $request->addAvailaibility($slot);
        $this->manager->persist($slot);
    }
}