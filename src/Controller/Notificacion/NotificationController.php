<?php

namespace App\Controller\Notificacion;

use App\Entity\NotificacionesUsuario;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Planificacion\ObjetivoGeneralRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{

    /**
     * @Route("/notificaciones/usuario", name="app_notificaciones_usuario")
     * @param NotificacionesUsuarioRepository $notificacionesUsuarioRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function index(NotificacionesUsuarioRepository $notificacionesUsuarioRepository): Response
    {

        $notificaciones = $notificacionesUsuarioRepository->findBy(['usuarioRecive' => $this->getUser()], ['id' => 'DESC']);

        return $this->render('cliente/notificaciones.html.twig', [
            'notificaciones' => $notificaciones,
        ]);
    }

    /**
     * @Route("/notificaciones/usuario/todas", name="app_notificaciones_usuario_todas")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function todas(NotificacionesUsuarioRepository $notificacionesUsuarioRepository): Response
    {

        $notificaciones = $notificacionesUsuarioRepository->findBy(['usuarioRecive' => $this->getUser()]);

        return $this->render('cliente/notificacionesTodas.html.twig', [
            'notificaciones' => $notificaciones,
        ]);
    }

    /**
     * @Route("/notificaciones/usuario/eliminar/{id}", name="app_notificaciones_usuario_eliminar")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function eliminar(EntityManagerInterface $entityManager, NotificacionesUsuario $id)
    {

        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('app_notificaciones_usuario_todas');
    }
}
