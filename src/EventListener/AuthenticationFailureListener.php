<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse('Credenciais invalidas', JsonResponse::HTTP_UNAUTHORIZED);
        $response->setData();

        $event->setResponse($response);
    }
}
