<?php

namespace App\Controller\Configuracion;

use App\Entity\Configuracion\Idioma;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\NotificacionesUsuario;
use App\Entity\Security\User;
use App\Form\Admin\NotificacionesUsuarioType;
use App\Form\Configuracion\IdiomaType;
use App\Form\Institucion\TipoInstitucionType;
use App\Repository\Configuracion\IdiomaRepository;
use App\Repository\Institucion\TipoInstitucionRepository;
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
 * @Route("/administracion/configuracion")
 * @IsGranted("ROLE_ADMIN")
 */
class IdiomaController extends AbstractController
{


    /**
     * @Route("/listar", name="app_configuracion_idioma_index", methods={"GET"})
     * @param IdiomaRepository $idiomaRepository
     * @return Response
     */
    public function listar(IdiomaRepository $idiomaRepository)
    {
        try {
            $registros = $idiomaRepository->findBy([], ['nombre' => 'ASC']);

            return $this->render('modules/admin/configuracion/idioma/index.html.twig', [
                'registros' => $registros
            ]);

        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_configuracion_idioma_listar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/registrar", name="app_configuracion_idioma_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param IdiomaRepository $idiomaRepository
     * @return Response
     */
    public function registrar(Request $request, IdiomaRepository $idiomaRepository)
    {
        try {
            $idioma = new Idioma();
            $form = $this->createForm(IdiomaType::class, $idioma, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $idiomaRepository->add($idioma, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_configuracion_idioma_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/configuracion/idioma/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_configuracion_idioma_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_configuracion_idioma_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Idioma $idioma
     * @param IdiomaRepository $idiomaRepository
     * @return Response
     */
    public function modificar(Request $request, Idioma $idioma, IdiomaRepository $idiomaRepository)
    {
        try {
            $form = $this->createForm(IdiomaType::class, $idioma, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $idiomaRepository->edit($idioma);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_configuracion_idioma_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/configuracion/idioma/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_configuracion_idioma_modificar', ['id' => $idioma], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_configuracion_idioma_detail", methods={"GET", "POST"})
     * @param Idioma $idioma
     * @return Response
     */
    public function detail(Idioma $idioma)
    {
        return $this->render('modules/admin/configuracion/idioma/detail.html.twig', [
            'item' => $idioma,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_configuracion_idioma_eliminar", methods={"GET"})
     * @param Idioma $idioma
     * @param IdiomaRepository $idiomaRepository
     * @return Response
     */
    public function eliminar(Idioma $idioma, IdiomaRepository $idiomaRepository)
    {
        try {
            if ($idiomaRepository->find($idioma) instanceof Idioma) {
                $idiomaRepository->remove($idioma, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_configuracion_idioma_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_configuracion_idioma_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_configuracion_idioma_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
