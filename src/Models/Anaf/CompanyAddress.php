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
    public function getBloc(): string
    {
        return $this->parser->getAddress()[ParserAnaf::BLOC];
    }

    /**
     * @return string
     */
    public function getScara(): string
    {
        return $this->parser->getAddress()[ParserAnaf::SCARA];
    }

    /**
     * @return string
     */
    public function getEtaj(): string
    {
        return $this->parser->getAddress()[ParserAnaf::ETAJ];
    }

    /**
     * @return string
     */
    public function getApart(): string
    {
        return $this->parser->getAddress()[ParserAnaf::APART];
    }

    /**
     * @return string
     */
    public function getCam(): string
    {
        return $this->parser->getAddress()[ParserAnaf::CAM];
    }

    /**
     * @return string
     */
    public function getSect(): string
    {
        return $this->parser->getAddress()[ParserAnaf::SECT];
    }
}