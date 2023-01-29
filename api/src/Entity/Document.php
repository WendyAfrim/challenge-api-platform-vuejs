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
    #[Groups(['document_read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['document_write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;


    #[ORM\Column(length: 255)]
    #[Groups(['document_read', 'document_write', 'user_write'])]
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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?UploadedFile $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

}
