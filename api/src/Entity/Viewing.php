<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ViewingRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['viewing_read']],
    denormalizationContext: ['groups' => ['viewing_write']],
)]
#[ORM\Entity(repositoryClass: ViewingRepository::class)]
class Viewing
{
    use TimestampTrait;
    use EntityIdTrait;

    #[ORM\ManyToOne(inversedBy: 'viewings')]
    #[Groups(['viewing_read', 'viewing_write'])]
    private ?User $agent = null;

    #[ORM\ManyToOne(inversedBy: 'visits')]
    #[Groups(['viewing_read', 'viewing_write'])]
    private ?User $lodger = null;

    #[ORM\OneToOne(mappedBy: 'viewing', cascade: ['persist', 'remove'])]
    #[Groups(['viewing_read', 'viewing_write'])]
    private ?Availaibility $availaibility = null;

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): self
    {
        $this->agent = $agent;

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


    public function getAvailaibility(): ?Availaibility
    {
        return $this->availaibility;
    }

    public function setAvailaibility(?Availaibility $availaibility): self
    {
        // unset the owning side of the relation if necessary
        if ($availaibility === null && $this->availaibility !== null) {
            $this->availaibility->setViewing(null);
        }

        // set the owning side of the relation if necessary
        if ($availaibility !== null && $availaibility->getViewing() !== $this) {
            $availaibility->setViewing($this);
        }

        $this->availaibility = $availaibility;

        return $this;
    }
}
