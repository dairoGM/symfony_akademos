<?php

namespace App\EventListener;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use \Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

class JWTDecodedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param RequestStack $requestStack
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(RequestStack $requestStack, Container $container)
    {
        $this->requestStack = $requestStack;
        $this->container = $container;
    }

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     * @throws \Exception
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
//        $payload = $event->getPayload();

//        if (true) {
//        if ($payload['valid_token'] != md5($this->container->get('idxboost.data_access')->getUserByUsername($payload['username'])['password'])) {
//            $event->markAsInvalid();
//        }
    }
}