<?php
namespace App\Handlers\VIP\Models;

use App\Entity\User;
use App\Services\SettingService;

class UserStructureVIP
{
    public $datiGeneraliCliente;
    public $datiContabiliCliente;
    public $datiAggiuntiviCliente;
    public function __construct(User $user, SettingService $settings, $action = null)
    {
        $this->datiGeneraliCliente = new DatiGeneraliVIP($user, $settings, $action);
        $this->datiContabiliCliente = new UserAccountingVIP($user, $settings);
    }

}
class DatiGeneraliVIP
{
    public $codCliente12;
    public $cognome;
    public $nome;
    public $ragioneSociale;
    //public $ragioneSocInRichiesta;
    public $indirizzo;
    public $localita;
    public $provincia;
    public $cap;
    //public $prefissoInternaz;
    public $numeroTel1;
    public $numeroTel2;
    //public $numeroFax;
    public $codiceNazione;
    public $codNazCodFiscale;
    public $email;
    //public $annullamento;
    public $sesso;
    public $tipologiaCliente;
    public $dataNascita;
    public $locNascita;
    //public $provNascita;

    public function __construct(User $user, SettingService $settings, $action = null)
    {
        $this->codCliente12 = $action == UserVIP::ACTION_VARIZAIONE_PURO || $action == UserVIP::ACTION_VARIAZIONE_INSERIMENTO ? $settings->getSessionCode() /*$user->getClientCode($settings)*/ : '';

        $settings->getSetting(Settings::NAME_VIP_DEFAULT_CLIENT) == $this->codCliente12 && $this->codCliente12 = null;

        $this->cognome = $user->getLastName();
        $this->nome = $user->getFirstName();
        $this->ragioneSociale = $user->getBusinessName();
        //$this->ragioneSocInRichiesta = '';
        $this->indirizzo = $user->getAddress();
        $this->localita = $user->getCity();
        $this->provincia = $user->getProvince();
        $this->cap = $user->getZipCode();
        //$this->prefissoInternaz = "0039"; //needed?
        $this->numeroTel1 = $user->getPhone();
        $this->numeroTel2 = $user->getMobilePhone();
        // $this->numeroFax = ''; //needed?
        $this->codiceNazione = $user->getNationCode();
        $this->codNazCodFiscale = $user->getNationCode(); //why?
        $this->email = $user->getEmail();
        //$this->annullamento = ''; //?
        $this->sesso = $user->getSex();
        $this->tipologiaCliente = $user->getIsAgency() ? 'A' : (empty(trim($user->getBusinessName())) ? 'P' : 'D'); //P = privato, D = ditta, A = agenzia
        $this->dataNascita = $user->getBirthDate() ? $user->getBirthDate()->format('d/m/Y') : '';
        $this->locNascita = $user->getBirthPlace();
        //$this->provNascita = ''; //e.g. CN //needed?

    }
}
