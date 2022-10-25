<?php

namespace App\Services\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CustomTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [           
            
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('hasRoles', [SecurityTwigExtension::class, 'hasRoles']),
        ];
    }
}