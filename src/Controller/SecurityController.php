<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\Admin\UserChgPswFormType;
use App\Repository\Security\UserRepository;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @return Response
     */
    public function home(Request $request): Response
    {
        return $this->render('pages/dashboard.html.twig');
    }


    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/chgpsw", name="app_usuario_cambiar_contrasena", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $usuarioRepository
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function chgpsw(Request $request, UserRepository $usuarioRepository, UserPasswordEncoderInterface $encoder)
    {
        try {               
            $usuario = $this->getUser();  
            $form = $this->createForm(UserChgPswFormType::class,  $usuario, ['action' => 'chgpsw']);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {                
                    
             
                if(!$encoder->isPasswordValid($usuario, $form->getData()->getPasswordPlainOld())){
                    $this->addFlash('error', "La contraseña anterior no coincide");
                    return $this->redirectToRoute('app_usuario_cambiar_contrasena', [], Response::HTTP_SEE_OTHER);
                }

                $encodedPassword = $encoder->encodePassword($usuario, $form->getData()->getPasswordPlainText());
                $usuario->setPassword($encodedPassword);
                $usuarioRepository->add($usuario);


                $this->addFlash('success', 'La contraseña ha sido cambiada correctamente.');
                return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/usuario/changepsw.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_usuario_cambiar_contrasena', [], Response::HTTP_SEE_OTHER);
        }
    }
}
