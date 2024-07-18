<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\CategoriaAcreditacionIES;
use App\Entity\Evaluacion\CategoriaAcreditacionPosgrado;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\InstitucionCentrosEstudios;
use App\Entity\Institucion\InstitucionCum;
use App\Entity\Institucion\InstitucionEditorial;
use App\Entity\Institucion\InstitucionFacultades;
use App\Entity\Institucion\InstitucionFum;
use App\Entity\Institucion\InstitucionRecursoHumano;
use App\Entity\Institucion\InstitucionRedes;
use App\Entity\Institucion\InstitucionRedesSociales;
use App\Entity\Institucion\InstitucionRevistaCientifica;
use App\Entity\Institucion\InstitucionSedes;
use App\Entity\Postgrado\SolicitudPrograma;
use App\Entity\posgrado\SolicitudProgramaAcademico;
use App\Entity\Security\User;
use App\Export\Institucion\ExportListInstitucionToPdf;
use App\Export\Institucion\ExportListPlanEstudioToPdf;
use App\Form\Evaluacion\CategoriaAcreditacionIESType;
use App\Form\Evaluacion\CategoriaAcreditacionPosgradoType;
use App\Form\Institucion\InstitucionCentrosEstudiosType;
use App\Form\Institucion\InstitucionCumType;
use App\Form\Institucion\InstitucionEditorialesType;
use App\Form\Institucion\InstitucionFacultadesType;
use App\Form\Institucion\InstitucionFumType;
use App\Form\Institucion\InstitucionRedesSocialesType;
use App\Form\Institucion\InstitucionRedesType;
use App\Form\Institucion\InstitucionRevistaCientificaType;
use App\Form\Institucion\InstitucionSedesType;
use App\Form\Institucion\InstitucionType;
use App\Form\Institucion\NombreDescripcionType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Evaluacion\CategoriaAcreditacionIESRepository;
use App\Repository\Evaluacion\CategoriaAcreditacionPosgradoRepository;
use App\Repository\Institucion\CategoriaAcreditacionRepository;
use App\Repository\Institucion\InstitucionCentrosEstudiosRepository;
use App\Repository\Institucion\InstitucionCumRepository;
use App\Repository\Institucion\InstitucionEditorialRepository;
use App\Repository\Institucion\InstitucionFacultadesRepository;
use App\Repository\Institucion\InstitucionFumRepository;
use App\Repository\Institucion\InstitucionRecursoHumanoRepository;
use App\Repository\Institucion\InstitucionRedesRepository;
use App\Repository\Institucion\InstitucionRedesSocialesRepository;
use App\Repository\Institucion\InstitucionRepository;
use App\Repository\Institucion\InstitucionRevistaCientificaRepository;
use App\Repository\Institucion\InstitucionSedesRepository;
use App\Repository\Institucion\RecursosHumanosRepository;
use App\Repository\Postgrado\SolicitudProgramaRepository;
use App\Repository\posgrado\SolicitudProgramaAcademicoInstitucionRepository;
use App\Repository\posgrado\SolicitudProgramaAcademicoRepository;
use App\Services\DoctrineHelper;
use App\Services\HandlerFop;
use App\Services\TraceService;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/evaluacion/categoria_acreditacion_posgrado")
 * @IsGranted("ROLE_ADMIN", "ROLE_EVALAUCION_GEST_CATEGORIA_ACREDITACION_POSGRADO")
 */
class CategoriaAcreditacionPosgradoController extends AbstractController
{

    /**
     * @Route("/", name="app_evaluacion_categoria_acreditacion_posgrado_index", methods={"GET"})
     * @return Response
     */
    public function index(SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        return $this->render('modules/evaluacion/categoria_acreditacion_posgrado/index.html.twig', [
            'registros' => $solicitudProgramaRepository->getProgramasV2(),
        ]);
    }


    /**
     * @Route("/{id}/modificar", name="app_evaluacion_categoria_acreditacion_posgrado_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param SolicitudPrograma $solicitudPrograma
     * @param CategoriaAcreditacionRepository $categoriaAcreditacionRepository
     * @param CategoriaAcreditacionPosgradoRepository $categoriaAcreditacionPosgradoRepository
     * @param SolicitudProgramaRepository $solicitudProgramaRepository
     * @return Response
     */
    public function modificar(Request $request, SolicitudPrograma $solicitudPrograma, CategoriaAcreditacionRepository $categoriaAcreditacionRepository, CategoriaAcreditacionPosgradoRepository $categoriaAcreditacionPosgradoRepository, SolicitudProgramaRepository $solicitudProgramaRepository)
    {
        try {
            $new = new CategoriaAcreditacionPosgrado();
            $exist = $categoriaAcreditacionPosgradoRepository->findBy(['solicitudPrograma' => $solicitudPrograma->getId()]);
            if (isset($exist[0])) {
                $new = $exist[0];
            }
            $form = $this->createForm(CategoriaAcreditacionPosgradoType::class, $new, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($request->request->all()['categoria_acreditacion_posgrado']['fechaEmision'])) {
                    $temp = explode('/', $request->request->all()['categoria_acreditacion_posgrado']['fechaEmision']);
                    if (isset($temp[0]) && isset($temp[1]) && isset($temp[2]))
                        $new->setFechaEmision(new \DateTime($temp[1] . '/' . $temp[0] . '/' . $temp[2]));
                }
                $new->setCategoriaAcreditacion($categoriaAcreditacionRepository->find($request->request->all()['categoria_acreditacion_posgrado']['categoriaAcreditacion']));
                $new->setSolicitudPrograma($solicitudPrograma);
                $categoriaAcreditacionPosgradoRepository->edit($new, true);

                $new->getSolicitudPrograma()->setCategoriaAcreditacion($new->getCategoriaAcreditacion());
                $solicitudProgramaRepository->edit($new->getSolicitudPrograma(), true);

                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_evaluacion_categoria_acreditacion_posgrado_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/evaluacion/categoria_acreditacion_posgrado/edit.html.twig', [
                'form' => $form->createView(),
                'solicitudPrograma' => $solicitudPrograma,
                'new' => $new
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_evaluacion_categoria_acreditacion_posgrado_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
