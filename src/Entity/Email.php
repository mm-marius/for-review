<?php

namespace App\Entity;

use App\Entity\Multilanguage\Descriptor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailRepository")
 */
class Email
{
    const TYPE_REGISTRATION = 'registration';
    const TYPE_FORGOT = 'forgot';
    const TYPE_PAYMENT = 'payment';
    const TYPE_PURCHASED = 'purchased';
    const TYPE_PAY_REMINDER = 'payReminder';
    const TYPE_PURCHASED_BANK_TRANSFER = 'purchaseBankTransfer';
    const TYPE_PURCHASED_QUOTATION = 'purchasePrenotation';
    const TYPE_PURCHASED_REQUEST = 'purchaseRequest';
    const TYPE_PURCHASED_CREDIT_CARD_WARRANTY = 'purchasedWarranty';
    const TYPE_BOOKED_TO_ADMIN = 'bookedToAdmin';
    const TYPE_ACTIVATION_ADMIN = 'adminActivation';
    const TYPE_WELFARE_JAKALA = 'welfareJakala';
    const TYPE_WELFARE_DOUBLEYOU = 'welfareDoubleYou';
    const TYPE_WELFARE_PELLEGRINI = 'welfarePellegrini';
    const TYPE_WELFARE_JOINTLY = 'welfareJointly';

    const PLACEHOLDER_USER_NAME = 'user.firstName';
    const PLACEHOLDER_USER_LASTNAME = 'user.lastName';
    const PLACEHOLDER_USER_BUSINESSNAME = 'user.businessName';
    const PLACEHOLDER_USER_CLIENT_CODE = 'user.clientCode';
    const PLACEHOLDER_USER_ACTIVATION_LINK = 'user.activationLink';
    const PLACEHOLDER_USER_FORGOT_LINK = 'user.forgotLink';
    const PLACEHOLDER_ORDER_TOTAL = 'order.totalPrice';
    const PLACEHOLDER_ORDER_DAYS = 'order.days';
    const PLACEHOLDER_TOTAL_PAYED = 'order.totalPayed';
    const PLACEHOLDER_FIRST_PAX = 'order.firstPax';
    const PLACEHOLDER_FIRST_PERIOD = 'order.firstPeriod';
    const PLACEHOLDER_FIRST_PRODUCT = 'order.firstProduct';
    const PLACEHOLDER_ORDER_DATE = 'order.creationDate';
    const PLACEHOLDER_ORDER_DETAIL = 'order.detail';
    const PLACEHOLDER_ORDER_PAYED = 'order.payed';
    const PLACEHOLDER_ORDER_REMAIN = 'order.payRemain';
    const PLACEHOLDER_DOSSIER_NOTE = 'dossier.note';
    const PLACEHOLDER_DOSSIER_REFERER = 'dossier.referer';
    const PLACEHOLDER_DOSSIER_CODE = 'dossier.code';
    const PLACEHOLDER_DOSSIER_STATUS = 'dossier.status';

    const PLACEHOLDERS = [
        self::PLACEHOLDER_USER_NAME,
        self::PLACEHOLDER_USER_LASTNAME,
        self::PLACEHOLDER_USER_BUSINESSNAME,
        self::PLACEHOLDER_USER_CLIENT_CODE,
        self::PLACEHOLDER_USER_ACTIVATION_LINK,
        self::PLACEHOLDER_USER_FORGOT_LINK,
        self::PLACEHOLDER_ORDER_TOTAL,
        self::PLACEHOLDER_ORDER_DAYS,
        self::PLACEHOLDER_ORDER_DATE,
        self::PLACEHOLDER_ORDER_DETAIL,
        self::PLACEHOLDER_ORDER_PAYED,
        self::PLACEHOLDER_ORDER_REMAIN,
        self::PLACEHOLDER_TOTAL_PAYED,
        self::PLACEHOLDER_DOSSIER_NOTE,
        self::PLACEHOLDER_DOSSIER_REFERER,
        self::PLACEHOLDER_DOSSIER_CODE,
        self::PLACEHOLDER_DOSSIER_STATUS,
    ];

    const PLACEHOLDERS_SUBJECT = [
        self::PLACEHOLDER_USER_NAME,
        self::PLACEHOLDER_USER_LASTNAME,
        self::PLACEHOLDER_USER_BUSINESSNAME,
        self::PLACEHOLDER_USER_CLIENT_CODE,
        self::PLACEHOLDER_ORDER_TOTAL,
        self::PLACEHOLDER_FIRST_PAX,
        self::PLACEHOLDER_FIRST_PERIOD,
        self::PLACEHOLDER_FIRST_PRODUCT,
        self::PLACEHOLDER_TOTAL_PAYED,
        self::PLACEHOLDER_DOSSIER_CODE,
        self::PLACEHOLDER_DOSSIER_STATUS,
        self::PLACEHOLDER_ORDER_DATE,
    ];

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
     * @var ArrayCollection $contents
     * @ORM\ManyToMany(targetEntity="\App\Entity\Multilanguage\Descriptor",cascade={"persist"})
     * @ORM\JoinTable(name="email_content_join",
     *  joinColumns={@ORM\JoinColumn(name="descriptor_ref", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="parent_descriptor", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $contents;
    /**
     * @var ArrayCollection $subjects
     * @ORM\ManyToMany(targetEntity="\App\Entity\Multilanguage\Descriptor",cascade={"persist"})
     * @ORM\JoinTable(name="email_subject_join",
     *  joinColumns={@ORM\JoinColumn(name="descriptor_ref", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="parent_descriptor", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $subjects;
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
    private $updateDateTime;
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"1"})
     */
    private $enable;

    public function __construct($name, String $type = self::TYPE_REGISTRATION)
    {
        $this->name = $name;
        $this->type = $type;
        $this->content = new ArrayCollection();
        $this->subject = new ArrayCollection();
        $this->creationDateTime = new \DateTime();
        $this->updateDateTime = new \DateTime();
        $this->contents = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    /**
     * @return Collection|Descriptor[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Descriptor $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
        }

        return $this;
    }

    public function removeContent(Descriptor $content): self
    {
        $this->contents->removeElement($content);

        return $this;
    }

    /**
     * @return Collection|Descriptor[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Descriptor $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
        }

        return $this;
    }

    public function removeSubject(Descriptor $subject): self
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(?bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

}