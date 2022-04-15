<?php
namespace App\Handlers\VIP\Models;

use App\Entity\User;

class UserAccountingVIP
{
    public $partitaIva;
    public $codiceFiscale;
    public $persFisicaGiur;

    public function __construct(User $user)
    {
        $this->partitaIva = $user->getVatCode();
        $taxCode = $user->getTaxCode();
        $this->codiceFiscale = $taxCode;
        $this->persFisicaGiur = preg_match("/[a-z]/i", $taxCode) ? 'F' : ($user->isBusiness() ? 'G' : 'F');
    }
}