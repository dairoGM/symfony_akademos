<?php

namespace App\Controller\Notificacion;

use App\Entity\NotificacionesUsuario;
use App\Form\Admin\NotificacionesUsuarioType;
use App\Repository\NotificacionesUsuarioRepository;
use App\Repository\Planificacion\ObjetivoGeneralRepository;
use App\Repository\Security\UserRepository;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/administracion/notificaciones")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_FUNC")
 */
class GestionNotificationController extends AbstractController
{


    /**
     * @Route("/listar", name="app_notificaciones_usuario_listar", methods={"GET"})
     * @param NotificacionesUsuarioRepository $notificacionesUsuarioRepository
     * @return Response
     */
    public function listar(NotificacionesUsuarioRepository $notificacionesUsuarioRepository)
    {
        try {

            $registros = $notificacionesUsuarioRepository->findBy([], ['fechaCreado' => 'DESC']);

            return $this->render('modules/admin/notificaciones/index.html.twig', [
                'registros' => $registros
            ]);

        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_notificaciones_usuario_listar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/registrar", name="app_notificaciones_usuario_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param NotificacionesUsuarioRepository $notificacionesUsuarioRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function registrar(Request $request, NotificacionesUsuarioRepository $notificacionesUsuarioRepository, UserRepository $userRepository)
    {
        try {
            $allPost = $request->request->all();
            $notificacionUser = new NotificacionesUsuario();
            $form = $this->createForm(NotificacionesUsuarioType::class, $notificacionUser, ['action' => 'registrar']);
            if (isset($allPost['notificaciones_usuario']['usuarioRecive'])) {
                foreach ($allPost['notificaciones_usuario']['usuarioRecive'] as $value) {
                    $notificacion = new NotificacionesUsuario();
                    $notificacion->setUsuarioEnvia($this->getUser());
                    $notificacion->setLeido(false);
                    $notificacion->setFechaCreado(new \DateTime());
                    $notificacion->setUrl($allPost['notificaciones_usuario']['url']);
                    $notificacion->setTexto($allPost['notificaciones_usuario']['texto']);
                    $notificacion->setUsuarioRecive($userRepository->find($value));
                    $notificacionesUsuarioRepository->add($notificacion, true);
                }
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_notificaciones_usuario_listar', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/notificaciones/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_notificaciones_usuario_listar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/eliminar", name="app_notificaciones_usuario_eliminar2", methods={"GET"})
     * @param Request $request
     * @param NotificacionesUsuario $notificacionesUsuario
     * @param NotificacionesUsuarioRepository $notificacionesUsuarioRepository
     * @return Response
     */
    public function eliminar(Request $request, NotificacionesUsuario $notificacionesUsuario, NotificacionesUsuarioRepository $notificacionesUsuarioRepository)
    {
        try {
            $notificacionesUsuarioRepository->remove($notificacionesUsuario, true);
            $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
            return $this->redirectToRoute('app_notificaciones_usuario_listar', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_notificaciones_usuario_listar', [], Response::HTTP_SEE_OTHER);
        }
    }
}
