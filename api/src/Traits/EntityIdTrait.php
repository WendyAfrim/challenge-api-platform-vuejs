<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityIdTrait
{
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $uuid = null;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     *
     * @return EntityIdTrait
     */
    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

}