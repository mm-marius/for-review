<?php

namespace App\Services\Helpers;

class SettingHelper
{
    const MID_RESPONSIVE = 'col-md-12 col-lg-6';
    const FULL_RESPONSIVE = 'col-md-12 col-lg-12';
    const DEFAULT_VIP_HOST = 'http://192.168.0.104:8080';

    public $settingList;
    public function __construct()
    {
        // $this->settingList = array_merge(
        //     $this->general(),
        //     $this->search(),
        //     $this->captcha(),
        //     $this->theme(),
        //     $this->paynode(),
        //     $this->vip(),
        //     $this->seo(),
        //     $this->packageDetails(),
        //     $this->appointments());
    }

    // private function packageDetails()
    // {
    //     $groupPickupPoints = new Setting(Settings::NAME_PK_GROUP_PICKUP_POINTS, Settings::TYPE_BOOLEAN, true, false, Settings::CATEGORY_PACKAGE_DETAILS, 68, self::MID_RESPONSIVE);
    //     $groupPickupPoints->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS]]);

    //     $realTimeCalculate = new Setting(Settings::NAME_PK_REAL_TIME_CALCULATE, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PACKAGE_DETAILS, 67, self::MID_RESPONSIVE);
    //     $realTimeCalculate->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS]]);

    //     $removeImage = new Setting(Settings::NAME_PK_NO_IMAGE, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PACKAGE_DETAILS, 94, self::MID_RESPONSIVE);
    //     $removeImage->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS]]);

    //     $hideShowcase = new Setting(Settings::NAME_HIDE_DATE_SHOWCASE, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PACKAGE_DETAILS, 95, self::MID_RESPONSIVE);
    //     $hideShowcase->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SHOWCASE]]);

    //     $showDateRange = new Setting(Settings::NAME_SHOW_DATE_RANGE, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PACKAGE_DETAILS, 96, self::MID_RESPONSIVE);
    //     $showDateRange->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SHOWCASE]]);

    //     return [
    //         $groupPickupPoints, $realTimeCalculate, $removeImage, $hideShowcase, $showDateRange,
    //     ];
    // }

    // private function vip()
    // {
    //     $host = new Setting(Settings::NAME_VIP_HOST, Settings::TYPE_STRING, self::DEFAULT_VIP_HOST, false, Settings::CATEGORY_VIP, 86, self::MID_RESPONSIVE);
    //     $host->addRole(Settings::ROLE_SUPERADMIN);

    //     $society = new Setting(Settings::NAME_VIP_SOCIETY, Settings::TYPE_STRING, '', false, Settings::CATEGORY_VIP, 85, self::MID_RESPONSIVE);
    //     $society->addRole(Settings::ROLE_SUPERADMIN);

    //     $opUnit = new Setting(Settings::NAME_VIP_OP_UNIT, Settings::TYPE_STRING, '', true, Settings::CATEGORY_VIP, 84, self::MID_RESPONSIVE);
    //     $opUnit->addRole(Settings::ROLE_SUPERADMIN);

    //     $vipUser = new Setting(Settings::NAME_VIP_USER, Settings::TYPE_STRING, '', false, Settings::CATEGORY_VIP, 83, self::MID_RESPONSIVE);
    //     $vipUser->addRole(Settings::ROLE_SUPERADMIN);
    //     $vipPassword = new Setting(Settings::NAME_VIP_PASSWORD, Settings::TYPE_STRING, '', false, Settings::CATEGORY_VIP, 82, self::MID_RESPONSIVE);
    //     $vipPassword->addRole(Settings::ROLE_SUPERADMIN);

    //     $defaultClient = new Setting(Settings::NAME_VIP_DEFAULT_CLIENT, Settings::TYPE_STRING, '', true, Settings::CATEGORY_VIP, 81, self::MID_RESPONSIVE);
    //     $defaultClient->setControls([
    //         Settings::CONTROL_STRING_MAX_LENGTH => 12,
    //     ]);

    //     $generatedDoc = new Setting(Settings::NAME_GENERATE_DOCUMENT, Settings::TYPE_STRING, GenerateDocumentVIP::DOCUMENT_VOUCHER, false, Settings::CATEGORY_VIP, 92, self::MID_RESPONSIVE);
    //     $generatedDoc->addRole(Settings::ROLE_SUPERADMIN);
    //     $generatedDoc->setControls([
    //         Settings::CONTROL_AVAILABLE_VALUES => [
    //             'nessuno' => '',
    //             'voucher' => GenerateDocumentVIP::DOCUMENT_VOUCHER,
    //             'estrattoConto' => GenerateDocumentVIP::DOCUMENT_ESTRATTO_CONTO,
    //             'confermaFornitore' => GenerateDocumentVIP::DOCUMENT_CONFERMA_FORNITORE,
    //         ]]);

