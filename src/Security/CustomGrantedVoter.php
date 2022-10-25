<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomGrantedVoter extends RoleVoter
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        $roles = $this->extractRoles($token);

        $rolesCheck = array();        

        foreach ($attributes as $attribute) {

            if(is_array($attribute)){
                foreach($attribute as $rol){
                    if (!\is_string($rol) || !$this->supportsAttribute($rol)) {
                        continue;
                    }

                    $rolesCheck[] = $rol;
                }
            } 
            else if(is_string($attribute)) {
                if (!\is_string($attribute) || !$this->supportsAttribute($attribute)) {
                    continue;
                }

                $rolesCheck[] = $attribute;
            }
        }       
        
        foreach ($roles as $role) {
            $result = VoterInterface::ACCESS_DENIED;
            if(in_array($role, $rolesCheck)){           
                return VoterInterface::ACCESS_GRANTED;           
            }
        }  

        return $result;
    }
}