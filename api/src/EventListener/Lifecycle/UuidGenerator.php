<?php

namespace App\EventListener\Lifecycle;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use App\Generator\UuidGenerator as UuidGeneratorV4;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UuidGenerator implements EventSubscriber
{
    public function __construct(private readonly UuidGeneratorV4 $uuidGenerator)
    {

    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->setUuid($args);
    }

    public function setUuid(LifecycleEventArgs $args): bool
    {
        $entity = $args->getObject();

        if (!property_exists(get_class($entity), 'uuid')){
            return false;
        }

        if (empty($entity->getUuid()) && ($em = $args->getObjectManager()) instanceof EntityManager){
            $entity->setUuid($this->uuidGenerator->generate($em, $entity));
        }

        return true;
    }

    public function isPostInsertGenerator() : bool
    {
        return true;
    }
}