    //     $opunitRules = new Setting(Settings::NAME_VIP_OPUNIT_RULES, Settings::TYPE_RULES_OPUNIT, json_encode(new OpUnitRules()), false, Settings::CATEGORY_VIP, 94, self::FULL_RESPONSIVE);
    //     $opunitRules->addRole(Settings::ROLE_SUPERADMIN);
    //     return [
    //         $host,
    //         $society,
    //         $opUnit,
    //         $vipUser,
    //         $vipPassword,
    //         $defaultClient,
    //         $generatedDoc,
    //         $opunitRules,
    //     ];
    // }
    // private function seo()
    // {
    //     $seoGoogleDomain = new Setting(Settings::NAME_SEO_GOOGLE_DOMAIN, Settings::TYPE_STRING, '', false, Settings::CATEGORY_SEO, 119, self::MID_RESPONSIVE);
    //     $seoGoogleDomain->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SEO]]);
    //     $seoDetailed = new Setting(Settings::NAME_SEO_DETAILED, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_SEO, 120, self::MID_RESPONSIVE);
    //     $seoDetailed->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SEO]]);

    //     $seoPluginGenerateSitemapOnly = new Setting(Settings::NAME_SEO_PLUGIN_GENERATE_SITEMAP_ONLY, Settings::TYPE_BOOLEAN, true, false, Settings::CATEGORY_SEO, 121, self::MID_RESPONSIVE);
    //     $seoPluginGenerateSitemapOnly->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SEO]]);
    //     $seoPluginGenerateSitemapOnly->addRole(Settings::ROLE_PLUGIN);

    //     $seoPluginGenerate = new Setting(Settings::NAME_SEO_PLUGIN_GENERATE, Settings::TYPE_BUTTON, 'wp-json/websales/v1/generateseo', false, Settings::CATEGORY_SEO, 122, self::MID_RESPONSIVE);
    //     $seoPluginGenerate->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_SEO]]);
    //     $seoPluginGenerate->addRole(Settings::ROLE_PLUGIN);
    //     return [$seoGoogleDomain, $seoDetailed, $seoPluginGenerateSitemapOnly, $seoPluginGenerate];
    // }

    // private function paynode()
    // {

    //     $userReference = new Setting(Settings::NAME_PAYNODE_USER_REFERENCE, Settings::TYPE_STRING, '000-000', true, Settings::CATEGORY_PAYNODE, 88, self::MID_RESPONSIVE);
    //     $userReference->addRole(Settings::ROLE_SUPERADMIN);

    //     $pluginReference = new Setting(Settings::NAME_PAYNODE_PLUGIN_REFERENCE, Settings::TYPE_STRING, '000-000', true, Settings::CATEGORY_PAYNODE, 87, self::MID_RESPONSIVE);
    //     $pluginReference->addRole(Settings::ROLE_SUPERADMIN);
    //     $pluginReference->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PLUGIN]]);

    //     $isDev = new Setting(Settings::NAME_PAYNODE_DEV, Settings::TYPE_BOOLEAN, true, true, Settings::CATEGORY_PAYNODE, 89, self::FULL_RESPONSIVE);
    //     $isDev->addRole(Settings::ROLE_SUPERADMIN);

    //     $deposit = new Setting(Settings::NAME_PAYNODE_DEPOSIT, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PAYNODE, 90, self::MID_RESPONSIVE);
    //     $depositToOK = new Setting(Settings::NAME_PAYNODE_DEPOSIT_TO_OK, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PAYNODE, 91, self::MID_RESPONSIVE);
    //     $depositOnly = new Setting(Settings::NAME_PAYNODE_DEPOSIT_ONLY, Settings::TYPE_BOOLEAN, false, false, Settings::CATEGORY_PAYNODE, 92, self::MID_RESPONSIVE);
    //     $depositDayBefore = new Setting(Settings::NAME_PAYNODE_DEPOSIT_DAY_BEFORE, Settings::TYPE_INTEGER, 7, false, Settings::CATEGORY_PAYNODE, 93, self::MID_RESPONSIVE);
    //     $depositRules = new Setting(Settings::NAME_PAYNODE_DEPOSIT_RULES, Settings::TYPE_RULES_DEPOSIT, json_encode(new DepositRules()), false, Settings::CATEGORY_PAYNODE, 94, self::FULL_RESPONSIVE);

