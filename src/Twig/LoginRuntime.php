<?php
namespace App\Twig;

use App\Form\LoginForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class LoginRuntime implements RuntimeExtensionInterface
{
    private $request;
    private $router;
    private $formFactory;

    public function __construct(RequestStack $request, $router, $formFactory)
    {
        $request && $this->request = $request->getCurrentRequest();
        $this->router = $router;
        $this->formFactory = $formFactory;
    }
    public function getLoginForm()
    {

        $returnTo = $this->request ? $this->request->attributes->get('_route') : 'home';
        $form = $this->formFactory->create(LoginForm::class, null, ['box' => true, 'action' => $this->router->generate('webLogin', ['returnTo' => $returnTo])]);
        return $form->createView();
    }
}