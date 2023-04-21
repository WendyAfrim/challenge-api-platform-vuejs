<?php

namespace App\Factory;

class SlotFactory
{
    public function createSlots(int $slotNumber = 1): array
    {
        $slots = [];
        for($i = 0; $i < $slotNumber; $i++) {
            $randomSlot = mt_rand(1, 2147385600);
            $slots = $randomSlot;
        }

        return $slots;
    }
}