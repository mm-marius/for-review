<?php
namespace App\Models\Anaf;

use App\Services\ParserAnaf;

class CompanyAddress
{
    /** @var Parser */
    private $parser;

    /**
     * CompanyAddress constructor.
     * @param ParserAnaf $parser
     */
    public function __construct(ParserAnaf $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return string
     */
    public function getCounty(): string
    {
        return $this->parser->getAddress()[ParserAnaf::COUNTY];
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->parser->getAddress()[ParserAnaf::CITY];
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->parser->getAddress()[ParserAnaf::STREET];
    }

    /**
     * @return string
     */
    public function getStreetNumber(): string
    {
        return $this->parser->getAddress()[ParserAnaf::STREET_NUMBER];
    }

    /**
     * @return string
     */
    public function getOthers(): string
    {
        return $this->parser->getAddress()['others'];
    }
}