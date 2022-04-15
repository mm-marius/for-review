<?php

namespace App\Entity\Multilanguage;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DescriptorRepository")
 */
class Descriptor extends Multilanguage
{
    const TYPE_GENERIC = 'generic';
    const PLACEHOLDERS = [];

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     */
    private $name;

    public function __construct(string $name, string $content, string $type = self::TYPE_GENERIC, string $language = 'it')
    {
        $this->init($content, $language);
        $this->name = $name;
        $this->type = $type;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}