    //     return [
    //         $isDev,
    //         $userReference,
    //         $pluginReference,
    //         $deposit,
    //         $depositToOK,
    //         $depositOnly,
    //         $depositDayBefore,
    //         $depositRules,
    //     ];
    // }

    // private function captcha()
    // {
    //     return [
    //         new Setting(Settings::NAME_CAPTCHA_SITE_KEY, Settings::TYPE_STRING, '', false, Settings::CATEGORY_CAPTCHA, 97, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_CAPTCHA_SECRET_KEY, Settings::TYPE_STRING, '', false, Settings::CATEGORY_CAPTCHA, 96, self::MID_RESPONSIVE),
    //     ];
    // }

    // private function search()
    // {

    //     $searchDistance = new Setting(Settings::NAME_HOTEL_SEARCH_DISTANCE, Settings::TYPE_INTEGER, 60000, false, Settings::CATEGORY_SEARCH, 98, self::MID_RESPONSIVE);
    //     $searchDistance->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_LISTINI_HOTEL]]);

    //     $initialHotelAdults = new Setting(Settings::NAME_MIN_ADULT, Settings::TYPE_INTEGER, 2, true, Settings::CATEGORY_SEARCH, 93, self::MID_RESPONSIVE);
    //     $initialHotelAdults->setControls([
    //         Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS, SystemController::MODULE_LISTINI_HOTEL],
    //         Settings::CONTROL_MOD_DEPENDENCY_BEHAVIOR => Settings::DEPENDENCY_BEHAVIOR_OR,
    //     ]);
    //     $initialPackageAdults = new Setting(Settings::NAME_MIN_PACKAGE_ADULT, Settings::TYPE_INTEGER, 2, true, Settings::CATEGORY_SEARCH, 93, self::MID_RESPONSIVE);
    //     $initialPackageAdults->setControls([
    //         Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS],
    //     ]);

    //     $showFirstFeature = new Setting(Settings::NAME_PK_SHOW_FIRST_FEATURE, Settings::TYPE_BOOLEAN, 0, true, Settings::CATEGORY_SEARCH, 78, self::MID_RESPONSIVE);
    //     $showFirstFeature->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS]]);

    //     $publishedHotelsOnly = new Setting(Settings::NAME_HOTEL_PUBLISHED_ONLY, Settings::TYPE_BOOLEAN, true, false, Settings::CATEGORY_SEARCH, 77, self::MID_RESPONSIVE);
    //     $publishedHotelsOnly->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_LISTINI_HOTEL]]);

    //     $cacheMonths = new Setting(Settings::NAME_CACHE_MONTHS, Settings::TYPE_INTEGER, 18, false, Settings::CATEGORY_SEARCH, 103, self::MID_RESPONSIVE);
    //     $cacheMonths->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS]]);

    //     return [
    //         new Setting(Settings::NAME_DEFAULT_SEARCH_DAYS, Settings::TYPE_INTEGER, 7, false, Settings::CATEGORY_SEARCH, 97, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_MAX_ROOMS, Settings::TYPE_INTEGER, 0, true, Settings::CATEGORY_SEARCH, 100, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_ALL_DESTINATIONS, Settings::TYPE_BOOLEAN, 1, false, Settings::CATEGORY_SEARCH, 99, self::MID_RESPONSIVE),
    //         new Setting(Settings::STORE_DESTINATION_SESSION, Settings::TYPE_BOOLEAN, 1, false, Settings::CATEGORY_SEARCH, 101, self::MID_RESPONSIVE),
    //         $searchDistance,
    //         $initialHotelAdults,
    //         $initialPackageAdults,
    //         $showFirstFeature,
    //         $publishedHotelsOnly,
    //         $cacheMonths,
    //     ];
    // }

    // private function general()
    // {
    //     $splitCountryCalls = new Setting(Settings::NAME_PK_SPLIT_COUNTRY_CALLS, Settings::TYPE_INTEGER, 30, true, Settings::CATEGORY_GENERAL, 95, self::MID_RESPONSIVE);
    //     $splitCountryCalls->addRole(Settings::ROLE_SUPERADMIN);
    //     $splitCountryCalls->setControls([
    //         Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PACKETS],
    //         Settings::CONTROL_INTEGER_MIN => 1,
    //         Settings::CONTROL_INTEGER_MAX => 540,
    //     ]);

    //     $pluginKey = new Setting(Settings::NAME_PLUGIN_KEY, Settings::TYPE_STRING, str_replace('-', '', GenericFunctions::GUID()), false, Settings::CATEGORY_GENERAL, 92, self::MID_RESPONSIVE);
    //     $pluginKey->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PLUGIN]]);
    //     $pluginKey->addRole(Settings::ROLE_SUPERADMIN);

