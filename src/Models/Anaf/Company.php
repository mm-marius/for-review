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

    /**
     * @return string
     */
    public function getCIF(): string
    {
        return $this->parser->getData()['cui'] ?? '';
    }

    /**
     * @return string
     */
    public function getRegCom(): string
    {
        return $this->parser->getData()['nrRegCom'] ?? '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->parser->getData()['denumire'] ?? '';
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->parser->getData()['telefon'] ?? '';
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return $this->parser->getData()['adresa'] ?? '';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        if (empty($this->parser->getData()['statusInactivi']) || !is_bool($this->parser->getData()['statusInactivi'])) {
            return false;
        }

        return !$this->parser->getData()['statusInactivi'];
    }

    /**
     * @return string
     */
    public function getInactivationDate(): string
    {
        return $this->parser->getData()['dataInactivare'] ?? '';
    }

    /**
     * @return string
     */
    public function getReactivationDate(): string
    {
        return $this->parser->getData()['dataReactivare'] ?? '';
    }

    /**
     * @return string
     */
    public function getDeletionDate(): string
    {
        return $this->parser->getData()['dataRadiere'] ?? '';
    }

    /**
     * @return CompanyTVA
     */
    public function getTVA(): CompanyTVA
    {
        return new CompanyTVA($this->parser);
    }

    /**
     * @return CompanyAddress
     */
    public function getAddress(): CompanyAddress
    {
        return new CompanyAddress($this->parser);
    }

    public function getSplitAddress()
    {
        return new CompanyAddress($this->parser);
    }

}