<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('is_mobile', [TemplateRuntime::class, 'isMobile']),
            new TwigFunction('get_logo', [TemplateRuntime::class, 'getLogo']),
            new TwigFunction('get_url', [TemplateRuntime::class, 'getUrl']),
        ];
    }
}