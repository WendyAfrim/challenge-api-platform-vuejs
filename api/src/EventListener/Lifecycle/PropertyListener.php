<?php

namespace App\EventListener\Lifecycle;

use App\Entity\Availaibility;
use App\Entity\Property;
use App\Service\EmailService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;

class PropertyListener implements EventSubscriber
{
    public function __construct(
        private readonly RequestStack $request,
    )
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
        $this->persistProperty($args);
    }

    public function persistProperty(LifecycleEventArgs $args): bool
    {
        $entity = $args->getObject();

        $request = $this->request->getCurrentRequest();
        if (!$request) {
            return true;
        }
        $data = json_decode($request->getContent(), true);

        if (Property::class ===  get_class($entity)) {
            $this->persistPropertyAmenities($entity, $data);
        }

        return true;
    }

    private function persistPropertyAmenities(Property $property, array $data): void
    {
        foreach ($data as $key => $value) {

            if (Property::HAS_BALCONY === $key) {
                $property->setHasBalcony($value);
            }

            if (Property::HAS_TERRACE === $key) {
                $property->setHasTerrace($value);
            }

            if (Property::HAS_CAVE === $key) {
                $property->setHasCave($value);
            }

            if (Property::HAS_ELEVATOR === $key) {
                $property->setHasElevator($value);
            }

            if (Property::HAS_PARKING === $key) {
                $property->setHasParking($value);
            }

            if (Property::IS_FURNISHED === $key) {
                $property->setIsFurnished($value);
            }
        }
    }

    public function isPostInsertGenerator() : bool
    {
        return true;
    }
}