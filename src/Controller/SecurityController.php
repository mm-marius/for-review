<?php

namespace App\Controller;

use App\Entity\Email;
use App\Entity\Helpers\EmailHelper;
use App\Entity\Jwt;
use App\Entity\User;
use App\Form\ForgottenPasswordForm;
use App\Form\LoginForm;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @Route("/{_locale<%supported_locales%>}/login", name="app_login_lang")
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator, Request $request): Response
    {
        $authError = $authenticationUtils->getLastAuthenticationError();
        $error = null;
        $authError && $error[] = $translator->trans($authError->getMessageKey(), [], 'validators');
        $lastUsername = $authenticationUtils->getLastUsername();
        $data = ["email" => $lastUsername];
        $form = $this->createForm(LoginForm::class, $data);
        $returnUrl = $request->query->get("returnTo");
        return $this->render('Security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'returnUrl' => $returnUrl,
        ]);
    }

    /**
     * @Route("/forgottenPassword", name="webForgottenPassword")
     * @Route("/{_locale<%supported_locales%>}/forgottenPassword", name="webForgottenPasswordLang")
     */
    public function forgottenPassword(AuthenticationUtils $authenticationUtils,
        Request $request,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        JwtService $jwtManager, SettingService $settings): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $authError = $authenticationUtils->getLastAuthenticationError();
        $error = null;
        $authError && $error[] = $translator->trans($authError->getMessageKey(), [], 'validators');
        $lastUsername = $authenticationUtils->getLastUsername();
        $data = ["email" => $lastUsername];
        $form = $this->createForm(ForgottenPasswordForm::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $form->get('email')->getData();
            $doctrine = $this->getDoctrine();
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $userEmail]);
            if ($user && $user->getEmail()) {
                $jwt = $jwtManager->generateJwt(Jwt::TYPE_FORGOT, $user);
                $forgotUrl = $this->generateUrl('webResetPassword', ['jwt' => $jwt->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $emailData = EmailHelper::getData($doctrine, $translator, Email::TYPE_FORGOT, $request->getLocale(), $settings, ['user' => $user,
                    'forgotUrl' => $forgotUrl]);
                $email = (new TemplatedEmail())
                    ->from($_ENV['SMTP_EMAIL'])
                    ->to($user->getEmail())
                    ->subject($emailData['subject'])
                    ->htmlTemplate('Email/forgotPassword.email.twig')
                    //->textTemplate('Email/registration.text.twig')
                    ->context([
                        'content' => $emailData['content'],
                    ]);
                try {
                    $mailer->send($email);
                } catch (\Exception $ex) {
                    // LogHelper::sendToChat("*FORGOT PASSWORD SEND EMAIL*:\nTo: " . $user->getEmail() . "\nerror: " . $ex->getMessage() . "\n", __FILE__, __LINE__, null, $settings);
                }
                //TODO gestire invio di un messaggio di invio effettuato con successo
            } else {
                $error[] = $translator->trans('user.emailNotExist', [], 'validators');
            }
        }
        return $this->render('Security/forgottenPassword.html.twig', ['form' => $form->createView(), 'error' => $error]);
    }
    /**
     * @Route("/reset-password/{jwt}", name="webResetPassword")
     * @Route("/{_locale<%supported_locales%>}/reset-password/{jwt}", name="webResetPasswordLang")
     */
    public function resetPassword(
        $jwt,
        JwtService $jwtManager,
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $data = ["jwt" => $jwt];
        $form = $this->createForm(ResetPasswordForm::class, $data);
        $error = null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jwt = $form->get('jwt')->getData();
            $password = $form->get('plainPassword')->getData();
            $passwordConfirm = $form->get('plainPasswordConfirm')->getData();
            if ($jwtManager->checkJwt($jwt) && $passwordConfirm == $password) {
                //restore password of jwt user
                $user = $jwtManager->getUser($jwt);
                if ($user) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $password
                        )
                    );
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('home');
            } else {
                //TODO SET errors for jwt or different plain passwords
            }
        }
        $returnUrl = $request->query->get("returnTo");
        return $this->render('Security/forgottenPassword.html.twig', ['form' => $form->createView(),
            'returnUrl' => $returnUrl,
            'error' => $error]);
    }

    /**
     * @Route("/acitvation/{jwt}", name="webActivationLinkPassword")
     * @Route("/{_locale<%supported_locales%>}/acitvation/{jwt}", name="webActivationLinkPasswordLang")
     */
    public function userActivation(
        $jwt,
        JwtService $jwtManager,
        Request $request): Response {
        $jwt = $jwtManager->JwtHandler($jwt);
        return $this->render('Security/forgottenPassword.html.twig', ['response' => $jwt]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \Exception('logout() should never be reached');
    }
}