<?php
namespace App\Services;

class ParserAnaf
{
    const COUNTY = 'county';
    const CITY = 'city';
    const STREET = 'street';
    const STREET_NUMBER = 'streetNumber';
    const BLOC = 'bloc';
    const SCARA = 'scara';
    const ETAJ = 'etaj';
    const APART = 'apart';
    const CAM = 'cam';
    const SECT = 'sect';
    const COMUNA = 'comuna';
    const SAT = 'sat';
    const OTHER = 'other';

    /** @var array */
    private $data = [];

    /**
     * Parser constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getAddress(): array
    {
        $address = [];
        $rawText = mb_convert_case($this->data['adresa'], MB_CASE_TITLE, 'UTF-8');
        $list = array_map('trim', explode(",", $rawText, 11));

        foreach ($list as $value) {
            $value = trim($value);

            if (stripos($value, 'Jud.') !== false) {
                $address[self::COUNTY] = trim(str_ireplace('Jud.', '', $value));
            }
            if (stripos($value, 'Mun.') !== false || stripos($value, 'Orş.') !== false || stripos($value, 'Municipiul') !== false) {
                $address[self::CITY] = trim(str_ireplace(['Mun.', 'Orş.', 'Municipiul'], '', $value));
            }
            if (stripos($value, 'Com.') !== false) {
                $address[self::COMUNA] = trim(str_ireplace("Com.", '', $value));
            }
            if (stripos($value, 'Sat') !== false) {
                $address[self::SAT] = trim(str_ireplace("Sat", '', $value));
            }

            if (stripos($value, 'Sos.') !== false || stripos($value, 'Bld') !== false || stripos($value, 'B-dul') !== false || stripos($value, 'Cal.') !== false || stripos($value, 'Calea') !== false || stripos($value, 'Str.') !== false) {
                $address[self::STREET] = trim(str_ireplace(['Sos.', 'Bld', 'B-dul', 'Cal.', 'Calea', 'Str.'], '', $value));
            }

            if (stripos($value, 'Nr.') !== false) {
                $address[self::STREET_NUMBER] = trim(str_ireplace('Nr.', '', $value));
            }

            if (stripos($value, 'Bl.') !== false) {
                $address[self::BLOC] = trim(str_ireplace('Bl.', '', $value));
            }

            if (stripos($value, 'Sc.') !== false) {
                $address[self::SCARA] = trim(str_ireplace('Sc.', '', $value));
            }

            if (stripos($value, 'Et.') !== false) {
                $address[self::ETAJ] = trim(str_ireplace('Et.', '', $value));
            }

            if (stripos($value, 'Ap.') !== false) {
                $address[self::APART] = trim(str_ireplace('Ap.', '', $value));
            }

            if (stripos($value, 'Cam.') !== false) {
                $address[self::CAM] = trim(str_ireplace('Cam.', '', $value));
            }

            if (stripos($value, 'Camera') !== false) {
                $address[self::CAM] = trim(str_ireplace('Camera', '', $value));
            }

            if (stripos($value, 'Sector') !== false) {
                $address[self::SECT] = trim(str_ireplace('SECTOR', '', $value));
            }

            if (stripos($value, 'DN') !== false) {
                $address[self::OTHER] .= $value . ',';
            }
            if (stripos($value, 'DJ') !== false) {
                $address[self::OTHER] .= $value . ',';
            }
            if (stripos($value, 'KM.') !== false) {
                $address[self::OTHER] .= $value . ',';
            }
        }
        return $address;
    }

    /**
     * @return false|string
     */
    public function getRegisterDate()
    {
        $rawDate = trim(str_replace('INREGISTRAT din data ', '', $this->data['stare_inregistrare']));
        return date("Y-m-d", strtotime($rawDate));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}