    //     $authSearch = new Setting(Settings::NAME_AUTH_SEARCH_RESULT, Settings::TYPE_BOOLEAN, 0, false, Settings::CATEGORY_GENERAL, 91, self::MID_RESPONSIVE);
    //     $authSearch->addRole(Settings::ROLE_SUPERADMIN);
    //     $authSearch->setControls([
    //         Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PLUGIN],
    //     ]);

    //     $b2bOnly = new Setting(Settings::NAME_B2B_ONLY, Settings::TYPE_BOOLEAN, 0, false, Settings::CATEGORY_GENERAL, 91, self::MID_RESPONSIVE);
    //     $b2bOnly->addRole(Settings::ROLE_SUPERADMIN);

    //     $b2cOnly = new Setting(Settings::NAME_B2C_ONLY, Settings::TYPE_BOOLEAN, 0, false, Settings::CATEGORY_GENERAL, 91, self::MID_RESPONSIVE);
    //     $b2cOnly->addRole(Settings::ROLE_SUPERADMIN);
    //     $enableSelling = new Setting(Settings::NAME_SELLING, Settings::TYPE_BOOLEAN, 0, false, Settings::CATEGORY_GENERAL, 91, self::MID_RESPONSIVE);
    //     $enableSelling->addRole(Settings::ROLE_SUPERADMIN);
    //     $languages = new Setting(Settings::NAME_ENABLED_LANGUAGES, Settings::TYPE_STRING, 'it', false, Settings::CATEGORY_GENERAL, 92, self::MID_RESPONSIVE);
    //     $languages->addRole(Settings::ROLE_SUPERADMIN);

    //     $explodedGuid = explode('-', GenericFunctions::GUID());
    //     $apiSalt = new Setting(Settings::NAME_API_SALT, Settings::TYPE_STRING, strtolower($explodedGuid[0] . $explodedGuid[1]), false, Settings::CATEGORY_GENERAL, 90, self::MID_RESPONSIVE);
    //     $apiSalt->addRole(Settings::ROLE_SUPERADMIN);

    //     $enabledModules = new Setting(Settings::NAME_ENABLED_MODULES, Settings::TYPE_MULTICHOICE_SELECT,
    //         SystemController::MODULE_LISTINI_HOTEL . ',' .
    //         SystemController::MODULE_PACKETS . ',' .
    //         SystemController::MODULE_SHOWCASE . ',' .
    //         SystemController::MODULE_PLUGIN, true, Settings::CATEGORY_GENERAL, 80);
    //     $enabledModules->addRole(Settings::ROLE_SUPERADMIN);
    //     $enabledModules->setControls([
    //         Settings::CONTROL_AVAILABLE_VALUES => [
    //             'hotelList' => SystemController::MODULE_LISTINI_HOTEL,
    //             'transferList' => SystemController::MODULE_LISTINI_TRANSFER,
    //             'eventList' => SystemController::MODULE_LISTINI_EVENT,
    //             'holidayPackages' => SystemController::MODULE_PACKETS,
    //             'plugin' => SystemController::MODULE_PLUGIN,
    //             'showcase' => SystemController::MODULE_SHOWCASE,
    //             'quotation' => SystemController::MODULE_QUOTATIONS,
    //             'home' => SystemController::MODULE_HOME,
    //             'Appointment' => SystemController::MODULE_APPOINTMENT,
    //             'SEO' => SystemController::MODULE_SEO,
    //             'Bus' => SystemController::MODULE_BUS,
    //             'santa' => SystemController::MODULE_SANTA,
    //             'spooky' => SystemController::MODULE_SPOOKY,
    //         ],
    //     ]);

    //     $cacheUpdateTIme = new Setting(Settings::NAME_CACHE_UPDATE_TIME, Settings::TYPE_INTEGER, 1, false, Settings::CATEGORY_GENERAL, 69, self::MID_RESPONSIVE);
    //     $cacheUpdateTIme->addRole(Settings::ROLE_SUPERADMIN);

    //     $errorChannel = new Setting(Settings::NAME_ERROR_CHANNEL, Settings::TYPE_STRING, 'logwebsale', false, Settings::CATEGORY_GENERAL, 66, self::MID_RESPONSIVE);
    //     $errorChannel->addRole(Settings::ROLE_SUPERADMIN);

