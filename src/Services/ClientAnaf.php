<?php
namespace App\Services;

use App\Models\Anaf\Company;
use App\Services\Exceptions\LimitExceeded;
use App\Services\Exceptions\RequestFailed;
use App\Services\Exceptions\ResponseFailed;

/**
 * Implementare API ANAF V6
 * https://webservicesp.anaf.ro/PlatitorTvaRest/api/v6/
 * @package App\Services
 */
class ClientAnaf
{
    /** @var array CIFs List */
    protected $cifs = [];

    /**
     * Add one or more cifs
     * @param string|array $cifs
     * @param string|null $date
     * @return $this
     */
    public function addCif($cifs, string $date = null): ClientAnaf
    {
        // If not have set date return today
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        // Convert to array
        if (!is_array($cifs)) {
            $cifs = [$cifs];
        }

        foreach ($cifs as $cif) {
            // Keep only numbers from CIF
            $cif = preg_replace('/\D/', '', $cif);

            // Add cif to list
            $this->cifs[] = [
                "cui" => $cif,
                "data" => $date,
            ];
        }

        return $this;
    }

    /**
     * @return Company[]
     * @throws LimitExceeded
     * @throws RequestFailed
     * @throws ResponseFailed
     */
    public function get(): array
    {
        $companies = [];
        $results = HttpCall::call($this->cifs);
        foreach ($results as $result) {
            $companies[] = new Company(new ParserAnaf($result));
        }
        return $companies;
    }

    /**
     * @return Company
     * @throws LimitExceeded
     * @throws RequestFailed
     * @throws ResponseFailed
     */
    public function first(): Company
    {
        $results = HttpCall::call($this->cifs);
        return new Company(new ParserAnaf($results[0]));
    }
}