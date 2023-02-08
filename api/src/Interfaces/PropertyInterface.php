<?php

namespace App\Interfaces;

interface PropertyInterface
{
    public const TYPE_APPARTMENT = 'Appartment';
    public const TYPE_HOUSE = 'House';

    public const HAS_BALCONY    = 'has_balcony';
    public const HAS_TERRACE    = 'has_terrace';
    public const HAS_CAVE       = 'has_cave';
    public const HAS_ELEVATOR   = 'has_elevator';
    public const HAS_PARKING    = 'has_parking';
    public const IS_FURNISHED   = 'is_furnished';
}