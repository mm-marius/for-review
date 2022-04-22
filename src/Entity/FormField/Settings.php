<?php

namespace App\Entity\FormField;

use App\Services\Helpers\SettingHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 */
class Settings extends FormField
{
    const NAME_COPYRIGHT = 'copyright';
    const NAME_THEME = 'theme';
    const NAME_PLUGIN_STYLE = 'pluginStyle';

    const NAME_API_GOOGLE_MAPS = 'general.googleMaps';
    const NAME_CAPTCHA_SECRET_KEY = 'captcha.secretKey';
    const NAME_CAPTCHA_SITE_KEY = 'captcha.siteKey';

    const NAME_CACHE_UPDATE_TIME = 'general.cacheUpdateTime';
    const NAME_API_SALT = 'general.apiSalt';
    const NAME_ENABLED_MODULES = 'general.enabledModules';
    const NAME_ERROR_CHANNEL = 'general.errorChannel';
    const NAME_PLUGIN_KEY = 'general.pluginKey';
    const NAME_SINGLE_PRODUCT = 'general.singleProduct';
    const NAME_PK_SPLIT_COUNTRY_CALLS = 'general.splitCountryCalls';
    const NAME_ENABLED_LANGUAGES = 'general.languages';
    const NAME_AUTH_SEARCH_RESULT = 'general.authSearch';
    const NAME_B2B_ONLY = 'general.b2bonly';
    const NAME_B2C_ONLY = 'general.b2conly';
    const NAME_SELLING = 'general.selling';
    const NAME_EMAIL_TO = 'general.emailTo';
    const NAME_EMAIL_REGISTRATION_TO = 'general.emailRegistrationTo';
    const NAME_EMAIL_PAYMENT_TO = 'general.emailPaymentTo';
    const NAME_DOSSIER_EXPIRE_TIME = 'general.dossierExpireTime';
    const NAME_PAX_FIELDS = 'general.paxFields';

    const NAME_APPOINTMENT_LINK = 'appointment.link';
    const NAME_HUBSPOT_PORTAL = 'appointment.hubspotPortal';
    const NAME_HUBSPOT_FORM_ID = 'appointment.hubspotFormID';

    const NAME_PK_NO_IMAGE = 'search.packageNoImage';
    const NAME_PK_GROUP_PICKUP_POINTS = 'packageDetails.groupPickupPoints';
    const NAME_PK_REAL_TIME_CALCULATE = 'packageDetails.realTimeCalculate';
    const NAME_HIDE_DATE_SHOWCASE = 'packageDetails.hideDateShowcase';
    const NAME_SHOW_DATE_RANGE = 'packageDetails.showDateRange';

    const NAME_PAYNODE_DEPOSIT = 'paynode.deposit';
    const NAME_PAYNODE_DEPOSIT_TO_OK = 'paynode.depositToOk';
    const NAME_PAYNODE_DEPOSIT_ONLY = 'paynode.depositOnly';
    const NAME_PAYNODE_DEPOSIT_DAY_BEFORE = 'paynode.depositDayBefore';
    const NAME_PAYNODE_DEPOSIT_RULES = 'paynode.depositRules';
    const NAME_PAYNODE_DEV = 'paynode.isDev';
    const NAME_PAYNODE_USER_REFERENCE = 'paynode.userReference';
    const NAME_PAYNODE_PLUGIN_REFERENCE = 'paynode.pluginReference';

    const NAME_ALL_DESTINATIONS = 'search.allDestinations';
    const NAME_HOTEL_SEARCH_DISTANCE = 'search.distance';
    const NAME_HOTEL_PUBLISHED_ONLY = 'search.publishedOnly';
    const NAME_MIN_ADULT = 'search.initialAdults';
    const NAME_MIN_PACKAGE_ADULT = 'search.initialPackageAdults';
    const NAME_MAX_ROOMS = 'search.maxRooms';
    const NAME_DEFAULT_SEARCH_DAYS = 'search.defaultDays';
    const NAME_PK_SHOW_FIRST_FEATURE = 'search.showFirstFeature';
    const NAME_CACHE_MONTHS = 'search.cacheMonths';

    const NAME_SEO_DETAILED = 'seo.detailed';
    const NAME_SEO_GOOGLE_DOMAIN = 'seo.googleDomain';
    const NAME_SEO_PLUGIN_GENERATE_SITEMAP_ONLY = 'seo.pluginGenerateSitemapOnly';
    const NAME_SEO_PLUGIN_GENERATE = 'seo.pluginGenerate';

    const NAME_VIP_DEFAULT_CLIENT = 'vip.defaultClient';
    const NAME_VIP_HOST = 'vip.host';
    const NAME_VIP_PASSWORD = 'vip.password';
    const NAME_VIP_USER = 'vip.user';

    const TYPE_RULES_DEPOSIT = 'rules_deposit';
    const TYPE_RULES_OPUNIT = 'rules_opunit';

    const CATEGORY_CAPTCHA = 'captcha';
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_PACKAGE_DETAILS = 'packageDetails';
    const CATEGORY_PAYNODE = 'paynode';
    const CATEGORY_SEARCH = 'search';
    const CATEGORY_THEME = 'theme';
    const CATEGORY_VIP = 'vip';
    const CATEGORY_APPOINTMENT = 'appointment';
    const CATEGORY_SEO = 'seo';

    const CATEGORIES = [
        self::CATEGORY_GENERAL,
        self::CATEGORY_SEARCH,
        self::CATEGORY_THEME,
        self::CATEGORY_CAPTCHA,
        self::CATEGORY_PACKAGE_DETAILS,
        self::CATEGORY_PAYNODE,
        self::CATEGORY_VIP,
        self::CATEGORY_APPOINTMENT,
        self::CATEGORY_SEO,
    ];

    const ROLE_PLUGIN = 'ROLE_PLUGIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    const STORE_DESTINATION_SESSION = 'search.storeDestinationSession';

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string", length=20 ,nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="json")
     */
    private $value;

    /*----- METHODS -----*/
    public function __construct(string $name, string $type, $value, bool $hasDescription = false, string $category = self::CATEGORY_GENERAL, int $weight = 100, ?string $responsive = null)
    {
        $this->init($name, $type, $hasDescription, $responsive ?: '', $weight);
        $this->value = $value;
        $this->category = $category;
        $this->roles = [];
    }

    public function update(Settings $setting)
    {
        $this->value = $setting->getValue();
    }

    /*-------- GETTER & SETTER ---------*/

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getValue()
    {
        $ret = $this->value;
        switch ($this->type) {
            case self::TYPE_BOOLEAN:
                $ret = (bool) $ret;
                break;
            case self::TYPE_INTEGER:
                $ret = intval($ret);
                break;
            default:
                break;
        }
        return $ret;
    }

    public function setValue(string $value): self
    { //TODO set value to string from type
        if (SettingHelper::checkControls($this->controls, $value, $this->type)) {
            $this->value = $value;
        }
        if ($this->type == self::TYPE_MULTICHOICE_SELECT) {
            $this->value = SettingHelper::checkControls($this->controls, $value, $this->type);
        }
        return $this;
    }

}