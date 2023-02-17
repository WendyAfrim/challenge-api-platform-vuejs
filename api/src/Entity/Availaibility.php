<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Helpers\DateFormatterHelper;
use App\Repository\AvailaibilityRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['availaibility_read', 'all_id']],
    denormalizationContext: ['groups' => ['availaibility_write']],
)]
#[ORM\Entity(repositoryClass: AvailaibilityRepository::class)]
class Availaibility
{
    use TimestampTrait;
    use EntityIdTrait;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['availaibility_read', 'availaibility_write', 'viewing_read', 'property_read'])]
    private ?\DateTimeInterface $slot = null;

    #[ORM\ManyToOne(inversedBy: 'availaibilities')]
    #[Groups(['availaibility_read', 'viewing_read'])]
    private ?Request $request = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['availaibility_read', 'property_read'])]
    private ?string $state = null;

    #[ORM\OneToOne(targetEntity: 'Viewing', mappedBy: 'availaibility', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'viewing_id', referencedColumnName: 'id', nullable: true)]
    #[Groups(['availaibility_read', 'property_read'])]
    private ?Viewing $viewing = null;

    public function getSlot(): \DateTimeInterface|string|null
    {
        if (null !== $this->slot) {
            return DateFormatterHelper::dateTimeToString($this->slot);
        }
        return $this->slot;
    }

    public function setSlot(?\DateTimeInterface $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

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

    public function getViewing(): ?Viewing
    {
        return $this->viewing;
    }

    public function setViewing(?Viewing $viewing): void
    {
        $this->viewing = $viewing;
    }
}
