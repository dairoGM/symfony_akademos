<?php

namespace App\Security;

use App\Entity\Personal\Persona;
use App\Entity\Security\User;
use App\Services\TraceService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $serializer;
    private $requestStack;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer,
                                RequestStack $requestStack, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->container = $container;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        $request->getSession()->set('password', $request->request->get('password'));
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        /* @var $user User */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);;

        $persona = $this->entityManager->getRepository(Persona::class)->findOneBy(['usuario' => (method_exists($user, 'getId') ? $user->getId() : -1)]);


        $session = new Session();
        if ($persona instanceof Persona) {
            $session->set('id_usuario_autenticado', $user->getId());
            $session->set('usuario_autenticado_role_admin', in_array('ROLE_ADMIN', $user->getRoles()));

            $session->set('foto_usuario_autenticado', $persona->getFoto());
            $session->set('nombre_usuario_autenticado', $persona->getPrimerNombre() . ' ' . $persona->getSegundoNombre() . ' ' . $persona->getPrimerApellido() . ' ' . $persona->getSegundoApellido());
            $session->set('responsabilidad_usuario_autenticado', $persona->getResponsabilidad()->getNombre());
            $session->set('estructura_usuario_autenticado', $persona->getEstructura()->getId());
            $session->set('id_persona_usuario_autenticado', $persona->getId());
            $session->set('password_change_first_time', $user->getPasswordChangeFirstTime());

            $traceService = new TraceService($this->requestStack, $this->entityManager, $this->serializer);
            $traceService->registrar($this->container->getParameter('accion_inicio_sesion'), $this->container->getParameter('objeto_autenticacion'),null, null, $this->container->getParameter('tipo_traza_sesion'));

        } else {
            $session->set('nombre_usuario_autenticado', (method_exists($user, 'getUsername') ? $user->getUsername() : null));
            $session->set('id_persona_usuario_autenticado', -1);
            $session->set('id_usuario_autenticado', 1);
        }


        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Correo electrónico no encontrado.');
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {      
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            if (!$request->getSession()->get(('password_change_first_time')) && !$request->getSession()->get(('usuario_autenticado_role_admin'))) {
                $idUser = $request->getSession()->get(('id_usuario_autenticado'));
                return new RedirectResponse("administracion/cambio_clave/$idUser/ejecutar");
            }
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
//        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse('/');
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
