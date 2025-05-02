<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE2;
use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\AE2Type;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE2Repository;
use App\Repository\RRHH\CategoriaDocenteRepository;
use App\Repository\Security\UserRepository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\String\u;

/**
 * @Route("/rrhh/ae2")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE2")
 */
class AE2Controller extends AbstractController
{

    /**
     * @Route("/", name="app_rrhh_reporte_ae2_index", methods={"GET"})
     * @param AE2Repository $AE2Repository
     * @return Response
     */
    public function index(AE2Repository $AE2Repository, PersonaRepository $personaRepository)
    {
        $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
        $entidad = $persona[0]->getEntidad();
        return $this->render('modules/rrhh/reporte/ae2/index.html.twig', [
            'registros' => $AE2Repository->findBy(['entidad' => $entidad], ['id' => 'desc']),
        ]);
    }


    /**
     * @Route("/registrar", name="app_rrhh_reporte_ae2_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE2Repository $aE2Repository
     * @return Response
     */
    public function registrar(Request $request, AE2Repository $aE2Repository, EstructuraRepository $estructuraRepository, PersonaRepository $personaRepository, UserRepository $userRepository)
    {
//        try {
            $ae2 = new AE2();
            if (!$ae2->getMes()) {
                $ae2->setMes((int)date('n'));
            }
            if (!$ae2->getAnno()) {
                $ae2->setAnno((int)date('Y'));
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            if (isset($persona[0]) && $persona[0]->getEntidad()) {
                $ae2->setEntidad($persona[0]->getEntidad());
            }

            $form = $this->createForm(AE2Type::class, $ae2, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
                if (isset($persona[0])) {
                    if (!$persona[0]->getEntidad()) {
                        $this->addFlash('error', 'La institucion no es correcta.');
                    }

                    $aE2Repository->add($ae2, true);
                    $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                    return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'Persona no encontrada.');
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae2/new.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad
            ]);
//        } catch (\Exception $exception) {
//            $this->addFlash('error', $exception->getMessage());
//            return $this->redirectToRoute('app_rrhh_reporte_ae2_registrar', [], Response::HTTP_SEE_OTHER);
//        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rrhh_reporte_ae2_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param AE2 $ae2
     * @param AE2Repository $ae2Repository
     * @return Response
     */
    public function modificar(Request $request, AE2 $ae2, AE2Repository $ae2Repository, PersonaRepository $personaRepository)
    {
        try {
            $form = $this->createForm(AE2Type::class, $ae2, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ae2Repository->edit($ae2);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
            }
            $persona = $personaRepository->findBy(['usuario' => $this->getUser()->getId()]);
            $entidad = isset($persona[0]) ? $persona[0]->getEntidad() : null;
            return $this->render('modules/rrhh/reporte/ae2/edit.html.twig', [
                'form' => $form->createView(),
                'entidad' => $entidad
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae2_modificar', ['id' => $ae2->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_rrhh_reporte_ae2_detail", methods={"GET", "POST"})
     * @param AE2 $ae2
     * @return Response
     */
    public function detail(AE2 $ae2)
    {
        return $this->render('modules/rrhh/reporte/ae2/detail.html.twig', [
            'item' => $ae2,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rrhh_reporte_ae2_eliminar", methods={"GET"})
     * @param Request $request
     * @param AE2 $ae2
     * @param AE2Repository $ae2Repository
     * @return Response
     */
    public function eliminar(Request $request, AE2 $ae2, AE2Repository $ae2Repository)
    {
        try {
            if ($ae2Repository->find($ae2) instanceof AE2) {
                $ae2Repository->remove($ae2, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_reporte_ae2_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
