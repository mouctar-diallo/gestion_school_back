<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class SurchargeTokenEvent
{
    public function addInfoInJwtToken(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        $data = $event->getData();//recupere les donnees du user encours
        $data['archive'] = $user->getArchive();
        $data['id']= $user->getId();
        
        $event->setData($data);
    }
}