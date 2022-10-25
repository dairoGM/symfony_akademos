<?php


namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use \Symfony\Component\HttpFoundation\RequestStack;
use \Symfony\Component\DependencyInjection\Container;

class JWTCreatedListener
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
     * @param JWTCreatedEvent $event
     *
     * @return void
     * @throws \Exception
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
//        $expiration = new \DateTime('+365 day');
//        $payload = $event->getData();
//        $payload['exp'] = $expiration->getTimestamp();
//        $payload['valid_token'] = 123;// md5($this->container->get('idxboost.data_access')->getUserByUsername($payload['username'])['password']);
//        $event->setData($payload);
    }
}