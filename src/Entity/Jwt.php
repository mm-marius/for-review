<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JwtRepository")
 */
class Jwt
{
    const TYPE_ACTIVATION = 'activation';
    const TYPE_FORGOT = 'forgot';
    const TYPE_LOGIN = 'login';

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDateTime;
    /**
     * @ORM\Column(type="datetime")
     */
    private $expirationDateTime;
    /**
     * @ORM\Column(type="boolean")
     */
    private $used;

    public function __construct(User $user, String $type, DateTime $expirationDateTime = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->used = false;
        $this->creationDateTime = new \DateTime();
        if (!$expirationDateTime) {
            $expirationDateTime = new \DateTime();
            $expirationDateTime->add(new \DateInterval('PT1H'));
        }
        $this->expirationDateTime = $expirationDateTime;
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

    public function getExpirationDateTime(): ?\DateTimeInterface
    {
        return $this->expirationDateTime;
    }

    public function setExpirationDateTime(\DateTimeInterface $expirationDateTime): self
    {
        $this->expirationDateTime = $expirationDateTime;

        return $this;
    }

    public function getUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}