    //     return [
    //         new Setting(Settings::NAME_COPYRIGHT, Settings::TYPE_STRING, (new \DateTime())->format('Y'), false, Settings::CATEGORY_GENERAL, 98),
    //         new Setting(Settings::NAME_SINGLE_PRODUCT, Settings::TYPE_BOOLEAN, 0, false, Settings::CATEGORY_GENERAL, 92, self::MID_RESPONSIVE),
    //         $splitCountryCalls,
    //         $pluginKey,
    //         $b2bOnly,
    //         $b2cOnly,
    //         $enableSelling,
    //         $authSearch,
    //         $languages,
    //         $apiSalt,
    //         $enabledModules,
    //         new Setting(Settings::NAME_API_GOOGLE_MAPS, Settings::TYPE_STRING, '', false, Settings::CATEGORY_GENERAL, 70, self::MID_RESPONSIVE),
    //         $cacheUpdateTIme,
    //         $errorChannel,
    //         new Setting(Settings::NAME_EMAIL_TO, Settings::TYPE_EMAIL, '', false, Settings::CATEGORY_GENERAL, 63, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_EMAIL_REGISTRATION_TO, Settings::TYPE_EMAIL, '', false, Settings::CATEGORY_GENERAL, 63, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_EMAIL_PAYMENT_TO, Settings::TYPE_EMAIL, '', false, Settings::CATEGORY_GENERAL, 63, self::MID_RESPONSIVE),
    //         new Setting(Settings::NAME_DOSSIER_EXPIRE_TIME, Settings::TYPE_INTEGER, 1, false, Settings::CATEGORY_GENERAL, 65, self::MID_RESPONSIVE),
    //     ];
    // }

    // private function theme()
    // {
    //     $pluginStyle = new Setting(Settings::NAME_PLUGIN_STYLE, Settings::TYPE_SELECT, 'style2', '', Settings::CATEGORY_THEME, 99, self::MID_RESPONSIVE);
    //     $pluginStyle->setControls([
    //         Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_PLUGIN],
    //     ]);
    //     return [
    //         new Setting(Settings::NAME_THEME, Settings::TYPE_SELECT, 'client_' . $_ENV['CLIENT_CODE'], '', Settings::CATEGORY_THEME, 99, self::MID_RESPONSIVE),
    //         $pluginStyle,
    //     ];
    // }
    // private function appointments()
    // {
    //     $link = new Setting(Settings::NAME_APPOINTMENT_LINK, Settings::TYPE_STRING, '', false, Settings::CATEGORY_APPOINTMENT, 92, self::MID_RESPONSIVE);
    //     $link->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_APPOINTMENT]]);
    //     $hubspotPortal = new Setting(Settings::NAME_HUBSPOT_PORTAL, Settings::TYPE_STRING, '', false, Settings::CATEGORY_APPOINTMENT, 92, self::MID_RESPONSIVE);
    //     $hubspotPortal->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_APPOINTMENT]]);
    //     $hubspotFormId = new Setting(Settings::NAME_HUBSPOT_FORM_ID, Settings::TYPE_STRING, '', false, Settings::CATEGORY_APPOINTMENT, 92, self::MID_RESPONSIVE);
    //     $hubspotFormId->setControls([Settings::CONTROL_MOD_DEPENDENCY => [SystemController::MODULE_APPOINTMENT]]);
    //     return [
    //         $link,
    //         $hubspotPortal,
    //         $hubspotFormId,
    //     ];
    // }
    public static function checkControls($controls, $value, $type)
    {
        $ret = true;
        switch ($type) {
            case Settings::TYPE_INTEGER:
                isset($controls[Settings::CONTROL_INTEGER_MIN]) && $ret = $ret && $value >= $controls[Settings::CONTROL_INTEGER_MIN];
                isset($controls[Settings::CONTROL_INTEGER_MAX]) && $ret = $ret && $value <= $controls[Settings::CONTROL_INTEGER_MAX];
                break;
            case $type == Settings::TYPE_MULTICHOICE_SELECT:
                $splitVal = explode(',', $value);
                $ret = '';
                if (empty($value)) {
                    break;
                }
                foreach ($splitVal as $insertedValue) {
                    in_array($insertedValue, $controls[Settings::CONTROL_AVAILABLE_VALUES]) && $ret .= $insertedValue . ',';
                }
                strlen($ret) && $ret = rtrim($ret, ',');
                break;
            default:
                empty($controls[Settings::CONTROL_AVAILABLE_VALUES]) || $ret = $ret && in_array($value, $controls[Settings::CONTROL_AVAILABLE_VALUES]);
                break;
        }
        return $ret;
    }
}
