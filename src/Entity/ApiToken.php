<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{
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
     * @ORM\Column(type="string", length=100)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expirationDateTime;

    public function __construct(User $user, string $apiToken, DateTime $expirationDateTime = null)
    {
        $this->user = $user;
        $this->apiToken = $apiToken;
        if (!$expirationDateTime) {
            $expirationDateTime = new \DateTime();
            $expirationDateTime->add(new \DateInterval('PT6H'));
        }
        $this->expirationDateTime = $expirationDateTime;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function isExpired(): bool
    {
        return $this->getExpirationDateTime() <= new \DateTime();
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

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

}