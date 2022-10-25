<?php

namespace App\EventListener;

use App\Entity\NotificacionesUsuario;
use App\Repository\Personal\ResponsableRepository;
use App\Services\TraceService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Serializer\SerializerInterface;

class CustomLogoutListener
{

    private $router;
    private $entityManager;
    private $tokenStorage;
    private $requestStack;
    private $serializer;
    private $container;

    /**
     * CustomLogoutListener constructor.
     * @param RouterInterface $router
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     * @param ContainerInterface $container
     * @param SerializerInterface $serializer
     */
    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage,  RequestStack $requestStack, ContainerInterface $container, SerializerInterface $serializer)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
        $this->container = $container;
    }

    /**
     * @param LogoutEvent $logoutEvent
     * @return void
     */
    #[NoReturn]
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent): void
    {

        $traceService = new TraceService($this->requestStack, $this->entityManager, $this->serializer);
        $traceService->registrar($this->container->getParameter('accion_cierre_sesion'), $this->container->getParameter('objeto_autenticacion'),null, null, $this->container->getParameter('tipo_traza_sesion'));


//        $notificacion = new NotificacionesUsuario();
//        $notificacion->setUsuarioEnvia($this->tokenStorage->getToken()->getUser());
//        $notificacion->setUsuarioRecive($this->tokenStorage->getToken()->getUser());
//        $notificacion->setFechaCreado(new \DateTime());
//        $notificacion->setLeido(false);
//        $notificacion->setTexto('El usuario ' . $this->tokenStorage->getToken()->getUser()->getUserIdentifier() . ' ha cerrado el sistema.');
//
//        $url = $this->router->generate("app_todo_lista_cliente", [], UrlGeneratorInterface::ABSOLUTE_URL);
//
//        $notificacion->setUrl($url);
//
//        $this->entityManager->persist($notificacion);
//        $this->entityManager->flush();
    }
}
