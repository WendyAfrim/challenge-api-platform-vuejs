<?php

namespace App\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\UuidV4;

class UuidGenerator extends AbstractIdGenerator
{

    public function generate(EntityManager $em, $entity)
    {
        return new UuidV4();
    }

}