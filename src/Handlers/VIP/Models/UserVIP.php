<?php
namespace App\Handlers\VIP\Models;

use App\Entity\User;
use App\Services\SettingService;

class UserVIP
{
    const ACTION_INSERIMENTO_PURO = 'IP';
    const ACTION_VARIAZIONE_INSERIMENTO = 'VI';
    const ACTION_VARIZAIONE_PURO = 'VP';
    const ACTION_CONSULTAZIONE = 'CO';
    public $userWs;
    public $azione;
    public $provenienza;
    public $structAnaCliente;

    public function __construct($settings, User $user, SettingService $settingService, $action = self::ACTION_VARIZAIONE_PURO)
    {
        $this->userWs = $settings->auth;
        $this->azione = $action;
        $this->structAnaCliente = new UserStructureVIP($user, $settingService, $action);
        $this->provenienza = 'ATT';
    }
}