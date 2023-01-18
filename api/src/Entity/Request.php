<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RequestRepository;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: (['request_read']),
    denormalizationContext: (['request_write'])
)]
#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups(['request_read', 'request_write'])]
    private ?User $lodger = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[Groups(['request_read', 'request_write'])]
    private ?Property $property = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['request_read', 'request_write'])]
    private ?bool $is_accepted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function isIsAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(?bool $is_accepted): self
    {
        $this->is_accepted = $is_accepted;

        return $this;
    }
}
