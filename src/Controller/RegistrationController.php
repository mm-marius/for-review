<?php

namespace App\Controller;

use App\Entity\Email;
use App\Entity\Helpers\EmailHelper;
use App\Entity\Jwt;
use App\Entity\User;
use App\Form\RegistrationForm;
use App\Handlers\VIP\VipHandler;
use App\Security\LoginFormAuthenticator;
use App\Services\Helpers\FormHelper;
use App\Services\JwtService;
use App\Services\SettingService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/registration", name="app_registration")
     */
    public function index(Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        JwtService $jwtManager,
        SettingService $settings): Response {

        $vipHandler = new VipHandler($settings);

        $lang = $request->getLocale();
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user, [
            FormHelper::LOCALE => $lang,
            // RegistrationForm::HAS_AGREE_TERMS => !empty($privacies),
            // RegistrationForm::PRIVACY_READ_TEXT => $translator->trans('read', [], 'security')
        ]);
        $form->handleRequest($request);

        $error = null;
        // $destination = $user->getNationCodeDestination();
        if ($form->isSubmitted() && $form->isValid()) {
            // if (!$destination || empty($destination->destinationsTarget)) {
            //     $form->get('nationCodeDestination')->addError(new FormError($translator->trans('nation.empty', [], 'validators')));
            //     $validTax = false;
            // }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $result = $vipHandler->createUser($translator, $user);
            if ($result->success) {
                $clientCode = $result->data;
                $user->setClientCode($clientCode);
                $doctrine = $this->getDoctrine();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $jwt = $jwtManager->generateJwt(Jwt::TYPE_ACTIVATION, $user);
                $activationUrl = $this->generateUrl('checkJwt', ['jwt' => $jwt->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                if ($user && $user->getEmail()) {
                    $emailData = EmailHelper::getData($doctrine, $translator, Email::TYPE_REGISTRATION, $request->getLocale(), $settings, ['user' => $user, 'activationUrl' => $activationUrl]);
                    $email = (new TemplatedEmail())
                        ->from($_ENV['SMTP_EMAIL'])
                        ->to($user->getEmail())
                        ->subject($emailData['subject'])
                        ->htmlTemplate('Email/registration.email.twig')
                        //->textTemplate('Email/registration.text.twig')
                        ->context([
                            'content' => $emailData['content'],
                        ]);
                    try {
                        $mailer->send($email);
                    } catch (\Exception $ex) {
                        // LogHelper::sendToChat("*REGISTRATION SEND EMAIL*:\nTo: " . $user->getEmail() . "\nerror: " . $ex->getMessage() . "\n", __FILE__, __LINE__, null, $settings);
                        //TODO we need to handle the not a valid domain error
                        //Error: Expected response code "250/251/252" but got code "550", with message "550 5.1.1 ewlcnXcQbFqeSewlcnchpx prato.it dominio non valido / invalid destination domain".
                        $error[] = $translator->trans('NOT_A_VALID_EMAIL_ADDRESS', [], 'security');
                    }
                }
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main'
                );
            } else {

                $error[] = print_r($result->errorMessage, true);
            }

            // $user->setBirthDatePicker($user->getBirthDatePicker());
        }
        $returnUrl = $request->query->get("returnTo");
        // return $this->render('Registration/registration.html.twig', [
        //     'returnUrl' => $returnUrl,
        //     'privacies' => $privacies,
        //     'registrationForm' => $form->createView(), RegistrationForm::BUSINESS => $isBusiness, RegistrationForm::AGENCY => $isAgency, 'error' => $error,
        // ]);
        return $this->render('Registration/registration.html.twig');
    }
}