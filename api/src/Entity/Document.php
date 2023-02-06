<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\CreateDocumentAction;
use App\Controller\CreateMediaObjectAction;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\File\File;
use App\Traits\EntityIdTrait;
use App\Traits\TimestampTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    types: ['https://schema.org/MediaObject'],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            controller: CreateDocumentAction::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ],
                                    'name'=> [
                                        'type' => 'string',
                                    ],
                                    'type'=> [
                                        'type' => 'string',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            validationContext: ['groups' => ['Default', 'media_object_create']],
            deserialize: false
        )
    ],
    normalizationContext: ['groups' => ['document_read']],
    denormalizationContext: ['groups' => ['document_write']]
)]

#[Vich\Uploadable]
#[ORM\Entity]
class Document
{
    use TimestampTrait;
    use EntityIdTrait;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['document_write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['media_object:read'])]
    public ?string $filePath = null;


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

    public function getFileDocument(): ?File
    {
        return $this->file;
    }
    public function setFileDocument(?File $file): self
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
