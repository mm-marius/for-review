<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrivacyFlagRepository")
 */
class PrivacyFlag
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text" ,nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mandatory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requiredYesToBooking;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requiredYesToSendEmail;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $checked;

    /**
     * @ORM\Column(type="integer")
     */
    private $atlId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privacyFlags", cascade={"persist","remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $consentDateTime;

    /*----- METHODS -----*/
    public function __construct(int $atlId, string $title, bool $mandatory, bool $requiredYesToBooking, bool $requiredYesToSendEmail)
    {
        $this->atlId = $atlId;
        $this->title = $title;
        //Changed the handling of checkbox mandatory to match requiredYesToBooking. If there is need of the mandatory
        //(which means you ought to pass the value of the flag), then change the form input required field to match requiredYesToBooking
        //instead of mandatory, then change the line below to take mandatory value
        $this->mandatory = $requiredYesToBooking; //? true : $mandatory;
        $this->requiredYesToBooking = $requiredYesToBooking;
        $this->requiredYesToSendEmail = $requiredYesToSendEmail;
    }

    public function getId(): ?string
    {return $this->id;}

    public function getTitle(): ?string
    {return $this->title;}

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {return $this->description;}

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getMandatory(): ?bool
    {return $this->mandatory;}

    public function setMandatory(bool $mandatory): self
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    public function getRequiredYesToBooking(): ?bool
    {return $this->requiredYesToBooking;}

    public function setRequiredYesToBooking(bool $requiredYesToBooking): self
    {
        $this->requiredYesToBooking = $requiredYesToBooking;
        return $this;
    }

    public function getRequiredYesToSendEmail(): ?bool
    {return $this->requiredYesToSendEmail;}

    public function setRequiredYesToSendEmail(bool $requiredYesToSendEmail): self
    {
        $this->requiredYesToSendEmail = $requiredYesToSendEmail;
        return $this;
    }

    public function getChecked(): ?bool
    {return $this->checked;}

    public function setChecked(bool $checked): self
    {
        $this->checked = (bool) $checked;
        $this->setConsentDateTime(new \DateTime());
        return $this;
    }

    public function getUser(): ?User
    {return $this->user;}

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getAtlId(): ?int
    {
        return $this->atlId;
    }

    public function setAtlId(int $atlId): self
    {
        $this->atlId = $atlId;
        return $this;
    }

    public function getConsentDateTime(): ?\DateTimeInterface
    {
        return $this->consentDateTime;
    }

    public function setConsentDateTime(?\DateTimeInterface $consentDateTime): self
    {
        $this->consentDateTime = $consentDateTime;
        return $this;
    }
}