<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DocumentRepository;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['document_read']],
    denormalizationContext: ['groups' => ['document_write']],
)]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['document_read', 'document_write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['document_read', 'document_write'])]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[Groups(['document_read', 'document_write'])]
    private ?User $user_document = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['document_read', 'document_write'])]
    private ?bool $is_valid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUserDocument(): ?User
    {
        return $this->user_document;
    }

    public function setUserDocument(?User $user_document): self
    {
        $this->user_document = $user_document;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(?bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }
}
