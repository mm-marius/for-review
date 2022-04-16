<?php

namespace App\Entity\Multilanguage;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
class Multilanguage
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

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

    public function init(string $content, string $language = 'it')
    {
        $this->content = $content;
        $this->language = $language;
        $this->creationDateTime = new \DateTime();
        $this->updateDateTime = new \DateTime();
    }

    public function update(Multilanguage $multilanguage)
    {
        $this->content = $multilanguage->getContent();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return str_replace("\n", "", str_replace("\r", "", str_replace("\t", "", $this->content)));
    }

    public function setContent(?string $content): self
    {
        $this->content = $content ? $content : '';
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