<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    normalizationContext: ['groups' => ['user_read']],
    denormalizationContext: ['groups' => ['user_write']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User
{
    use TimestampTrait;

    public const ROLE_LODGER = 'ROLE_LODGER';
    public const ROLE_AGENCY = 'ROLE_AGENCY';
    public const ROLE_OWNER = 'ROLE_OWNER';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\NotBlank]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user_read', 'user_write'])]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $roles = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $situation = null;

    #[ORM\OneToMany(mappedBy: 'user_document', targetEntity: Document::class)]
    #[Groups(['user_read'])]
    private ?Collection $documents = null;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: Viewing::class)]
    #[Groups(['user_read'])]
    private Collection $viewings;

    #[ORM\OneToMany(mappedBy: 'lodger', targetEntity: Viewing::class)]
    #[Groups(['user_read'])]
    private Collection $visits;

    #[ORM\Column(nullable:true)]
    private ?int $salary = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $income_source = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Property::class)]
    private Collection $properties;

    #[ORM\OneToMany(mappedBy: 'lodger', targetEntity: Request::class)]
    private Collection $requests;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->userProperties = new ArrayCollection();
        $this->viewings = new ArrayCollection();
        $this->visits = new ArrayCollection();
        $this->ownerProperties = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(?Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setUserDocument($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUserDocument() === $this) {
                $document->setUserDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Viewing>
     */
    public function getViewings(): Collection
    {
        return $this->viewings;
    }

    public function addViewing(Viewing $viewing): self
    {
        if (!$this->viewings->contains($viewing)) {
            $this->viewings->add($viewing);
            $viewing->setAgent($this);
        }

        return $this;
    }

    public function removeViewing(Viewing $viewing): self
    {
        if ($this->viewings->removeElement($viewing)) {
            // set the owning side to null (unless already changed)
            if ($viewing->getAgent() === $this) {
                $viewing->setAgent(null);
            }
        }

        return $this;
    }

    public function getViewing(): ?Viewing
    {
        return $this->viewing;
    }

    public function setViewing(?Viewing $viewing): self
    {
        $this->viewing = $viewing;

        return $this;
    }

    /**
     * @return Collection<int, Viewing>
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Viewing $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits->add($visit);
            $visit->setLodger($this);
        }

        return $this;
    }

    public function removeVisit(Viewing $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getLodger() === $this) {
                $visit->setLodger(null);
            }
        }

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(?int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getIncomeSource(): ?string
    {
        return $this->income_source;
    }

    public function setIncomeSource(?string $income_source): self
    {
        $this->income_source = $income_source;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setOwner($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getOwner() === $this) {
                $property->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setLodger($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getLodger() === $this) {
                $request->setLodger(null);
            }
        }

        return $this;
    }
}
