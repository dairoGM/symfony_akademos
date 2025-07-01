<?php

namespace App\Services;

use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Personal\Persona;
use App\Entity\Personal\Sexo;
use App\Entity\Planificacion\PlanIndicador;
use App\Entity\Pregrado\EstadoProgramaAcademico;
use App\Entity\Pregrado\HistoricoEstadoProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Entity\RRHH\AE3;
use App\Entity\Security\Rol;
use App\Entity\Security\RolEstructura;
use App\Entity\Security\User;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Service("agent.utils")
 */
class Utils
{

    private $base_url;
    private $em;
    private $ib_api_ri_url;
    private $api_fuc_url;
    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;


    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port)
    {
        $this->base_url = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $this->em = $em;
        $this->ib_api_ri_url = $container->get('IB_API_RI_URL');
        $this->api_fuc_url = $container->get('API_FUC');

        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $connectionParams = array(
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => $this->port,
            'driver' => $this->driver,
            'charset' => 'UTF8'
        );
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function guardarHistoricoEstadoProgramaAcademico($solicitudProgramaAcademico, $estado, $cursoAcademico = null, $dictamenAprobacion = null)
    {
        ;
        $historico = new HistoricoEstadoProgramaAcademico();
        $historico->setSolicitudProgramaAcademico($this->em->getRepository(SolicitudProgramaAcademico::class)->find($solicitudProgramaAcademico));
        $historico->setEstadoProgramaAcademico($this->em->getRepository(EstadoProgramaAcademico::class)->find($estado));
        if (!empty($cursoAcademico)) {
            $historico->setCursoAcademico($cursoAcademico);
        }
        if (!empty($dictamenAprobacion)) {
            $historico->setDictamenAprobacion($dictamenAprobacion);
        }
        $this->em->persist($historico);
        $this->em->flush();
    }

    public function getToken($email, $password)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('POST', $this->base_url . "/api/login_check", [
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

    public function procesarEstructuraV2($nomenclador)
    {
        $result = [];
        if (is_array($nomenclador)) {
            foreach ($nomenclador as $value) {
                $element['id'] = $value->getId();
                $siglas = $value->getEstructura() && method_exists($value->getEstructura(), 'getSiglas')
                    ? $value->getEstructura()->getSiglas()
                    : null;
                $element['nombre'] = !empty($siglas) ? "(" . $value->getEstructura()->getSiglas() . ") " . $value->getNombre() : $value->getNombre();
                $result[] = $element;
            }
        }
        return $result;
    }

    public function procesarEstructura($nomenclador)
    {
        $element['id'] = $nomenclador->getId();
        $element['nombre'] = $nomenclador->getNombre();
        $element['siglas'] = $nomenclador->getSiglas();
        $element['telefono'] = $nomenclador->getTelefono();
        $element['correo'] = $nomenclador->getEmail();
        $element['direccion'] = $nomenclador->getDireccion();
        $element['ubicacion'] = $nomenclador->getUbicacion();
        return $element;
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


    public function getDatosPersonaDadoIdUsuario($idUsuario)
    {
        return $this->em->getRepository(Persona::class)->findOneBy(['usuario' => $idUsuario]);
    }

    public function procesarRolesUsuarioAutenticado($idUsuario)
    {
        /* @var $usuario User */
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

            if (is_array($actual)) {
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
                                    if (!is_array($value1)) {
                                        $nuevoActual[ucfirst($key) . ' / ' . (isset($arrayCorrecciones[ucfirst($key1)]) ? $arrayCorrecciones[ucfirst($key1)] : ucfirst($key1))]['actual'] = $value1;
                                    }
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


//    Ejemplo para consumirlos
    public function getTokenRelacionesInternacionales()
    {
        try {
            $curl = curl_init();

            $arrayDataPage['user'] = "drgil";
            $arrayDataPage['password'] = "dasdasd";

            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->ib_api_ri_url}/api/services/getAccessToken",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($arrayDataPage)
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $token = json_decode($response, true);
            return isset($token['data']) ? $token['data'] : null;
        } catch (Exception $e) {
            $this->services_lib->printJson(403, "Invalid token", null);
        }
    }

    public function obtenerMecanismosColaboracion($codigo)
    {
        try {
            $token = $this->getTokenRelacionesInternacionales();
            $curl = curl_init();
            $arrayDataPage['codigo_mes'] = $codigo;
            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->ib_api_ri_url}/api/services/ri_listar_mecanismos",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_POSTFIELDS => json_encode($arrayDataPage),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    sprintf("Authorization: Bearer %s", $token),
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            return isset($result['data']) ? $result['data'] : [];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function obtenerProgramasColaboracion($codigo)
    {
        try {
            $token = $this->getTokenRelacionesInternacionales();
            $curl = curl_init();
            $arrayDataPage['codigo_mes'] = $codigo;
            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->ib_api_ri_url}/api/services/ri_listar_programas",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_POSTFIELDS => json_encode($arrayDataPage),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    sprintf("Authorization: Bearer %s", $token),
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            return isset($result['data']) ? $result['data'] : [];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function obtenerProyectos($codigo)
    {
        try {
            $token = $this->getTokenRelacionesInternacionales();
            $curl = curl_init();
            $arrayDataPage['codigo_mes'] = $codigo;
            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->ib_api_ri_url}/api/services/ri_listar_proyectos",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_POSTFIELDS => json_encode($arrayDataPage),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    sprintf("Authorization: Bearer %s", $token),
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            return isset($result['data']) ? $result['data'] : [];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function obtenerMembresias($codigo)
    {
        try {
            $token = $this->getTokenRelacionesInternacionales();
            $curl = curl_init();
            $arrayDataPage['codigo_mes'] = $codigo;
            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->ib_api_ri_url}/api/services/ri_listar_membresias",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_POSTFIELDS => json_encode($arrayDataPage),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    sprintf("Authorization: Bearer %s", $token),
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response, true)['data'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function actualizarCategoriaResponsabilidadDri($catResponsabilidadEntity, $elimiar = false)
    {
        try {
            $nombre = $catResponsabilidadEntity->getNombre();
            $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_ncategoria_responsabilidad WHERE nombre_categoria_responsabilidad = '$nombre'");

            $data['nombre_categoria_responsabilidad'] = $nombre;
            $data['descripcion'] = $catResponsabilidadEntity->getDescripcion();
            $data['activo'] = !empty($catResponsabilidadEntity->getActivo()) ? $catResponsabilidadEntity->getActivo() : 0;
            if (!isset($existe[0])) {
                $this->connection->insert('sq_estructura_composicion.tb_ncategoria_responsabilidad', $data);
            } else {
                $this->connection->update('sq_estructura_composicion.tb_ncategoria_responsabilidad', $data, ['id_categoria_responsabilidad' => $existe[0]['id_categoria_responsabilidad']]);
            }

            if ($elimiar && isset($existe[0])) {
                $this->connection->delete('sq_estructura_composicion.tb_ncategoria_responsabilidad', ['nombre_categoria_responsabilidad' => $existe[0]['nombre_categoria_responsabilidad']]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function actualizarCategoriaEstructuraDri($catEstructuraEntity, $elimiar = false)
    {
        try {
            $nombre = $catEstructuraEntity->getNombre();
            $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_ncategoria_estructura WHERE nombre_categoria_estructura = '$nombre'");

            $data['nombre_categoria_estructura'] = $nombre;
            $data['descripcion_categoria_estructura'] = $catEstructuraEntity->getDescripcion();
            $data['color'] = $catEstructuraEntity->getColor();
            $data['activo'] = !empty($catEstructuraEntity->getActivo()) ? $catEstructuraEntity->getActivo() : 0;
            if (!isset($existe[0])) {
                $this->connection->insert('sq_estructura_composicion.tb_ncategoria_estructura', $data);
            } else {
                $this->connection->update('sq_estructura_composicion.tb_ncategoria_estructura', $data, ['id_categoria_estructura' => $existe[0]['id_categoria_estructura']]);
            }

            if ($elimiar && isset($existe[0])) {
                $this->connection->delete('sq_estructura_composicion.tb_ncategoria_estructura', ['nombre_categoria_estructura' => $existe[0]['nombre_categoria_estructura']]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function actualizarProfesionDri($profesionEntity, $elimiar = false)
    {
        try {
            $nombre = $profesionEntity->getNombre();
            $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_gestion_personal.tb_nprofesion WHERE nombre_profesion = '$nombre'");

            $data['nombre_profesion'] = $nombre;
            $data['descripcion'] = $profesionEntity->getDescripcion();
            $data['activo'] = !empty($profesionEntity->getActivo()) ? $profesionEntity->getActivo() : 0;
            if (!isset($existe[0])) {
                $this->connection->insert('sq_gestion_personal.tb_nprofesion', $data);
            } else {
                $this->connection->update('sq_gestion_personal.tb_nprofesion', $data, ['id_profesion' => $existe[0]['id_profesion']]);
            }

            if ($elimiar && isset($existe[0])) {
                $this->connection->delete('sq_gestion_personal.tb_nprofesion', ['nombre_profesion' => $existe[0]['nombre_profesion']]);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getFechasEntre($fechaInicio, $fechaFin)
    {
        $fechas = array();
        $fechaInicio = strtotime($fechaInicio);
        $fechaFin = strtotime($fechaFin);

        if ($fechaInicio === false || $fechaFin === false || $fechaInicio > $fechaFin) {
            return $fechas; // Si las fechas son inválidas o están en orden incorrecto, retornar array vacío
        }

        // Recorrer cada día entre las fechas y formatearlo como Y-m-d
        while ($fechaInicio <= $fechaFin) {
            $fechas[] = date('Y-m-d', $fechaInicio);
            $fechaInicio = strtotime('+1 day', $fechaInicio);
        }

        return $fechas;
    }

    public function autenticarFUC()
    {
        $clientId = "infomesclientid";
        $clientSecrect = "*&*info_mes_&2024&client_secrect^";

        $authcode = $clientId . ':' . $clientSecrect;

        $params = array(
            "username" => "dirinfo@mes.gob.cu",
            "password" => "*&*info_mes_&2024&user^",
            "grant_type" => "password");
        $apiFuc = $this->api_fuc_url;
        $curl = curl_init("$apiFuc/identity/oauth/token");
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, 'Content-Type: application/x-www-form-urlencoded');
        curl_setopt($curl, CURLOPT_USERPWD, $authcode);


// Remove comment if you have a setup that causes ssl validation to fail
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $postData = "";

//This is needed to properly form post the credentials object
        foreach ($params as $k => $v) {
            $postData .= $k . '=' . urlencode($v) . '&';
        }

        $postData = rtrim($postData, '&');

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        $json_response = curl_exec($curl);
        $data = json_decode($json_response, true);
        return $data['access_token'] ?? null;
    }


    public function getPersonaFuc($ci)
    {
        $token = $this->autenticarFUC();
        $curl = curl_init();
        $apiFuc = $this->api_fuc_url;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$apiFuc/fuc/person/$ci",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response, true);
        return $data[0] ?? false;

    }

    public function getPersonaFotoFuc($idPersonaFuc)
    {
        $token = $this->autenticarFUC();
        $curl = curl_init();
        $apiFuc = $this->api_fuc_url;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$apiFuc/fuc/person/$idPersonaFuc/photo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }

    public function asignarDatosFuc($persona, $datosFuc)
    {
        try {
            $persona->setPrimerNombre($datosFuc['primer_nombre']);
            $persona->setSegundoNombre($datosFuc['segundo_nombre']);
            $persona->setPrimerApellido($datosFuc['primer_apellido']);
            $persona->setSegundoApellido($datosFuc['segundo_apellido']);
            $persona->setCarnetIdentidad($datosFuc['identidad_numero']);
            $persona->setDireccion($datosFuc['direccion']);
            if ($datosFuc['sexo'] == 'M') {
                $persona->setSexo($this->em->getRepository(Sexo::class)->find(3));
            }
            if ($datosFuc['sexo'] == 'F') {
                $persona->setSexo($this->em->getRepository(Sexo::class)->find(4));
            }
            $temp = explode('-', $datosFuc['nacimiento_fecha']);

            $datosFuc['nacimiento_fecha'] = $temp[2] . "/" . $temp[1] . "/" . $temp[0];

            $fechaNacimiento = \DateTime::createFromFormat('d/m/Y', $datosFuc['nacimiento_fecha']);

            $persona->setFechaNacimiento($fechaNacimiento);
            $provincia = $this->em->getRepository(Provincia::class)->findOneBy(['nombre' => $datosFuc['provincia_residencia'], 'activo' => 1]);
            $persona->setProvincia($provincia);

            $municipio = $this->em->getRepository(Municipio::class)->findOneBy(['nombre' => $datosFuc['municipio_residencia'], 'activo' => 1, 'provincia' => $provincia->getId()]);
            $persona->setMunicipio($municipio);
        } catch (\Exception $exception) {

        }
    }


    public function getMesesNombres(): array
    {
        return [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic'
        ];
    }

    public function organizarDatosPorMes(array $registros): array
    {
        $datos = [
            'meses' => [],
            'totalesPorMes' => []
        ];

        foreach ($registros as $registro) {
            $key = $registro->getMes() . '-' . $registro->getAnno();

            if (!isset($datos['meses'][$key])) {
                $datos['meses'][$key] = [
                    'mes' => $registro->getMes(),
                    'anno' => $registro->getAnno(),
                    'entidad' => $registro->getEntidad(),
                    'valores' => []
                ];
                $datos['totalesPorMes'][$key] = 0;
            }

            // Sumar todos los valores para el total por mes
            $totalMes = 0;
            foreach ($this->getFieldsConfig() as $field) {
                $value = $registro->{'get' . ucfirst($field['field'])}() ?? 0;
                @$datos['meses'][$key]['valores'][$field['field']] += $value;
                $totalMes += $value;
            }
            $datos['totalesPorMes'][$key] += $totalMes;
        }

        return $datos;
    }

    public function getFieldsConfig(): array
    {
        return [
            ['label' => '1.- Total plantilla aprobada', 'field' => 'totalPlantillaAprobada'],
            ['label' => '2.- Total plantilla cubierta', 'field' => 'totalPlantillaCubierta'],
            ['label' => '3.- Total general de contratos (4+7+14)', 'field' => 'totalGeneralContratos'],
            ['label' => '4.- Total contratos profesores tiempo determinado', 'field' => 'totalContratosProfesoresTiempoDeterminado'],
            ['label' => '5.- Profesores tiempo completo', 'field' => 'profesoresTiempoCompleto'],
            ['label' => '6.- Total de contratos no docentes (7+14)', 'field' => 'totalContratosNoDocentes'],
            ['label' => '7.- Contratos no docentes con respaldo de plazas (8 a 13)', 'field' => 'contratosNoDocentesConRespaldo'],
            ['label' => '8.- Contratos por sustitución', 'field' => 'contratosPorSustitucion'],
            ['label' => '9.- Período de prueba', 'field' => 'periodoPrueba'],
            ['label' => '10.- Serenos, auxiliares, limpieza', 'field' => 'serenosAuxiliaresLimpieza'],
            ['label' => '11.- Labores agrícolas', 'field' => 'laboresAgricolas'],
            ['label' => '12.- Jubilados', 'field' => 'jubilados'],
            ['label' => '13.- Otros con respaldo', 'field' => 'otrosConRespaldo'],
            ['label' => '14.- Contratos no docentes sin respaldo de plazas (15 a 19)', 'field' => 'contratosNoDocentesSinRespaldo'],
            ['label' => '15.- Serenos, auxiliares, limpieza sin respaldo', 'field' => 'serenosAuxiliaresLimpiezaSinRespaldo'],
            ['label' => '16.- Labores Agrícolas sin respaldo', 'field' => 'laboresAgricolasSinRespaldo'],
            ['label' => '17.- Jubilados sin respaldo', 'field' => 'jubiladosSinRespaldo'],
            ['label' => '18.- Ejecución de obra', 'field' => 'ejecucionObra'],
            ['label' => '19.- Otros sin respaldo', 'field' => 'otrosSinRespaldo'],
            ['label' => '20.- Reserva científica en preparación', 'field' => 'reservaCientificaPreparacion'],
            ['label' => '21.- Recién graduados en preparación (Nivel Sup.)', 'field' => 'recienGraduadosPreparacion'],
            ['label' => '22.- Reserva dirección provincial de trabajo', 'field' => 'reservaDireccionProvincialTrabajo'],
            ['label' => '23.- Técnicos medios en preparación', 'field' => 'tecnicosMediosPreparacion'],
            ['label' => '24.- Total estudiantes universidad contratados', 'field' => 'totalEstudiantesUniversidadContratados'],
            ['label' => '25.- Estudiantes auxiliares técnicos de docencia', 'field' => 'estudiantesAuxiliaresTecnicosDocencia'],
            ['label' => '26.- Estudiantes en cargos no docentes', 'field' => 'estudiantesCargosNoDocentes']
        ];
    }

    public function serializeAe3(AE3 $ae3): array
    {
        return [
            // Total Cuadros
            'totalCuadrosTotalCubierta' => $ae3->getTotalCuadrosTotalCubierta(),
            'totalCuadrosDeEllosFem' => $ae3->getTotalCuadrosDeEllosFem(),
            'totalCuadrosJovenesTotal' => $ae3->getTotalCuadrosJovenesTotal(),
            'totalCuadrosJovenesFem' => $ae3->getTotalCuadrosJovenesFem(),
            'totalCuadrosFem' => $ae3->getTotalCuadrosFem(),
            'totalCuadrosPt' => $ae3->getTotalCuadrosPt(),
            'totalCuadrosPa' => $ae3->getTotalCuadrosPa(),
            'totalCuadrosAs' => $ae3->getTotalCuadrosAs(),
            'totalCuadrosI' => $ae3->getTotalCuadrosI(),
            'totalCuadrosIt' => $ae3->getTotalCuadrosIt(),
            'totalCuadrosIa' => $ae3->getTotalCuadrosIa(),
            'totalCuadrosIag' => $ae3->getTotalCuadrosIag(),
            'totalCuadrosAi' => $ae3->getTotalCuadrosAi(),
            'totalCuadrosAuxTecDoc' => $ae3->getTotalCuadrosAuxTecDoc(),
            'totalCuadrosMsc' => $ae3->getTotalCuadrosMsc(),
            'totalCuadrosDr' => $ae3->getTotalCuadrosDr(),

            // Cuadros Docentes
            'cuadrosDocentesTotalCubierta' => $ae3->getCuadrosDocentesTotalCubierta(),
            'cuadrosDocentesDeEllosFem' => $ae3->getCuadrosDocentesDeEllosFem(),
            'cuadrosDocentesJovenesTotal' => $ae3->getCuadrosDocentesJovenesTotal(),
            'cuadrosDocentesJovenesFem' => $ae3->getCuadrosDocentesJovenesFem(),
            'cuadrosDocentesFem' => $ae3->getCuadrosDocentesFem(),
            'cuadrosDocentesPt' => $ae3->getCuadrosDocentesPt(),
            'cuadrosDocentesPa' => $ae3->getCuadrosDocentesPa(),
            'cuadrosDocentesAs' => $ae3->getCuadrosDocentesAs(),
            'cuadrosDocentesI' => $ae3->getCuadrosDocentesI(),
            'cuadrosDocentesIt' => $ae3->getCuadrosDocentesIt(),
            'cuadrosDocentesIa' => $ae3->getCuadrosDocentesIa(),
            'cuadrosDocentesIag' => $ae3->getCuadrosDocentesIag(),
            'cuadrosDocentesAi' => $ae3->getCuadrosDocentesAi(),
            'cuadrosDocentesAuxTecDoc' => $ae3->getCuadrosDocentesAuxTecDoc(),
            'cuadrosDocentesMsc' => $ae3->getCuadrosDocentesMsc(),
            'cuadrosDocentesDr' => $ae3->getCuadrosDocentesDr(),

            // Cuadros Administrativos
            'cuadrosAdministrativosTotalCubierta' => $ae3->getCuadrosAdministrativosTotalCubierta(),
            'cuadrosAdministrativosDeEllosFem' => $ae3->getCuadrosAdministrativosDeEllosFem(),
            'cuadrosAdministrativosJovenesTotal' => $ae3->getCuadrosAdministrativosJovenesTotal(),
            'cuadrosAdministrativosJovenesFem' => $ae3->getCuadrosAdministrativosJovenesFem(),
            'cuadrosAdministrativosFem' => $ae3->getCuadrosAdministrativosFem(),
            'cuadrosAdministrativosPt' => $ae3->getCuadrosAdministrativosPt(),
            'cuadrosAdministrativosPa' => $ae3->getCuadrosAdministrativosPa(),
            'cuadrosAdministrativosAs' => $ae3->getCuadrosAdministrativosAs(),
            'cuadrosAdministrativosI' => $ae3->getCuadrosAdministrativosI(),
            'cuadrosAdministrativosIt' => $ae3->getCuadrosAdministrativosIt(),
            'cuadrosAdministrativosIa' => $ae3->getCuadrosAdministrativosIa(),
            'cuadrosAdministrativosIag' => $ae3->getCuadrosAdministrativosIag(),
            'cuadrosAdministrativosAi' => $ae3->getCuadrosAdministrativosAi(),
            'cuadrosAdministrativosAuxTecDoc' => $ae3->getCuadrosAdministrativosAuxTecDoc(),
            'cuadrosAdministrativosMsc' => $ae3->getCuadrosAdministrativosMsc(),
            'cuadrosAdministrativosDr' => $ae3->getCuadrosAdministrativosDr(),

            // Cuadros Investigación
            'cuadrosInvestigacionTotalCubierta' => $ae3->getCuadrosInvestigacionTotalCubierta(),
            'cuadrosInvestigacionDeEllosFem' => $ae3->getCuadrosInvestigacionDeEllosFem(),
            'cuadrosInvestigacionJovenesTotal' => $ae3->getCuadrosInvestigacionJovenesTotal(),
            'cuadrosInvestigacionJovenesFem' => $ae3->getCuadrosInvestigacionJovenesFem(),
            'cuadrosInvestigacionFem' => $ae3->getCuadrosInvestigacionFem(),
            'cuadrosInvestigacionPt' => $ae3->getCuadrosInvestigacionPt(),
            'cuadrosInvestigacionPa' => $ae3->getCuadrosInvestigacionPa(),
            'cuadrosInvestigacionAs' => $ae3->getCuadrosInvestigacionAs(),
            'cuadrosInvestigacionI' => $ae3->getCuadrosInvestigacionI(),
            'cuadrosInvestigacionIt' => $ae3->getCuadrosInvestigacionIt(),
            'cuadrosInvestigacionIa' => $ae3->getCuadrosInvestigacionIa(),
            'cuadrosInvestigacionIag' => $ae3->getCuadrosInvestigacionIag(),
            'cuadrosInvestigacionAi' => $ae3->getCuadrosInvestigacionAi(),
            'cuadrosInvestigacionAuxTecDoc' => $ae3->getCuadrosInvestigacionAuxTecDoc(),
            'cuadrosInvestigacionMsc' => $ae3->getCuadrosInvestigacionMsc(),
            'cuadrosInvestigacionDr' => $ae3->getCuadrosInvestigacionDr(),

            // Otros Cuadros
            'otrosCuadrosTotalCubierta' => $ae3->getOtrosCuadrosTotalCubierta(),
            'otrosCuadrosDeEllosFem' => $ae3->getOtrosCuadrosDeEllosFem(),
            'otrosCuadrosJovenesTotal' => $ae3->getOtrosCuadrosJovenesTotal(),
            'otrosCuadrosJovenesFem' => $ae3->getOtrosCuadrosJovenesFem(),
            'otrosCuadrosFem' => $ae3->getOtrosCuadrosFem(),
            'otrosCuadrosPt' => $ae3->getOtrosCuadrosPt(),
            'otrosCuadrosPa' => $ae3->getOtrosCuadrosPa(),
            'otrosCuadrosAs' => $ae3->getOtrosCuadrosAs(),
            'otrosCuadrosI' => $ae3->getOtrosCuadrosI(),
            'otrosCuadrosIt' => $ae3->getOtrosCuadrosIt(),
            'otrosCuadrosIa' => $ae3->getOtrosCuadrosIa(),
            'otrosCuadrosIag' => $ae3->getOtrosCuadrosIag(),
            'otrosCuadrosAi' => $ae3->getOtrosCuadrosAi(),
            'otrosCuadrosAuxTecDoc' => $ae3->getOtrosCuadrosAuxTecDoc(),
            'otrosCuadrosMsc' => $ae3->getOtrosCuadrosMsc(),
            'otrosCuadrosDr' => $ae3->getOtrosCuadrosDr(),

            // Total Técnicos
            'totalTecnicosTotalCubierta' => $ae3->getTotalTecnicosTotalCubierta(),
            'totalTecnicosDeEllosFem' => $ae3->getTotalTecnicosDeEllosFem(),
            'totalTecnicosJovenesTotal' => $ae3->getTotalTecnicosJovenesTotal(),
            'totalTecnicosJovenesFem' => $ae3->getTotalTecnicosJovenesFem(),
            'totalTecnicosFem' => $ae3->getTotalTecnicosFem(),
            'totalTecnicosPt' => $ae3->getTotalTecnicosPt(),
            'totalTecnicosPa' => $ae3->getTotalTecnicosPa(),
            'totalTecnicosAs' => $ae3->getTotalTecnicosAs(),
            'totalTecnicosI' => $ae3->getTotalTecnicosI(),
            'totalTecnicosIt' => $ae3->getTotalTecnicosIt(),
            'totalTecnicosIa' => $ae3->getTotalTecnicosIa(),
            'totalTecnicosIag' => $ae3->getTotalTecnicosIag(),
            'totalTecnicosAi' => $ae3->getTotalTecnicosAi(),
            'totalTecnicosAuxTecDoc' => $ae3->getTotalTecnicosAuxTecDoc(),
            'totalTecnicosMsc' => $ae3->getTotalTecnicosMsc(),
            'totalTecnicosDr' => $ae3->getTotalTecnicosDr(),

            // Profesores Tiempo Completo
            'profesoresTiempoCompletoTotalCubierta' => $ae3->getProfesoresTiempoCompletoTotalCubierta(),
            'profesoresTiempoCompletoDeEllosFem' => $ae3->getProfesoresTiempoCompletoDeEllosFem(),
            'profesoresTiempoCompletoJovenesTotal' => $ae3->getProfesoresTiempoCompletoJovenesTotal(),
            'profesoresTiempoCompletoJovenesFem' => $ae3->getProfesoresTiempoCompletoJovenesFem(),
            'profesoresTiempoCompletoFem' => $ae3->getProfesoresTiempoCompletoFem(),
            'profesoresTiempoCompletoPt' => $ae3->getProfesoresTiempoCompletoPt(),
            'profesoresTiempoCompletoPa' => $ae3->getProfesoresTiempoCompletoPa(),
            'profesoresTiempoCompletoAs' => $ae3->getProfesoresTiempoCompletoAs(),
            'profesoresTiempoCompletoI' => $ae3->getProfesoresTiempoCompletoI(),
            'profesoresTiempoCompletoIt' => $ae3->getProfesoresTiempoCompletoIt(),
            'profesoresTiempoCompletoIa' => $ae3->getProfesoresTiempoCompletoIa(),
            'profesoresTiempoCompletoIag' => $ae3->getProfesoresTiempoCompletoIag(),
            'profesoresTiempoCompletoAi' => $ae3->getProfesoresTiempoCompletoAi(),
            'profesoresTiempoCompletoAuxTecDoc' => $ae3->getProfesoresTiempoCompletoAuxTecDoc(),
            'profesoresTiempoCompletoMsc' => $ae3->getProfesoresTiempoCompletoMsc(),
            'profesoresTiempoCompletoDr' => $ae3->getProfesoresTiempoCompletoDr(),

            // Asesores Metodologos
            'asesoresMetodologosTotalCubierta' => $ae3->getAsesoresMetodologosTotalCubierta(),
            'asesoresMetodologosDeEllosFem' => $ae3->getAsesoresMetodologosDeEllosFem(),
            'asesoresMetodologosJovenesTotal' => $ae3->getAsesoresMetodologosJovenesTotal(),
            'asesoresMetodologosJovenesFem' => $ae3->getAsesoresMetodologosJovenesFem(),
            'asesoresMetodologosFem' => $ae3->getAsesoresMetodologosFem(),
            'asesoresMetodologosPt' => $ae3->getAsesoresMetodologosPt(),
            'asesoresMetodologosPa' => $ae3->getAsesoresMetodologosPa(),
            'asesoresMetodologosAs' => $ae3->getAsesoresMetodologosAs(),
            'asesoresMetodologosI' => $ae3->getAsesoresMetodologosI(),
            'asesoresMetodologosIt' => $ae3->getAsesoresMetodologosIt(),
            'asesoresMetodologosIa' => $ae3->getAsesoresMetodologosIa(),
            'asesoresMetodologosIag' => $ae3->getAsesoresMetodologosIag(),
            'asesoresMetodologosAi' => $ae3->getAsesoresMetodologosAi(),
            'asesoresMetodologosAuxTecDoc' => $ae3->getAsesoresMetodologosAuxTecDoc(),
            'asesoresMetodologosMsc' => $ae3->getAsesoresMetodologosMsc(),
            'asesoresMetodologosDr' => $ae3->getAsesoresMetodologosDr(),

            // Investigadores
            'investigadoresTotalCubierta' => $ae3->getInvestigadoresTotalCubierta(),
            'investigadoresDeEllosFem' => $ae3->getInvestigadoresDeEllosFem(),
            'investigadoresJovenesTotal' => $ae3->getInvestigadoresJovenesTotal(),
            'investigadoresJovenesFem' => $ae3->getInvestigadoresJovenesFem(),
            'investigadoresFem' => $ae3->getInvestigadoresFem(),
            'investigadoresPt' => $ae3->getInvestigadoresPt(),
            'investigadoresPa' => $ae3->getInvestigadoresPa(),
            'investigadoresAs' => $ae3->getInvestigadoresAs(),
            'investigadoresI' => $ae3->getInvestigadoresI(),
            'investigadoresIt' => $ae3->getInvestigadoresIt(),
            'investigadoresIa' => $ae3->getInvestigadoresIa(),
            'investigadoresIag' => $ae3->getInvestigadoresIag(),
            'investigadoresAi' => $ae3->getInvestigadoresAi(),
            'investigadoresAuxTecDoc' => $ae3->getInvestigadoresAuxTecDoc(),
            'investigadoresMsc' => $ae3->getInvestigadoresMsc(),
            'investigadoresDr' => $ae3->getInvestigadoresDr(),

            // Otros Técnicos
            'otrosTecnicosTotalCubierta' => $ae3->getOtrosTecnicosTotalCubierta(),
            'otrosTecnicosDeEllosFem' => $ae3->getOtrosTecnicosDeEllosFem(),
            'otrosTecnicosJovenesTotal' => $ae3->getOtrosTecnicosJovenesTotal(),
            'otrosTecnicosJovenesFem' => $ae3->getOtrosTecnicosJovenesFem(),
            'otrosTecnicosFem' => $ae3->getOtrosTecnicosFem(),
            'otrosTecnicosPt' => $ae3->getOtrosTecnicosPt(),
            'otrosTecnicosPa' => $ae3->getOtrosTecnicosPa(),
            'otrosTecnicosAs' => $ae3->getOtrosTecnicosAs(),
            'otrosTecnicosI' => $ae3->getOtrosTecnicosI(),
            'otrosTecnicosIt' => $ae3->getOtrosTecnicosIt(),
            'otrosTecnicosIa' => $ae3->getOtrosTecnicosIa(),
            'otrosTecnicosIag' => $ae3->getOtrosTecnicosIag(),
            'otrosTecnicosAi' => $ae3->getOtrosTecnicosAi(),
            'otrosTecnicosAuxTecDoc' => $ae3->getOtrosTecnicosAuxTecDoc(),
            'otrosTecnicosMsc' => $ae3->getOtrosTecnicosMsc(),
            'otrosTecnicosDr' => $ae3->getOtrosTecnicosDr(),

            // Administrativos
            'administrativosTotalCubierta' => $ae3->getAdministrativosTotalCubierta(),
            'administrativosDeEllosFem' => $ae3->getAdministrativosDeEllosFem(),
            'administrativosJovenesTotal' => $ae3->getAdministrativosJovenesTotal(),
            'administrativosJovenesFem' => $ae3->getAdministrativosJovenesFem(),
            'administrativosFem' => $ae3->getAdministrativosFem(),
            'administrativosPt' => $ae3->getAdministrativosPt(),
            'administrativosPa' => $ae3->getAdministrativosPa(),
            'administrativosAs' => $ae3->getAdministrativosAs(),
            'administrativosI' => $ae3->getAdministrativosI(),
            'administrativosIt' => $ae3->getAdministrativosIt(),
            'administrativosIa' => $ae3->getAdministrativosIa(),
            'administrativosIag' => $ae3->getAdministrativosIag(),
            'administrativosAi' => $ae3->getAdministrativosAi(),
            'administrativosAuxTecDoc' => $ae3->getAdministrativosAuxTecDoc(),
            'administrativosMsc' => $ae3->getAdministrativosMsc(),
            'administrativosDr' => $ae3->getAdministrativosDr(),

            // Servicio
            'servicioTotalCubierta' => $ae3->getServicioTotalCubierta(),
            'servicioDeEllosFem' => $ae3->getServicioDeEllosFem(),
            'servicioJovenesTotal' => $ae3->getServicioJovenesTotal(),
            'servicioJovenesFem' => $ae3->getServicioJovenesFem(),
            'servicioFem' => $ae3->getServicioFem(),
            'servicioPt' => $ae3->getServicioPt(),
            'servicioPa' => $ae3->getServicioPa(),
            'servicioAs' => $ae3->getServicioAs(),
            'servicioI' => $ae3->getServicioI(),
            'servicioIt' => $ae3->getServicioIt(),
            'servicioIa' => $ae3->getServicioIa(),
            'servicioIag' => $ae3->getServicioIag(),
            'servicioAi' => $ae3->getServicioAi(),
            'servicioAuxTecDoc' => $ae3->getServicioAuxTecDoc(),
            'servicioMsc' => $ae3->getServicioMsc(),
            'servicioDr' => $ae3->getServicioDr(),

            // Operarios
            'operariosTotalCubierta' => $ae3->getOperariosTotalCubierta(),
            'operariosDeEllosFem' => $ae3->getOperariosDeEllosFem(),
            'operariosJovenesTotal' => $ae3->getOperariosJovenesTotal(),
            'operariosJovenesFem' => $ae3->getOperariosJovenesFem(),
            'operariosFem' => $ae3->getOperariosFem(),
            'operariosPt' => $ae3->getOperariosPt(),
            'operariosPa' => $ae3->getOperariosPa(),
            'operariosAs' => $ae3->getOperariosAs(),
            'operariosI' => $ae3->getOperariosI(),
            'operariosIt' => $ae3->getOperariosIt(),
            'operariosIa' => $ae3->getOperariosIa(),
            'operariosIag' => $ae3->getOperariosIag(),
            'operariosAi' => $ae3->getOperariosAi(),
            'operariosAuxTecDoc' => $ae3->getOperariosAuxTecDoc(),
            'operariosMsc' => $ae3->getOperariosMsc(),
            'operariosDr' => $ae3->getOperariosDr(),

            // Total
            'totalTotalCubierta' => $ae3->getTotalTotalCubierta(),
            'totalDeEllosFem' => $ae3->getTotalDeEllosFem(),
            'totalJovenesTotal' => $ae3->getTotalJovenesTotal(),
            'totalJovenesFem' => $ae3->getTotalJovenesFem(),
            'totalFem' => $ae3->getTotalFem(),
            'totalPt' => $ae3->getTotalPt(),
            'totalPa' => $ae3->getTotalPa(),
            'totalAs' => $ae3->getTotalAs(),
            'totalI' => $ae3->getTotalI(),
            'totalIt' => $ae3->getTotalIt(),
            'totalIa' => $ae3->getTotalIa(),
            'totalIag' => $ae3->getTotalIag(),
            'totalAi' => $ae3->getTotalAi(),
            'totalAuxTecDoc' => $ae3->getTotalAuxTecDoc(),
            'totalMsc' => $ae3->getTotalMsc(),
            'totalDr' => $ae3->getTotalDr(),

            // Profesores Tipo Parcial
            'profesoresTipoParcialTotalCubierta' => $ae3->getProfesoresTipoParcialTotalCubierta(),
            'profesoresTipoParcialDeEllosFem' => $ae3->getProfesoresTipoParcialDeEllosFem(),
            'profesoresTipoParcialJovenesTotal' => $ae3->getProfesoresTipoParcialJovenesTotal(),
            'profesoresTipoParcialJovenesFem' => $ae3->getProfesoresTipoParcialJovenesFem(),
            'profesoresTipoParcialFem' => $ae3->getProfesoresTipoParcialFem(),
            'profesoresTipoParcialPt' => $ae3->getProfesoresTipoParcialPt(),
            'profesoresTipoParcialPa' => $ae3->getProfesoresTipoParcialPa(),
            'profesoresTipoParcialAs' => $ae3->getProfesoresTipoParcialAs(),
            'profesoresTipoParcialI' => $ae3->getProfesoresTipoParcialI(),
            'profesoresTipoParcialIt' => $ae3->getProfesoresTipoParcialIt(),
            'profesoresTipoParcialIa' => $ae3->getProfesoresTipoParcialIa(),
            'profesoresTipoParcialIag' => $ae3->getProfesoresTipoParcialIag(),
            'profesoresTipoParcialAi' => $ae3->getProfesoresTipoParcialAi(),
            'profesoresTipoParcialAuxTecDoc' => $ae3->getProfesoresTipoParcialAuxTecDoc(),
            'profesoresTipoParcialMsc' => $ae3->getProfesoresTipoParcialMsc(),
            'profesoresTipoParcialDr' => $ae3->getProfesoresTipoParcialDr(),

            // Documento
            'documento' => $ae3->getDocumento()
        ];
    }
}
