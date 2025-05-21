<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\AE2;
use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\AE2FilterType;
use App\Form\RRHH\AE2Type;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\RRHH\AE2Repository;
use App\Repository\RRHH\CategoriaDocenteRepository;
use App\Repository\Security\UserRepository;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\Curl\Util;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\String\u;

/**
 * @Route("/rrhh/ae2/mes")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_REPORTE_AE2_MES")
 */
class AE2MesController extends AbstractController
{

    /**
     * @Route("/", name="app_rrhh_reporte_ae2_mes_index", methods={"GET", "POST"})
     * @param Request $request
     * @param AE2Repository $ae2Repository
     * @return Response
     */
    public function index(Request $request, AE2Repository $ae2Repository, Utils $utils)
    {
        $session = $request->getSession();

        // Si es POST, actualizar valores de sesión
        if ($request->isMethod('POST')) {
            $allPost = $request->request->all();

            $filMes = isset($allPost['mes']) && !empty($allPost['mes']) ? $allPost['mes'] : null;
            $session->set('ae2_mes_general', $filMes);

            $filAnno = isset($allPost['anno']) && !empty($allPost['anno']) ? $allPost['anno'] : null;
            $session->set('ae2_anno_general', $filAnno);

            $filUniversidad = isset($allPost['universidad']) && !empty($allPost['universidad']) ? $allPost['universidad'] : null;
            $session->set('ae2_universidad_general', $filUniversidad);
        } else {
            // No es POST, conservar lo que ya está en la sesión
            $filMes = $session->get('ae2_mes_general');
            $filAnno = $session->get('ae2_anno_general');
            $filUniversidad = $session->get('ae2_universidad_general');
        }

        if (!$filAnno) {
            $filAnno = date('Y');
        }
        // Filtros a usar
        $filters = [
            'mes' => $filMes,
            'anno' => $filAnno,
            'entidad' => $filUniversidad
        ];

        // Definición de nombres de los meses
        $meses = $utils->getMesesNombres();

        $universidades = $ae2Repository->getUniversidadesAE2();
        // Obtener registros filtrados
        $registros = $ae2Repository->findByFilters($filters);

        // Organizar los datos
        $datosOrganizados = $utils->organizarDatosPorMes($registros);
        // Texto de filtro aplicado
        $mesNombre = $meses[$filMes] ?? null;

        $nombreUniversidad = null;
        foreach ($universidades as $value) {
            if ($value['id'] == $filUniversidad) {
                $nombreUniversidad = $value['nombre'];
            }
        }

        $textFil = "Año: " . ($filAnno ?? ' ');
//        $textFil = "Mes: " . ($mesNombre ?? ' ') . " | Año: " . ($filAnno ?? ' ') . " | Universidad: " . ($nombreUniversidad ?? ' ');

        return $this->render('modules/rrhh/reporte/ae2/mes/index.html.twig', [
            'datos' => $datosOrganizados,
            'fields' => $utils->getFieldsConfig(),
            'meses' => $meses,
            'universidades' => $universidades,
            'fil_mes' => $filMes,
            'fil_anno' => $filAnno,
            'fil_universidad' => $filUniversidad,
            'text_fil' => $textFil
        ]);
    }

    /**
     * @Route("/reporte-ae2-mes/detalle", name="app_rrhh_reporte_ae2_mes_detalle", methods={"POST"})
     */
    public function detalleMes(Request $request, Utils $utils): Response
    {
        $mes = $request->request->get('mes');
        $anno = $request->request->get('anno');

        // Datos fijos de ejemplo (ajusta con tus datos reales)
        $datosTrabajadores = [
            [
                'ces' => 'UPR',
                'aprobada' => 2508,
                'cubierta' => 1644,
                'promedio' => 2062,
                'vacantes' => 864,
                'cubrim' => 65.6
            ],
            [
                'ces' => 'Org. Soroa',
                'aprobada' => 55,
                'cubierta' => 40,
                'promedio' => 43,
                'vacantes' => 15,
                'cubrim' => 72.7
            ],
            // ... resto de tus datos
        ];

        return $this->render('modules/rrhh/reporte/ae2/mes/tabla1.2.html.twig', [
            'mes' => $mes,
            'anno' => $anno,
            'meses' => $utils->getMesesNombres(), // Pasa el array directamente
            'datos' => $datosTrabajadores // Asegúrate que esta estructura coincide con lo que espera la vista
        ]);
    }


    /**
     * @Route("/reporte-ae2-mes/detalle2", name="app_rrhh_reporte_ae2_mes_detalle2", methods={"POST"})
     */
    public function detalleMes2(Request $request, Utils $utils): Response
    {
        $mes = $request->request->get('mes');
        $anno = $request->request->get('anno');

        // Obtén los datos para la segunda tabla
        $datosAdicionales = [
            // Tu estructura de datos para la tabla 1.3
            [
                'categoria' => 'Profesores',
                'cantidad' => 120,
                'porcentaje' => 45.2
            ],
            // ... más datos
        ];

        return $this->render('modules/rrhh/reporte/ae2/mes/tabla1.3.html.twig', [
            'mes' => $mes,
            'anno' => $anno,
            'meses' => $utils->getMesesNombres(),
            'datos' => $datosAdicionales
        ]);
    }
}
