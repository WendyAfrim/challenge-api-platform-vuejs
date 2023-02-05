<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\DocumentRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(

    normalizationContext: ['groups' => ['document_read']],
    denormalizationContext: ['groups' => ['document_write']],
)]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    use TimestampTrait;
    use EntityIdTrait;

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

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/file'])]
    #[Groups(['document_read', 'document_write'])]
    public ?MediaObject $file = null;

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

    public function getFileDocument(): ?MediaObject
    {
        return $this->file;
    }
    public function setFileDocument(?MediaObject $file): self
    {
        $this->file = $file;

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
