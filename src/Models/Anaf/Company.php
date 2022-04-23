<?php
namespace App\Models\Anaf;

use App\Services\ParserAnaf;

class Company
{
    /** @var ParserAnaf */
    private $parser;

    /**
     * Company constructor.
     * @param ParserAnaf $parser
     */
    public function __construct(ParserAnaf $parser)
    {
        $this->parser = $parser;
    }

    public function getCIF(): string
    {
        return $this->parser->getData()['cui'] ?? '';
    }

    public function getRegCom(): string
    {
        return $this->parser->getData()['nrRegCom'] ?? '';
    }

    public function getName(): string
    {
        return $this->parser->getData()['denumire'] ?? '';
    }

    public function getPhone(): string
    {
        return $this->parser->getData()['telefon'] ?? '';
    }

    public function getFullAddress(): string
    {
        return $this->parser->getData()['adresa'] ?? '';
    }

    public function getCounty(): string
    {
        return $this->parser->getAddress()[ParserAnaf::COUNTY] ?? "";
    }

    public function getCity(): string
    {
        return $this->parser->getAddress()[ParserAnaf::CITY] ?? "";
    }

    public function getStreet(): string
    {
        return $this->parser->getAddress()[ParserAnaf::STREET] ?? "";
    }

    public function getStreetNumber(): string
    {
        return $this->parser->getAddress()[ParserAnaf::STREET_NUMBER] ?? "";
    }

    public function getBloc()
    {
        return $this->parser->getAddress()[ParserAnaf::BLOC] ?? "";
    }

    public function getScara(): string
    {
        return $this->parser->getAddress()[ParserAnaf::SCARA] ?? "";
    }

    public function getEtaj(): string
    {
        return $this->parser->getAddress()[ParserAnaf::ETAJ] ?? "";
    }

    public function getApart(): string
    {
        return $this->parser->getAddress()[ParserAnaf::APART] ?? "";
    }

    public function getCam(): string
    {
        return $this->parser->getAddress()[ParserAnaf::CAM] ?? "";
    }

    public function getSector(): string
    {
        return $this->parser->getAddress()[ParserAnaf::SECT] ?? "";
    }

    public function getComuna(): string
    {
        return $this->parser->getAddress()[ParserAnaf::COMUNA] ?? "";
    }

    public function getSat(): string
    {
        return $this->parser->getAddress()[ParserAnaf::SAT] ?? "";
    }

    public function getOther(): string
    {
        return $this->parser->getAddress()[ParserAnaf::OTHER] ?? "";
    }

    public function getZipCode(): string
    {
        return $this->parser->getData()['codPostal'] ?? '';
    }

    public function isActive(): bool
    {
        if (empty($this->parser->getData()['statusInactivi']) || !is_bool($this->parser->getData()['statusInactivi'])) {
            return false;
        }

        return !$this->parser->getData()['statusInactivi'];
    }

    public function getInactivationDate(): string
    {
        return $this->parser->getData()['dataInactivare'] ?? '';
    }

    public function getReactivationDate(): string
    {
        return $this->parser->getData()['dataReactivare'] ?? '';
    }

    public function getDeletionDate(): string
    {
        return $this->parser->getData()['dataRadiere'] ?? '';
    }

    public function getTVA(): CompanyTVA
    {
        return new CompanyTVA($this->parser);
    }

    public function getAddress(): CompanyAddress
    {
        return new CompanyAddress($this->parser);
    }
}