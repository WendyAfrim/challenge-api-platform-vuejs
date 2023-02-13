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

    #[ORM\ManyToOne(inversedBy: 'availaibilities')]
    #[Groups(['availaibility_read', 'availaibility_write', 'viewing_read'])]
    private ?Property $property = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['availaibility_read', 'availaibility_write', 'viewing_read'])]
    private ?\DateTimeInterface $slot = null;

    #[ORM\ManyToOne(inversedBy: 'availaibilities')]
    #[Groups(['availaibility_read', 'availaibility_write', 'viewing_read'])]
    private ?User $lodger = null;


    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

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

    public function getLodger(): ?User
    {
        return $this->lodger;
    }

    public function setLodger(?User $lodger): self
    {
        $this->lodger = $lodger;

        return $this;
    }
}
