<?php

namespace App\Controller\RRHH;

use App\Entity\Estructura\Entidad;
use App\Entity\Estructura\Estructura;
use App\Entity\RRHH\AE3;
use App\Repository\RRHH\AE3Repository;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/rrhh/ae3/consolidado")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE3_CONSOLIDADO")
 */
class AE3ConsolidadoController extends AbstractController
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/", name="app_rrhh_reporte_ae3_consolidado_index", methods={"GET", "POST"})
     * @param AE3Repository $AE3Repository
     * @return Response
     */
    public function index(Request $request, AE3Repository $AE3Repository, Utils $utils)
    {
        $allPost = $request->request->all();

        if (isset($allPost['mes']) && !empty($allPost['mes'])) {
            $filMes = $allPost['mes'];
            $request->getSession()->set('ae3_mes', $filMes);
        } else {
            if (!$request->getSession()->has('ae3_mes')) {
                $filMes = date('n');
                $request->getSession()->set('ae3_mes', $filMes);
            }

        }
        if (isset($allPost['anno']) && !empty($allPost['anno'])) {
            $filMes = $allPost['anno'];
            $request->getSession()->set('ae3_anno', $filMes);
        } else {
            if (!$request->getSession()->has('ae3_anno')) {
                $filAnno = date('Y');
                $request->getSession()->set('ae3_anno', $filAnno);
            }
        }


        $response = $this->render('modules/rrhh/reporte/ae3/consolidado/index.html.twig', [
            'registros' => $AE3Repository->findDistinctEntidades(null, $request->getSession()->get('ae3_mes'), $request->getSession()->get('ae3_anno')),
            'fil_mes' => $request->getSession()->get('ae3_mes'),
            'fil_anno' => $request->getSession()->get('ae3_anno'),
            'text_fil' => "Año: " . $request->getSession()->get('ae3_anno'),
        ]);
        return $response;
    }

    /**
     * @Route("/{id}/detail", name="app_rrhh_reporte_ae3_consolidado_detail", methods={"GET", "POST"})
     * @param AE3 $ae3
     * @return Response
     * @throws ExceptionInterface
     */
    public function detail($id, AE3Repository $ae3Repository)
    {
        $ae3 = $ae3Repository->findBy(['entidad' => $id], ['mes' => 'asc']);
        $ae3Array = [];
        $temp = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $meses = [];
        if (isset($ae3[0])) {
            $ae3Array = $this->normalizer->normalize($ae3[0], null, [
                'circular_reference_handler' => function ($object) {
                    return method_exists($object, 'getId') ? $object->getId() : null;
                },
            ]);
            foreach ($ae3 ?? [] as $item) {
                $meses[$temp[$item->getMes()]] = $item->getMes();
            }
        }

        return $this->render('modules/rrhh/reporte/ae3/consolidado/detail.html.twig', [
            'ae3' => $ae3Array,
            'meses' => $meses
        ]);
    }

    /**
     * @Route("/ae3/cargar-datos", name="ae3_cargar_datos", methods={"POST"})
     */
    public function cargarDatos(Request $request, Utils $utils)
    {
        $mes = (int)$request->request->get('mes');
        $anno = (int)$request->request->get('anno');
        $entidadId = (int)$request->request->get('entidadId');

        if (!$mes || !$anno || !$entidadId) {
            return $this->json([
                'status' => 'error',
                'message' => 'Parámetros inválidos'
            ], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entidad = $entityManager->getRepository(Estructura::class)->find($entidadId);

        if (!$entidad) {
            return $this->json([
                'status' => 'error',
                'message' => 'Entidad no encontrada'
            ], 404);
        }


        $ae3 = $entityManager->getRepository(AE3::class)->findOneBy([
            'entidad' => $entidad,
            'mes' => $mes,
            'anno' => $anno
        ]);

        if (!$ae3) {
            return $this->json([
                'status' => 'error',
                'hasData' => false,
                'message' => 'No se encontraron datos para el mes y año seleccionados'
            ], 404);
        }

        return $this->json([
            'status' => 'success',
            'hasData' => true,
            'data' => $utils->serializeAe3($ae3)
        ]);
    }


}
