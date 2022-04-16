<?php

namespace App\Entity\Helpers;

use App\Entity\Email;
use App\Services\SettingService;

class EmailHelper
{
    public static function getData($doctrine, $translator, $type, $language, SettingService $settings, $attr)
    {
        $email = $doctrine->getRepository(Email::class)->findOneBy(['type' => $type]);
        return [
            'subject' => self::getSubject($doctrine, $translator, $type, $language, $settings, $attr, $email),
            'content' => self::getContent($doctrine, $translator, $type, $language, $settings, $attr, $email),
        ];
    }

    public static function getContent($doctrine, $translator, $type, $language, SettingService $settings, $attr, $email = null)
    {
        $email || $email = $doctrine->getRepository(Email::class)->findOneBy(['type' => $type]);
        $resultDescriptor = null;
        foreach ($email->getContents() as $content) {
            $content->getLanguage() == 'it' && $resultDescriptor = $content->getContent();
            if ($content->getLanguage() == $language) {
                $resultDescriptor = $content->getContent();
                break;
            }
        }
        if (!$resultDescriptor) {
            return '';
        }
        self::replaceImagePath($resultDescriptor);
        self::replacePlaceholders($resultDescriptor, Email::PLACEHOLDERS, $translator, $settings, $attr);
        return $resultDescriptor;
    }

    public static function getSubject($doctrine, $translator, $type, $language, SettingService $settings, $attr, $email = null)
    {
        $email || $email = $doctrine->getRepository(Email::class)->findOneBy(['type' => $type]);
        $resultDescriptor = null;
        foreach ($email->getSubjects() as $content) {
            $content->getLanguage() == 'it' && $resultDescriptor = $content->getContent();
            if ($content->getLanguage() == $language) {
                $resultDescriptor = $content->getContent();
                break;
            }
        }
        if (!$resultDescriptor) {
            return '';
        }
        self::replacePlaceholders($resultDescriptor, Email::PLACEHOLDERS_SUBJECT, $translator, $settings, $attr);
        $resultDescriptor = str_replace("&nbsp;", ' ', strip_tags($resultDescriptor));
        return $resultDescriptor;
    }
    private static function replaceImagePath(&$content)
    {
        $protocol = $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $fullUrl = $protocol . $_SERVER['HTTP_HOST'] . '/uploads';
        $content = str_replace('../uploads', $fullUrl, $content);
    }
    private static function replacePlaceholders(&$target, $placeholders, $translator, SettingService $settings, $attr)
    {
        $user = $attr['user'] ?? null;
        $order = $attr['order'] ?? null;
        $paySetting = $attr['paySetting'] ?? null;
        $cartDetails = $attr['cartDetails'] ?? null;
        $products = $order ? $order->getProducts() : null;
        $firstProduct = !empty($products) ? $products->first() : null;
        $productParameters = $firstProduct ? $firstProduct->getParameter() : null;
        $periodIN = $productParameters['checkIn'] ?? null;
        $periodOUT = $productParameters['checkOut'] ?? null;
        $productPax = $firstProduct ? $firstProduct->getPax() : null;
        $paxDetails = !empty($productPax) ? $productPax['paxDetails'] : null;
        $firstPaxKey = $paxDetails ? array_keys($paxDetails)[0] : null;
        $firstPax = $firstPaxKey ? $paxDetails[$firstPaxKey] : null;
        $totalPayed = $attr['totalPayed'] ?? null;

        foreach ($placeholders as $placeholder) {

            if ($placeholder == Email::PLACEHOLDER_USER_FORGOT_LINK) {
                $placeholder = '{{' . $placeholder . '}}';
                $forgotUrl = $attr['forgotUrl'] ?? null;
                $link = $forgotUrl ? '<p>' . $translator->trans('forgot.action.reset', [], 'email') .
                '<a href="' . $forgotUrl . '">' .
                $translator->trans('forgot.reset.link', [], 'email') . '</a></p>' : '';
                $target = str_replace($placeholder, $link, $target);
                continue;
            }

            if ($placeholder == Email::PLACEHOLDER_USER_ACTIVATION_LINK) {
                $placeholder = '{{' . $placeholder . '}}';
                $activationUrl = $attr['activationUrl'] ?? null;
                $link = $activationUrl ? '<p>' . $translator->trans('registration.action.activate', [], 'email') .
                '<a href="' . $activationUrl . '">' .
                $translator->trans('registration.activate.link', [], 'email') . '</a></p>' : '';
                $target = str_replace($placeholder, $link, $target);
                continue;
            }
            if ($placeholder == Email::PLACEHOLDER_TOTAL_PAYED) {
                //TODO format currency
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $totalPayed ?: '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }

            if ($placeholder == Email::PLACEHOLDER_ORDER_DETAIL) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $cartDetails ?: '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }

            if ($placeholder == Email::PLACEHOLDER_ORDER_DAYS) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $paySetting ? $paySetting->getOptionDay() : '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }
            if ($placeholder == Email::PLACEHOLDER_FIRST_PAX) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $firstPax ? $firstPax['firstName'] . ' ' . $firstPax['lastName'] : '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }
            if ($placeholder == Email::PLACEHOLDER_FIRST_PERIOD) {
                //TODO get first period checkin and checkout with dal al translation
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $periodIN && $periodOUT ?
                $translator->trans('from', [], 'monitor') . ' ' . $periodIN . ' ' .
                $translator->trans('to', [], 'monitor') . ' ' . $periodOUT : '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }
            if ($placeholder == Email::PLACEHOLDER_FIRST_PRODUCT) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $firstProduct ? $firstProduct->getDescription() : '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }

            if ($placeholder == Email::PLACEHOLDER_ORDER_DATE) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $order ? $order->getCreationDate()->format('d/m/Y H:i') : '';
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }
            if ($placeholder == Email::PLACEHOLDER_USER_CLIENT_CODE) {
                $placeholder = '{{' . $placeholder . '}}';
                $replaceWith = $settings->getSessionCode();
                $target = str_replace($placeholder, $replaceWith, $target);
                continue;
            }

            $dataReference = explode('.', $placeholder);
            $parameter = 'get' . ucfirst($dataReference[1]);
            $placeholder = '{{' . $placeholder . '}}';
            switch ($dataReference[0]) {
                case 'user':
                    $placeholder == '{{user.businessName}}' && error_log("user business emailHelper: " . $parameter . ' =' . $user->$parameter());

                    $target = str_replace($placeholder, $user ? $user->$parameter() : '', $target);
                    break;
                case 'order':
                    $target = str_replace($placeholder, $order ? $order->$parameter() : '', $target);
                    break;
                case 'dossier':
                    $dossier = $order ? $order->getDossier() : null;
                    $target = str_replace($placeholder, $dossier ? $dossier->$parameter() : '', $target);
                    break;
                default:
                    $target = str_replace($placeholder, '', $target);
            }
        }
    }
}