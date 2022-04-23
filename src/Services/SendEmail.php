<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class SendEmail
{

    public static function send(Environment $twig, TranslatorInterface $translator, $to, $subject, $params = [], $template): JsonResponse
    {
        $success = true;
        $error = [];
        $transport = new GmailSmtpTransport('miclean.marius88@gmail.com', 'Apocaliptica1');
        $mailer = new Mailer($transport);
        $htmlContents = $twig->render($template, $params);
        $email = (new Email())
            ->from($_ENV['SMTP_EMAIL'])
            ->to($to)
            ->subject($subject)
            ->html($htmlContents);
        try {
            $mailer->send($email);
        } catch (\Exception $ex) {
            // LogHelper::sendToChat("*REGISTRATION SEND EMAIL*:\nTo: " . $user->getEmail() . "\nerror: " . $ex->getMessage() . "\n", __FILE__, __LINE__, null, $settings);
            //TODO we need to handle the not a valid domain error
            //Error: Expected response code "250/251/252" but got code "550", with message "550 5.1.1 ewlcnXcQbFqeSewlcnchpx prato.it dominio non valido / invalid destination domain".
            $error[] = $translator->trans('NOT_A_VALID_EMAIL_ADDRESS', [], 'security');
            $success = false;
        }
        return new JsonResponse(["success" => $success, 'error' => $error]);
    }
}