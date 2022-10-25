<?php

namespace App\Services\Twig;

use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\Security\Core\Security;

class SecurityTwigExtension implements RuntimeExtensionInterface
{
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function hasRoles(...$roles): bool
    {
        $userRoles = $this->security->getUser()->getRoles();

        if(in_array('ROLE_ADMIN', $userRoles))
        {
            return true;
        }

        foreach($roles as $rol)
        {
            if(in_array($rol, $userRoles))
            {
                return true;
            }
        }
       

        return false;
    }
}