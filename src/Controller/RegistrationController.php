<?php

namespace App\Controller;

use App\Entity\Jwt;
use App\Entity\User;
use App\Form\RegistrationForm;
use App\Security\LoginFormAuthenticator;
use App\Services\Helpers\FormHelper;
use App\Services\JwtService;
use App\Services\SendEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/email", name="email")
     */
    public function testEmail(Request $request, Environment $twig)
    {

        $transport = new GmailSmtpTransport('miclean.marius88@gmail.com', 'Apocaliptica1');
        $mailer = new Mailer($transport);

        $htmlContents = $twig->render('Email/registration.html.twig', [
            'url' => "jhgbjhgbjhb",
        ]);

        $email = (new Email())
            ->from($_ENV['SMTP_EMAIL'])
            ->to('miclean.marius@yahoo.com')
            ->subject("teste subiect")
            ->html($htmlContents);

        $mailer->send($email);
        return new Response();
    }

    /**
     * @Route("/registration", name="app_registration")
     * @Route("/{_locale<%supported_locales%>}/registration", name="app_registration_lang")
     */
    public function index(Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        TranslatorInterface $translator,
        JwtService $jwtManager,
        Environment $twig, EventDispatcherInterface $dispatcher): Response {

        $lang = $request->getLocale();
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user, [
            FormHelper::LOCALE => $lang,
            RegistrationForm::HAS_AGREE_TERMS => true,
            RegistrationForm::PRIVACY_READ_TEXT => $translator->trans('fields.readFullPolicy', [], 'security')
        ]);
        $form->handleRequest($request);

        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles($user->getRoles());

            $clientCode = implode('-', str_split(substr(strtoupper(md5(time() . rand(1000, 9999))), 0, 20), 4));
            $user->setClientCode($clientCode);
            $doctrine = $this->getDoctrine();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $jwt = $jwtManager->generateJwt(Jwt::TYPE_ACTIVATION, $user);
            $activationUrl = $this->generateUrl('checkJwt', ['jwt' => $jwt->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            if ($user && $user->getEmail()) {
                SendEmail::send($twig, $translator, $user->getEmail(), [
                    'url' => $activationUrl,
                ], 'Email/registration.html.twig');
                // $transport = new GmailSmtpTransport('miclean.marius88@gmail.com', 'Apocaliptica1');
                // $mailer = new Mailer($transport);
                // $htmlContents = $twig->render('Email/registration.html.twig', [
                //     'url' => $activationUrl,
                // ]);
                // $email = (new Email())
                //     ->from($_ENV['SMTP_EMAIL'])
                //     ->to($user->getEmail())
                //     ->subject("teste subiect")
                //     ->html($htmlContents);
                // try {
                //     $mailer->send($email);
                // } catch (\Exception $ex) {
                //     // LogHelper::sendToChat("*REGISTRATION SEND EMAIL*:\nTo: " . $user->getEmail() . "\nerror: " . $ex->getMessage() . "\n", __FILE__, __LINE__, null, $settings);
                //     //TODO we need to handle the not a valid domain error
                //     //Error: Expected response code "250/251/252" but got code "550", with message "550 5.1.1 ewlcnXcQbFqeSewlcnchpx prato.it dominio non valido / invalid destination domain".
                //     $error[] = $translator->trans('NOT_A_VALID_EMAIL_ADDRESS', [], 'security');
                // }
            }
            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'app_login'
            // );

            $event = new SecurityEvents($request);
            $dispatcher->dispatch($event, SecurityEvents::INTERACTIVE_LOGIN);
            return $this->redirectToRoute('app_login', ['validateEmail' => '1']);

        }
        return $this->render('registration/registration.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => $error,
            'policyPage' => $this->get('router')->generate('policyPage'),
        ]);
    }
}