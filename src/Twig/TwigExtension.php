<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_login_form', [LoginRuntime::class, 'getLoginForm']),
            new TwigFunction('is_mobile', [TemplateRuntime::class, 'isMobile']),
            new TwigFunction('get_logo', [TemplateRuntime::class, 'getLogo']),
            new TwigFunction('get_url', [TemplateRuntime::class, 'getUrl']),
            new TwigFunction('get_main_css', [TemplateRuntime::class, 'getMainCss']),
            new TwigFunction('get_env_languages', [TemplateRuntime::class, 'getEnvLanguages']),
            new TwigFunction('generate_id', [TemplateRuntime::class, 'generateId']),
        ];
    }
}