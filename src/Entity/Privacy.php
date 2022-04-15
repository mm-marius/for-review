<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrivacyRepository")
 */
class Privacy
{
    const TYPE_REGISTRATION = 'registration';

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=3)
     */
    private $language;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDateTime;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDateTime;

    public function __construct($name, String $type = self::TYPE_REGISTRATION)
    {
        $this->name = $name;
        $this->language = 'it';
        $this->type = $type;
        $this->creationDateTime = new \DateTime();
        $this->updateDateTime = new \DateTime();
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreationDateTime(): ?\DateTimeInterface
    {
        return $this->creationDateTime;
    }

    public function setCreationDateTime(\DateTimeInterface $creationDateTime): self
    {
        $this->creationDateTime = $creationDateTime;

        return $this;
    }

    public function getUpdateDateTime(): ?\DateTimeInterface
    {
        return $this->updateDateTime;
    }

    public function setUpdateDateTime(\DateTimeInterface $updateDateTime): self
    {
        $this->updateDateTime = $updateDateTime;

        return $this;
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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

}