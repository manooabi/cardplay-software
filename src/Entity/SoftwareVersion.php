<?php

namespace App\Entity;

use App\Repository\SoftwareVersionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoftwareVersionRepository::class)]
class SoftwareVersion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $systemVersion = null;

    #[ORM\Column(length: 50)]
    private ?string $systemVersionAlt = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gdLink = null;

    #[ORM\Column(nullable: true)]
    private ?bool $latest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSystemVersion(): ?string
    {
        return $this->systemVersion;
    }

    public function setSystemVersion(string $systemVersion): static
    {
        $this->systemVersion = $systemVersion;

        return $this;
    }

    public function getSystemVersionAlt(): ?string
    {
        return $this->systemVersionAlt;
    }

    public function setSystemVersionAlt(string $systemVersionAlt): static
    {
        $this->systemVersionAlt = $systemVersionAlt;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getStLink(): ?string
    {
        return $this->stLink;
    }

    public function setStLink(?string $stLink): static
    {
        $this->stLink = $stLink;

        return $this;
    }

    public function getGdLink(): ?string
    {
        return $this->gdLink;
    }

    public function setGdLink(?string $gdLink): static
    {
        $this->gdLink = $gdLink;

        return $this;
    }

    public function isLatest(): ?bool
    {
        return $this->latest;
    }

    public function setLatest(?bool $latest): static
    {
        $this->latest = $latest;

        return $this;
    }
}
