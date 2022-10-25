<?php

namespace App\Services;

use App\Entity\Personal\Persona;
use App\Entity\Planificacion\PlanIndicador;
use App\Entity\Security\Rol;
use App\Entity\Security\RolEstructura;
use App\Entity\Security\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Service("agent.utils")
 */
class Utils
{

    private $baseUrl;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $this->em = $em;
    }

    public function getToken($email, $password)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('POST', $this->baseUrl . "/api/login_check", [
            'body' => json_encode(['email' => $email, 'password' => $password]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return isset($result['token']) ? $result['token'] : null;;
    }

    public function procesarNomenclador($nomenclador)
    {
        $result = [];
        if (is_array($nomenclador)) {
            foreach ($nomenclador as $value) {
                $element['id'] = $value->getId();
                $element['nombre'] = $value->getNombre();
                $result[] = $element;
            }
        }
        return $result;
    }

    public function procesarNomencladorResponsabilidad($nomenclador)
    {
        $result = [];
        if (is_array($nomenclador)) {
            foreach ($nomenclador as $value) {
                $element['id'] = $value->getResponsabilidad()->getId();
                $element['nombre'] = $value->getResponsabilidad()->getNombre();
                $result[] = $element;
            }
        }
        return $result;
    }

    public function procesarOrganizaciones($organizaciones)
    {
        $result = [];
        if (is_array($organizaciones)) {
            foreach ($organizaciones as $value) {
                $result[] = $value->getOrganizacion()->getId();
            }
        }
        return $result;
    }

    public function getSiguienteCodigo($repositorio)
    {
        $codigo = null;
        $todos = $repositorio->findBy([], ['id' => 'desc']);
        $count = count($todos) + 1;
//        $codigo = str_pad($count, 4, "0", STR_PAD_LEFT);
        $exist = $repositorio->findBy(['codigo' => $codigo], ['id' => 'desc']);
        while (count($exist) > 0) {
            $count++;
//            $codigo = str_pad($count, 4, "0", STR_PAD_LEFT);
            $exist = $repositorio->findBy(['codigo' => $codigo], ['id' => 'desc']);
        }
//        $codigo = str_pad($count, 4, "0", STR_PAD_LEFT);
        $codigo = $count;
        return $codigo;
    }

    public function procesarObjetivosGeneral($planObjetivoGeneralRepository)
    {
        $final = [];
        foreach ($planObjetivoGeneralRepository as $value) {
            $final[] = (string)$value->getObjetivoGeneral()->getId();
        }
        return implode(',', $final);
    }

    public function procesarObjetivosEspecificos($planObjetivoEspecificoRepository)
    {
        $final = [];
        foreach ($planObjetivoEspecificoRepository as $value) {
            $final[] = (string)$value->getObjetivoEspecifico()->getId();
        }
        return implode(',', $final);
    }


    public function getInformacionPlan1($planIndicadorRepository, $planIndicadorResponsableRepository, $planId)
    {
        $final = [];
        $objGral = [];
        $objGralId = [];
        $objEsp = [];
        $objEspId = [];
        $indic = [];
        $indicId = [];

        $planValor = [];
        $formaEvaluacion = [];
        foreach ($planIndicadorRepository as $value) {
            if (!in_array($value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId(), $objGralId)) {
                $objGral[] = $value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral();
                $objGralId[] = $value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId();
            }

            if (!in_array($value->getIndicador()->getObjetivoEspecifico()->getId(), $objEspId)) {
                $objEsp[$value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId()][] = $value->getIndicador()->getObjetivoEspecifico();
                $objEspId[] = $value->getIndicador()->getObjetivoEspecifico()->getId();
            }

            if (!in_array($value->getIndicador()->getId(), $indicId)) {
                $indic[$value->getIndicador()->getObjetivoEspecifico()->getId()][] = $value->getIndicador();
                $indicId[] = $value->getIndicador()->getId();
            }
            $planValor[$value->getIndicador()->getId()] = $value->getPlanValor();

            $formaEvaluacion[$value->getIndicador()->getId()] = (method_exists($value->getFormaEvaluacion(), 'getId')) ? $value->getFormaEvaluacion()->getId() : null;
            $formaEvaluacionNombres[$value->getIndicador()->getId()] = (method_exists($value->getFormaEvaluacion(), 'getNombre')) ? $value->getFormaEvaluacion()->getNombre() : null;
        }

        foreach ($objGral as $value) {
            $item = null;
            $item['idObjetivoGeneral'] = $value->getId();
            $item['nombreObjetivoGeneral'] = $value->getNombre();

            $objEspec = [];
            foreach ($objEsp[$value->getId()] as $value2) {
                $itemOE['idObjetivoEspecifico'] = $value2->getId();
                $itemOE['nombreObjetivoEspecifico'] = $value2->getNombre();

                $indicadores = [];
                foreach ($indic[$value2->getId()] as $value3) {
                    $itemI['idIndicador'] = $value3->getId();
                    $itemI['codigoIndicador'] = $value3->getCodigo();
                    $itemI['nombreIndicador'] = $value3->getNombre();
                    $itemI['unidadMedida'] = $value3->getUnidadMedida()->getSiglas();


                    $itemI['valorPlan'] = $planValor[$value3->getId()];
                    $itemI['formaEvaluacion'] = $formaEvaluacion[$value3->getId()];
                    $itemI['formaEvaluacionNombre'] = $formaEvaluacionNombres[$value3->getId()];
                    $arrayResponsables = [];
                    $arrayResponsablesNombre = [];
                    foreach ($planIndicadorResponsableRepository->findBy(['indicador' => $value3->getId(), 'plan' => $planId, 'activo'=>true]) as $value) {
                        $arrayResponsables[] = $value->getResponsable()->getId();
                        $arrayResponsablesNombre[] = $value->getResponsable()->getPrimerNombre() . ' ' . $value->getResponsable()->getSegundoNombre() . ' ' . $value->getResponsable()->getPrimerApellido() . ' ' . $value->getResponsable()->getSegundoApellido();
                    }
                    $itemI['responsables'] = $arrayResponsables;
                    $itemI['responsablesNombre'] = $arrayResponsablesNombre;
                    $indicadores[] = $itemI;
                }
                $itemOE['indicadores'] = $indicadores;
                $objEspec[] = $itemOE;
            }
            $item['objetivosEspecificos'] = $objEspec;
            $final[] = $item;
        }
        return $final;
    }

    /**
     * @return string
     */
    public function getMeses()
    {
        $meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];
        return $meses;
    }


    public function getAwesomeIcons()
    {
        $fonts = array(
            'fa-address-book',
            'fa-address-card',
            'fa-adjust',
            'fa-align-center',
            'fa-align-justify',
            'fa-align-left',
            'fa-align-right',
            'fa-ambulance',
            'fa-american-sign-language-interpreting',
            'fa-anchor',
            'fa-angle-double-down',
            'fa-angle-double-left',
            'fa-angle-double-right',
            'fa-angle-double-up',
            'fa-angle-down',
            'fa-angle-left',
            'fa-angle-right',
            'fa-angle-up',
            'fa-archive',
            'fa-arrow-circle-down',
            'fa-arrow-circle-left',
            'fa-arrow-circle-right',
            'fa-arrow-circle-up',
            'fa-arrow-down',
            'fa-arrow-left',
            'fa-arrow-right',
            'fa-arrow-up',
            'fa-arrows-alt',
            'fa-assistive-listening-systems',
            'fa-asterisk',
            'fa-at',
            'fa-audio-description',
            'fa-backward',
            'fa-balance-scale',
            'fa-ban',
            'fa-barcode',
            'fa-bars',
            'fa-bath',
            'fa-battery-empty',
            'fa-battery-full',
            'fa-battery-half',
            'fa-battery-quarter',
            'fa-battery-three-quarters',
            'fa-bed',
            'fa-beer',
            'fa-bell',
            'fa-bell-slash',
            'fa-bicycle',
            'fa-binoculars',
            'fa-birthday-cake',
            'fa-blind',
            'fa-bold',
            'fa-bolt',
            'fa-bomb',
            'fa-book',
            'fa-bookmark',
            'fa-braille',
            'fa-briefcase',
            'fa-bug',
            'fa-building',
            'fa-bullhorn',
            'fa-bullseye',
            'fa-bus',
            'fa-calculator',
            'fa-calendar',
            'fa-camera',
            'fa-camera-retro',
            'fa-car',
            'fa-caret-down',
            'fa-caret-left',
            'fa-caret-right',
            'fa-caret-up',
            'fa-cart-arrow-down',
            'fa-cart-plus',
            'fa-certificate',
            'fa-check',
            'fa-check-circle',
            'fa-check-square',
            'fa-chevron-circle-down',
            'fa-chevron-circle-left',
            'fa-chevron-circle-right',
            'fa-chevron-circle-up',
            'fa-chevron-down',
            'fa-chevron-left',
            'fa-chevron-right',
            'fa-chevron-up',
            'fa-child',
            'fa-circle',
            'fa-clipboard',
            'fa-clone',
            'fa-cloud',
            'fa-code',
            'fa-coffee',
            'fa-cog',
            'fa-cogs',
            'fa-columns',
            'fa-comment',
            'fa-comments',
            'fa-compass',
            'fa-compress',
            'fa-copy',
            'fa-copyright',
            'fa-credit-card',
            'fa-crop',
            'fa-crosshairs',
            'fa-cube',
            'fa-cubes',
            'fa-cut',
            'fa-database',
            'fa-deaf',
            'fa-desktop',
            'fa-download',
            'fa-edit',
            'fa-eject',
            'fa-ellipsis-h',
            'fa-ellipsis-v',
            'fa-envelope',
            'fa-envelope-open',
            'fa-envelope-square',
            'fa-eraser',
            'fa-exclamation',
            'fa-exclamation-circle',
            'fa-exclamation-triangle',
            'fa-expand',
            'fa-eye',
            'fa-eye-slash',
            'fa-fast-backward',
            'fa-fast-forward',
            'fa-fax',
            'fa-female',
            'fa-fighter-jet',
            'fa-file',
            'fa-film',
            'fa-filter',
            'fa-fire',
            'fa-fire-extinguisher',
            'fa-flag',
            'fa-flag-checkered',
            'fa-flask',
            'fa-folder',
            'fa-folder-open',
            'fa-font',
            'fa-forward',
            'fa-gamepad',
            'fa-gavel',
            'fa-genderless',
            'fa-gift',
            'fa-globe',
            'fa-graduation-cap',
            'fa-h-square',
            'fa-hashtag',
            'fa-headphones',
            'fa-heart',
            'fa-heartbeat',
            'fa-history',
            'fa-home',
            'fa-hotel',
            'fa-hourglass',
            'fa-hourglass-end',
            'fa-hourglass-half',
            'fa-hourglass-start',
            'fa-i-cursor',
            'fa-id-badge',
            'fa-id-card',
            'fa-image',
            'fa-inbox',
            'fa-indent',
            'fa-industry',
            'fa-info',
            'fa-info-circle',
            'fa-italic',
            'fa-key',
            'fa-language',
            'fa-laptop',
            'fa-leaf',
            'fa-life-ring',
            'fa-link',
            'fa-list',
            'fa-list-alt',
            'fa-list-ol',
            'fa-list-ul',
            'fa-location-arrow',
            'fa-lock',
            'fa-low-vision',
            'fa-magic',
            'fa-magnet',
            'fa-male',
            'fa-map',
            'fa-map-marker',
            'fa-map-pin',
            'fa-map-signs',
            'fa-mars',
            'fa-mars-double',
            'fa-mars-stroke',
            'fa-mars-stroke-h',
            'fa-mars-stroke-v',
            'fa-medkit',
            'fa-mercury',
            'fa-microchip',
            'fa-microphone',
            'fa-microphone-slash',
            'fa-minus',
            'fa-minus-circle',
            'fa-minus-square',
            'fa-mobile',
            'fa-motorcycle',
            'fa-mouse-pointer',
            'fa-music',
            'fa-neuter',
            'fa-object-group',
            'fa-object-ungroup',
            'fa-outdent',
            'fa-paint-brush',
            'fa-paper-plane',
            'fa-paperclip',
            'fa-paragraph',
            'fa-paste',
            'fa-pause',
            'fa-pause-circle',
            'fa-paw',
            'fa-percent',
            'fa-phone',
            'fa-phone-square',
            'fa-plane',
            'fa-play',
            'fa-play-circle',
            'fa-plug',
            'fa-plus',
            'fa-plus-circle',
            'fa-plus-square',
            'fa-podcast',
            'fa-power-off',
            'fa-print',
            'fa-puzzle-piece',
            'fa-qrcode',
            'fa-question',
            'fa-question-circle',
            'fa-quote-left',
            'fa-quote-right',
            'fa-random',
            'fa-recycle',
            'fa-registered',
            'fa-reply',
            'fa-reply-all',
            'fa-retweet',
            'fa-road',
            'fa-rocket',
            'fa-rss',
            'fa-rss-square',
            'fa-save',
            'fa-search',
            'fa-search-minus',
            'fa-search-plus',
            'fa-server',
            'fa-share',
            'fa-share-alt',
            'fa-share-alt-square',
            'fa-share-square',
            'fa-ship',
            'fa-shopping-bag',
            'fa-shopping-basket',
            'fa-shopping-cart',
            'fa-shower',
            'fa-sign-language',
            'fa-signal',
            'fa-sitemap',
            'fa-sort',
            'fa-sort-down',
            'fa-sort-up',
            'fa-space-shuttle',
            'fa-spinner',
            'fa-square',
            'fa-star',
            'fa-star-half',
            'fa-step-backward',
            'fa-step-forward',
            'fa-stethoscope',
            'fa-sticky-note',
            'fa-stop',
            'fa-stop-circle',
            'fa-street-view',
            'fa-strikethrough',
            'fa-subscript',
            'fa-subway',
            'fa-suitcase',
            'fa-superscript',
            'fa-table',
            'fa-tablet',
            'fa-tag',
            'fa-tags',
            'fa-tasks',
            'fa-taxi',
            'fa-terminal',
            'fa-text-height',
            'fa-text-width',
            'fa-th',
            'fa-th-large',
            'fa-th-list',
            'fa-thermometer',
            'fa-thermometer-empty',
            'fa-thermometer-full',
            'fa-thermometer-half',
            'fa-thermometer-quarter',
            'fa-thermometer-three-quarters',
            'fa-thumbs-down',
            'fa-thumbs-up',
            'fa-times',
            'fa-times-circle',
            'fa-tint',
            'fa-toggle-off',
            'fa-toggle-on',
            'fa-trademark',
            'fa-train',
            'fa-transgender',
            'fa-transgender-alt',
            'fa-trash',
            'fa-tree',
            'fa-trophy',
            'fa-truck',
            'fa-tty',
            'fa-tv',
            'fa-umbrella',
            'fa-underline',
            'fa-undo',
            'fa-universal-access',
            'fa-university',
            'fa-unlink',
            'fa-unlock',
            'fa-unlock-alt',
            'fa-upload',
            'fa-user',
            'fa-user-circle',
            'fa-user-md',
            'fa-user-plus',
            'fa-user-secret',
            'fa-user-times',
            'fa-users',
            'fa-venus',
            'fa-venus-double',
            'fa-venus-mars',
            'fa-volume-down',
            'fa-volume-off',
            'fa-volume-up',
            'fa-wheelchair',
            'fa-wifi',
            'fa-window-close',
            'fa-window-maximize',
            'fa-window-minimize',
            'fa-window-restore',
            'fa-wrench',
        );

        return $fonts;
    }


    public function getInformacionPlanDesglose($planId, $planIndicadorRepository, $planIndicadorRepository1, $periodo)
    {
        $final = [];
        $objGral = [];
        $objGralId = [];
        $objEsp = [];
        $objEspId = [];
        $indic = [];
        $indicId = [];

        $planValor = [];
        $formaEvaluacion = [];
        $plan = [];
        /* @var $value PlanIndicador */
        foreach ($planIndicadorRepository as $value) {
            if (!in_array($value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId(), $objGralId)) {
                $objGral[] = $value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral();
                $objGralId[] = $value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId();
            }

            if (!in_array($value->getIndicador()->getObjetivoEspecifico()->getId(), $objEspId)) {
                $objEsp[$value->getIndicador()->getObjetivoEspecifico()->getObjetivoGeneral()->getId()][] = $value->getIndicador()->getObjetivoEspecifico();
                $objEspId[] = $value->getIndicador()->getObjetivoEspecifico()->getId();
            }

            if (!in_array($value->getIndicador()->getId(), $indicId)) {
                $indic[$value->getIndicador()->getObjetivoEspecifico()->getId()][] = $value->getIndicador();
                $indicId[] = $value->getIndicador()->getId();
            }
            $planValor[$value->getIndicador()->getId()] = $value->getPlanValor();
        }

        foreach ($objGral as $value) {
            $item = null;
            $item['idObjetivoGeneral'] = $value->getId();
            $item['nombreObjetivoGeneral'] = $value->getNombre();

            $objEspec = null;
            foreach ($objEsp[$value->getId()] as $value2) {
                $itemOE['idObjetivoEspecifico'] = $value2->getId();
                $itemOE['nombreObjetivoEspecifico'] = $value2->getNombre();

                $indicadores = null;
                foreach ($indic[$value2->getId()] as $value3) {
                    $itemI['idIndicador'] = $value3->getId();
                    $itemI['codigoIndicador'] = $value3->getCodigo();
                    $itemI['nombreIndicador'] = $value3->getNombre();
                    $itemI['unidadMedida'] = $value3->getUnidadMedida()->getSiglas();
                    $itemI['valorPlan'] = $planValor[$value3->getId()];
                    $itemI['periodo'] = $periodo;
                    $itemI['planesMensual'] = $this->getPlanPorIndicador($value3->getId(), $planIndicadorRepository1, $planId);
                    $itemI['realesMensual'] = $this->getRealPorIndicador($value3->getId(), $planIndicadorRepository1, $planId);
                    $itemI['evaluacion'] = $this->getEvaluacionPorIndicador($value3->getId(), $planIndicadorRepository1, $planId);
                    $indicadores[] = $itemI;
                }
                $itemOE['indicadores'] = $indicadores;
                $objEspec[] = $itemOE;
            }
            $item['objetivosEspecificos'] = $objEspec;
            $final[] = $item;
        }
//        pr($final);
        return $final;
    }

    public function getPlanPorIndicador($indicadorId, $planIndicadorRepository, $planId)
    {
        $final = [];
        $planes = $planIndicadorRepository->getPlanesMensuales($indicadorId, $planId);
        foreach ($planes as $value) {
            $final[explode('/', $value['periodo'])[0]] = $value['planValor'];
        }
        return $final;
    }

    public function getRealPorIndicador($indicadorId, $planIndicadorRepository, $planId)
    {
        $final = [];
        $planes = $planIndicadorRepository->getPlanesMensuales($indicadorId, $planId);
        foreach ($planes as $value) {
            $final[explode('/', $value['periodo'])[0]] = $value['planReal'];
        }
        return $final;
    }

    public function getEvaluacionPorIndicador($indicadorId, $planIndicadorRepository, $planId)
    {
        $final = [];
        $planes = $planIndicadorRepository->getPlanesMensuales($indicadorId, $planId);
        foreach ($planes as $value) {
            $final[explode('/', $value['periodo'])[0]] = $value['evaluacionTxt'];
        }
        return $final;
    }

    public function getDatosPersonaDadoIdUsuario($idUsuario)
    {
        return $this->em->getRepository(Persona::class)->findOneBy(['usuario' => $idUsuario]);
    }

    public function procesarRolesUsuarioAutenticado($idUsuario)
    {
        $usuario = $this->em->getRepository(User::class)->find($idUsuario);
        $arrayEstructuras = [];
        /* @var $value Rol */
        foreach ($usuario->getUserRoles() as $value) {
            $rolEstructura = $this->em->getRepository(RolEstructura::class)->findBy(['rol' => $value->getId()]);
            if (is_array($rolEstructura)) {
                foreach ($rolEstructura as $value2) {
                    if (!in_array($value2->getEstructura()->getId(), $arrayEstructuras)) {
                        $arrayEstructuras[] = $value2->getEstructura()->getId();
                    }
                }
            }
        }
        return $arrayEstructuras;
    }

    public function getArrayPeriodp($evaluacion, $anno)
    {
        $arrayPeriodo = [];
        foreach ($evaluacion as $value) {
            $arrayPeriodo[] = $value . '/' . $anno;
        }
        return $arrayPeriodo;
    }

    public function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

    public function arrayFiffAssocRecursive($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayFiffAssocRecursive($value, $array2[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($array2[$key]) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }

    public function procesarTraza($anterior, $actual)
    {
        $arrayCorrecciones = [
            'Ubicacion' => 'Ubicación',
            'Telefono' => 'Teléfono',
            'Descripcion' => 'Descripción',
            'Periodo' => 'Período',
            'Codigo' => 'Código',
            'Direccion' => 'Dirección',
            'Email' => 'Correo',
        ];
        $arrayNiveles = ['id', 'fechaActivacion', 'actualizado', 'creado', 'activo'];
        $final = [];
        if (is_array($anterior)) {
            foreach ($anterior as $key => $value) {
                if (is_array($anterior[$key])) {
                    foreach ($anterior[$key] as $key1 => $value1) {
                        if ($anterior[$key][$key1] != $actual[$key][$key1]) {
                            if (!in_array($key1, $arrayNiveles)) {
                                $final[ucfirst($key) . ' / ' . (isset($arrayCorrecciones[ucfirst($key1)]) ? $arrayCorrecciones[ucfirst($key1)] : ucfirst($key1))] = [
                                    'anterior' => $value1,
                                    'actual' => $actual[$key][$key1]
                                ];
                            }
                        }
                    }
                } else {
                    if ($anterior[$key] != $actual[$key]) {
                        if (!in_array($key, $arrayNiveles)) {
                            $final[(isset($arrayCorrecciones[ucfirst($key)]) ? $arrayCorrecciones[ucfirst($key)] : ucfirst($key))] = [
                                'anterior' => $value,
                                'actual' => $actual[$key]
                            ];
                        }
                    }
                }
            }
        }

        if (!is_array($anterior)) {
            $nuevoActual = [];

            foreach ($actual as $key => $value) {
                if (!is_array($actual[$key])) {
                    if (!in_array($key, $arrayNiveles)) {
                        if (!empty($value)) {
                            $nuevoActual[(isset($arrayCorrecciones[ucfirst($key)]) ? $arrayCorrecciones[ucfirst($key)] : ucfirst($key))]['actual'] = $value;
                        }
                    }
                } else {
                    foreach ($actual[$key] as $key1 => $value1) {
                        if (!in_array($key1, $arrayNiveles)) {
                            if (!empty($value1)) {
                                if(!is_array($value1)){
                                    $nuevoActual[ucfirst($key) . ' / ' . (isset($arrayCorrecciones[ucfirst($key1)]) ? $arrayCorrecciones[ucfirst($key1)] : ucfirst($key1))]['actual'] = $value1;
                                }
                            }
                        }
                    }
                }
            }
            $final = $nuevoActual;
        }
        return $final;
    }


}
