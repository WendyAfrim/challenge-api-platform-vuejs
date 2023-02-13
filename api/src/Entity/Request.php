<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RequestRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['request_read', 'all', 'all_timestamp', 'property_read']],
    denormalizationContext: ['groups' => ['request_write']]
)]
#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    use TimestampTrait;
    use EntityIdTrait;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups(['request_read', 'request_write', 'property_read', 'user_details'])]
    private ?User $lodger = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups(['request_read', 'request_write'])]
    private ?Property $property = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['request_read', 'request_write'])]
    private ?string $state = null;

    public function getLodger(): ?User
    {
        return $this->lodger;
    }

    public function setLodger(?User $lodger): self
    {
        $this->lodger = $lodger;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
}
