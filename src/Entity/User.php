<?php

namespace App\Entity;

use App\Handlers\VIP\VipHandler;
use App\Models\General\DateRange;
use App\Services\SettingService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="email.duplicate")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = false;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $username;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $vatCode;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $taxCode;
    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $uniqueCode;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $businessName;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $pec;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mobilePhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $birthPlace;

    /**
     * @ORM\Column(type="datetime", nullable=true, nullable=true)
     */
    private $birthDate;
    private $birthDatePicker;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $clientCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $publicAdministration;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cig;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAgency;

    /**
     * @var ArrayCollection $privacyFlags
     * @ORM\OneToMany(targetEntity="PrivacyFlag", mappedBy="user" ,cascade={"persist","remove"})
     */
    private $privacyFlags;

    public function __construct()
    {
        $this->privacyFlags = new ArrayCollection();
    }

    public static function fromWP($request, $isData = false)
    {
        $user = new User();
        if (!$request) {
            error_log("request is null!");
            return null;
        }
        $user->setEmail($isData ? $request['email'] : $request->request->get('email'));
        $user->setUsername($isData ? $request['username'] : $request->request->get('username'));
        $user->setFirstName($isData ? $request['firstname'] : $request->request->get('firstname'));
        $user->setLastName($isData ? $request['lastname'] : $request->request->get('lastname'));
        $vatCode = $isData ? ($request['vatCode'] ?: ''): ($request->request->get('vatCode') ?: '');
        $taxCode = $isData ? ($request['taxCode'] ?: ''): ($request->request->get('taxCode') ?: '');
        $user->setVatCode($vatCode);
        $user->setTaxCode($taxCode);
        $user->setBusinessName($isData ? $request['businessName'] : $request->request->get('businessName'));
        $user->setAddress($isData ? $request['address'] : $request->request->get('address'));
        $user->setCity($isData ? $request['city'] : $request->request->get('city'));
        $user->setProvince($isData ? $request['province'] : $request->request->get('province'));
        $user->setState($isData ? ($request['state'] ?? 'Italia') : $request->request->get('state', 'Italia'));
        $user->setZipCode($isData ? $request['zipCode'] : $request->request->get('zipCode'));
        $user->setPhone($isData ? $request['phone'] : $request->request->get('phone'));
        // $user->setNationCode($isData ? ($request['nationCode'] ?? 'IT') : $request->request->get('nationCode', 'IT'));
        $user->setIsAgency($isData ? ($request['isAgency'] ?: false): ($request->request->get('isAgency') ?: false));
        $clientCode = $isData ? ($request['clientCode'] ?? true) : $request->request->get('clientCode') ?? null;
        $clientCode && $user->setClientCode($clientCode);
        return $user;
    }
    public function getIsAgency()
    {
        return $this->isAgency;
    }
    public function setIsAgency(bool $isAgency)
    {
        $this->isAgency = $isAgency;
        return $this;
    }
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email != 'admin' ? $this->email : null;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(string $taxCode): self
    {
        $this->taxCode = $taxCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(string $birthPlace): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getVatCode(): ?string
    {
        return $this->vatCode;
    }

    public function setVatCode(string $vatCode): self
    {
        $this->vatCode = $vatCode;

        return $this;
    }

    public function getUniqueCode(): ?string
    {
        return $this->uniqueCode;
    }

    public function setUniqueCode(string $uniqueCode): self
    {
        $this->uniqueCode = $uniqueCode;

        return $this;
    }

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): self
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function getPec(): ?string
    {
        return $this->pec;
    }

    public function setPec(string $pec): self
    {
        $this->pec = $pec;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    // public function getNationCode(): ?string
    // {
    //     return $this->nationCode;
    // }

    // public function setNationCode(string $nationCode): self
    // {
    //     $this->nationCode = $nationCode;

    //     return $this;
    // }

    public function getClientCode(SettingService $settings): ?string
    {

        $dataConnection = (new VipHandler($settings))->getConnectionData();
        return $this->clientCode ?: $dataConnection['default_client'];
    }

    public function setClientCode(?string $clientCode): self
    {
        $this->clientCode = $clientCode;

        return $this;
    }

    public function getBirthDatePicker(): ?DateRange
    {
        $this->birthDatePicker || $this->birthDatePicker = new DateRange($this->birthDate);
        return $this->birthDatePicker;
    }

    public function setBirthDatePicker(?DateRange $birthDatePicker): self
    {
        $this->birthDatePicker = $birthDatePicker;
        $this->birthDate = $birthDatePicker->datePickerDate;
        return $this;
    }

    public function isBusiness($fakeParam = false/*Unused - do not remove unless you fix isBusiness form map (company registration)*/)
    {
        return $this->businessName != null;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPrivacyFlags()
    {
        return $this->privacyFlags;
    }

    public function getPrivacyFlagByAtlId(int $atlId): ?PrivacyFlag
    {
        /** @var PrivacyFlag $privacyFlag */
        foreach ($this->privacyFlags as $privacyFlag) {
            if ($privacyFlag->getAtlId() == $atlId) {
                return $privacyFlag;
            }
        }
        return null;
    }

    public function addPrivacyFlag(PrivacyFlag $privacyFlag)
    {
        $this->privacyFlags[] = $privacyFlag;
    }

    public function setPrivacyFlags($privacyFlags)
    {
        $this->privacyFlags = $privacyFlags;
    }

    public function removePrivacyFlag(PrivacyFlag $privacyFlag): self
    {
        if ($this->privacyFlags->contains($privacyFlag)) {
            $this->privacyFlags->removeElement($privacyFlag);
            // set the owning side to null (unless already changed)
            if ($privacyFlag->getUser() === $this) {
                $privacyFlag->setUser(null);
            }
        }
        return $this;
    }

    public function getCig()
    {
        return $this->cig;
    }
    public function setCig($cig): self
    {
        $this->cig = $cig;

        return $this;
    }
    public function getCup()
    {
        return $this->cup;
    }
    public function setCup($cup): self
    {
        $this->cup = $cup;

        return $this;
    }
    public function getPublicAdministration()
    {
        return $this->publicAdministration;
    }
    public function setPublicAdministration($publicAdministration): self
    {
        $this->publicAdministration = $publicAdministration;

        return $this;
    }
}