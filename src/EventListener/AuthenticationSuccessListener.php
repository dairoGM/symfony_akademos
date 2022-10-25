<?php

namespace App\EventListener;

use App\Entity\Security\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function  onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
       
        if (!$user instanceof User) {
            return;
        }
        $data['userData'] = array(
            'id' => $user->getId(),
            'roles' => $user->getRoles(),
            'email' => $user->getEmail()
        );

        $event->setData($data);
    }
}