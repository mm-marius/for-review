<?php

namespace App\Entity;

use App\Repository\GeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GeneralRepository::class)
 * @ORM\Table(name="`general`")
 */
class General
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $code;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $value1;
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $value2;
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $value3;
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $value4;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue1()
    {
        return $this->value1;
    }

    public function setValue1($value1): self
    {
        $this->value1 = $value1;

        return $this;
    }

    public function getValue2()
    {
        return $this->value2;
    }

    public function setValue2($value2): self
    {
        $this->value2 = $value2;

        return $this;
    }

    public function getValue3()
    {
        return $this->value3;
    }

    public function setValue3($value3): self
    {
        $this->value3 = $value3;

        return $this;
    }

    public function getValue4()
    {
        return $this->value4;
    }

    public function setValue4($value4): self
    {
        $this->value4 = $value4;

        return $this;
    }
}