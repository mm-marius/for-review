<?php

namespace App\Entity\FormField;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */
class FormField
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_BUTTON = 'button';
    const TYPE_DATE = 'date';
    const TYPE_EMAIL = 'email';
    const TYPE_INTEGER = 'integer';
    const TYPE_MULTICHOICE_SELECT = 'multiselect';
    const TYPE_PHONE = 'phone';
    const TYPE_SELECT = 'select';
    const TYPE_STRING = 'string';
    const TYPE_AUTOCOMPLETE = "autocomplete";

    const CONTROL_INTEGER_MIN = 'min';
    const CONTROL_INTEGER_MAX = 'max';
    const CONTROL_STRING_MAX_LENGTH = 'maxLength';
    const CONTROL_STRING_MIN_LENGTH = 'minLength';
    const CONTROL_AVAILABLE_VALUES = 'availableValues';
    const CONTROL_MOD_DEPENDENCY = 'modDependency';
    const CONTROL_MOD_DEPENDENCY_BEHAVIOR = 'modDependencyBehavior';

    const DEPENDENCY_BEHAVIOR_AND = 'AND';
    const DEPENDENCY_BEHAVIOR_OR = 'OR';

    const TYPES = [
        self::TYPE_BOOLEAN,
        self::TYPE_BUTTON,
        self::TYPE_DATE,
        self::TYPE_EMAIL,
        self::TYPE_INTEGER,
        self::TYPE_MULTICHOICE_SELECT,
        self::TYPE_PHONE,
        self::TYPE_SELECT,
        self::TYPE_STRING,
        Settings::TYPE_RULES_DEPOSIT, //FIXME find a way to add this only for Setting entity
        Settings::TYPE_RULES_OPUNIT, //FIXME find a way to add this only for Setting entity
    ];

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=200)
     */
    protected $name;

    /**
     * @ORM\Column(type="string",length=60 ,nullable=true)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255 ,nullable=true)
     */
    protected $responsive;
    /**
     * @ORM\Column(type="json",nullable=true)
     */
    protected $controls;
    /**
     * @ORM\Column(type="smallint",options={"unsigned":true})
     */
    protected $weight;
    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $type;

    /*-------- GETTER & SETTER ---------*/

    protected function init(string $name, string $type, bool $hasDescription, string $responsive, int $weight)
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $hasDescription ? $name . '.description' : '';
        $this->weight = $weight;
        $this->responsive = $responsive;
        $this->controls = [];
    }

    public function getId(): ?string
    {return $this->id;}

    public function getName(): ?string
    {return $this->name;}

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {return $this->type;}

    public function setType(string $type): self
    {
        in_array($type, self::TYPES) && $this->type = $type;
        empty($this->type) && $this->type = self::TYPE_STRING;
        return $this;
    }

    public function getResponsive(): ?string
    {return $this->responsive;}

    public function setResponsive(?string $responsive): self
    {
        $this->responsive = $responsive;
        return $this;
    }

    public function getDescription(): ?string
    {return $this->description;}

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getWeight(): ?int
    {return $this->weight;}

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getControls(): ?array
    {return $this->controls;}

    public function setControls(array $controls): self
    {
        $this->controls = $controls;
        return $this;
    }

}
