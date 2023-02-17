<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Helpers\DateFormatterHelper;
use App\Repository\RequestRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[Groups(['request_read', 'request_write', 'viewing_read'])]
    private ?Property $property = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['request_read', 'request_write', 'property_read'])]
    private ?string $state = null;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: Availaibility::class)]
    #[Groups(['request_read', 'property_read'])]
    private Collection $availaibilities;

    public function __construct()
    {
        $this->availaibilities = new ArrayCollection();
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Availaibility>
     */
    public function getAvailaibilities(): Collection
    {
        return $this->availaibilities;
    }

    public function addAvailaibility(Availaibility $availaibility): self
    {
        if (!$this->availaibilities->contains($availaibility)) {
            $this->availaibilities->add($availaibility);
            $availaibility->setRequest($this);
        }

        return $this;
    }

    public function removeAvailaibility(Availaibility $availaibility): self
    {
        if ($this->availaibilities->removeElement($availaibility)) {
            // set the owning side to null (unless already changed)
            if ($availaibility->getRequest() === $this) {
                $availaibility->setRequest(null);
            }
        }

        return $this;
    }

    public function isSlotConform(Request $request, array $slots): bool
    {
        $existingSlotsNumber = $request->getAvailaibilities()->count();

        if ($existingSlotsNumber > 3 || count($slots) > 3 ) {
            return false;
        }

        return true;
    }

    public function countSlotToAdd(Request $request, array $slots): int
    {
        $existingSlotsNumber = $request->getAvailaibilities()->count();
        $addingSlots = count($slots);

        return $addingSlots - $existingSlotsNumber;
    }

    public function createSlot(Availaibility $slot, Request $request): bool
    {
        $slot = ((new Availaibility())
            ->setRequest($request)
            ->setSlot(DateFormatterHelper::stringToDatetime($slot['slot']))
        );

        $this->manager->persist($slot);
    }
}
