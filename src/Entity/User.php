<?php

namespace App\Entity;

use App\Handlers\VIP\VipHandler;
use App\Services\SettingService;
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
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $clientCode;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $username;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $businessName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $bloc;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $scara;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $etaj;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $apart;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $cam;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comuna;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressFull;

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
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $agreeTerms = false;

    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    private $avatarUrl;

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
        $user->setVatCode($vatCode);
        $user->setBusinessName($isData ? $request['businessName'] : $request->request->get('businessName'));
        $user->setAddressFull($isData ? $request['addressFull'] : $request->request->get('addressFull'));
        $user->setCity($isData ? $request['city'] : $request->request->get('city'));
        $user->setCounty($isData ? $request['county'] : $request->request->get('county'));
        $user->setZipCode($isData ? $request['zipCode'] : $request->request->get('zipCode'));
        $user->setPhone($isData ? $request['phone'] : $request->request->get('phone'));
        $clientCode = $isData ? ($request['clientCode'] ?? true) : $request->request->get('clientCode') ?? null;
        $clientCode && $user->setClientCode($clientCode);
        return $user;
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

    public function getAddressFull(): ?string
    {
        return $this->addressFull;
    }

    public function setAddressFull(string $addressFull): self
    {
        $this->addressFull = $addressFull;

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

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $this->county = $county;

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

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): self
    {
        $this->businessName = $businessName;

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

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAgreeTerms(): ?bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(bool $agreeTerms): self
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    /**
     * Get the value of street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     */
    public function setStreet($street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of streetNumber
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set the value of streetNumber
     */
    public function setStreetNumber($streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get the value of bloc
     */
    public function getBloc()
    {
        return $this->bloc;
    }

    /**
     * Set the value of bloc
     */
    public function setBloc($bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }

    /**
     * Get the value of scara
     */
    public function getScara()
    {
        return $this->scara;
    }

    /**
     * Set the value of scara
     */
    public function setScara($scara): self
    {
        $this->scara = $scara;

        return $this;
    }

    /**
     * Get the value of etaj
     */
    public function getEtaj()
    {
        return $this->etaj;
    }

    /**
     * Set the value of etaj
     */
    public function setEtaj($etaj): self
    {
        $this->etaj = $etaj;

        return $this;
    }

    /**
     * Get the value of apart
     */
    public function getApart()
    {
        return $this->apart;
    }

    /**
     * Set the value of apart
     */
    public function setApart($apart): self
    {
        $this->apart = $apart;

        return $this;
    }

    /**
     * Get the value of cam
     */
    public function getCam()
    {
        return $this->cam;
    }

    /**
     * Set the value of cam
     */
    public function setCam($cam): self
    {
        $this->cam = $cam;

        return $this;
    }

    /**
     * Get the value of sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     */
    public function setSector($sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get the value of comuna
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * Set the value of comuna
     */
    public function setComuna($comuna): self
    {
        $this->comuna = $comuna;

        return $this;
    }

    /**
     * Get the value of sat
     */
    public function getSat()
    {
        return $this->sat;
    }

    /**
     * Set the value of sat
     */
    public function setSat($sat): self
    {
        $this->sat = $sat;

        return $this;
    }

    /**
     * Get the value of other
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Set the value of other
     */
    public function setOther($other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * Set the value of other
     */
    public function setAvatarUrl($avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }
}