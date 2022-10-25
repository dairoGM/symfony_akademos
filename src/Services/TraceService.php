<?php

namespace App\Services;

use App\Entity\Personal\Persona;
use App\Entity\Traza\Accion;
use App\Entity\Traza\ConfiguracionTraza;
use App\Entity\Traza\Objeto;
use App\Entity\Traza\TipoTraza;
use App\Entity\Traza\Traza;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Service("traceService")
 */
class TraceService
{

    private $baseUrl;
    private $em;
    private $serializer;
    private $request;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $this->em = $em;
        $this->serializer = $serializer;
        $this->request = $requestStack->getCurrentRequest();

    }


    /**
     * @param $accion
     * @param $objeto
     * @param $entidadNueva
     * @param int $tipoTraza
     * @return bool
     */
    public function registrar($accion, $objeto, $dataAnterior, $nuevaData, $tipoTraza = 2)
    {
//        pr($serializer->deserialize($va, CategoriaDocente::class,'json')->getNombre());
        try {
            $session = new Session();
            $idPersona = $session->get('id_persona_usuario_autenticado');
            $configuracionTraza = $this->em->getRepository(ConfiguracionTraza::class)->findOneBy(['persona' => $idPersona]);

            if ($configuracionTraza instanceof ConfiguracionTraza && method_exists($configuracionTraza, 'getActivo') && $configuracionTraza->getActivo()) {
                $nuevo = new Traza();
                $nuevo->setDataAnterior($this->serializer->serialize($dataAnterior, 'json') );
                $nuevo->setData($this->serializer->serialize($nuevaData, 'json'));
                $nuevo->setAccion($this->em->getRepository(Accion::class)->find($accion));
                $nuevo->setTipoTraza($this->em->getRepository(TipoTraza::class)->find($tipoTraza));
                $nuevo->setObjeto($this->em->getRepository(Objeto::class)->find($objeto));
                $nuevo->setPersona($this->em->getRepository(Persona::class)->find($this->request->getSession()->get('id_persona_usuario_autenticado')));
                $nuevo->setIp($_SERVER['REMOTE_ADDR']);
                $nuevo->setNavegador($this->detect()['browser']);
                $nuevo->setSistemaOperativo($this->detect()['os']);
                $this->em->persist($nuevo);
                $this->em->flush();
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    private function detect()
    {
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
        $os = array("WIN", "MAC", "LINUX");
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
        foreach ($browser as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if ($s) {
                $info['browser'] = ucfirst(strtolower($parent)) . ' ' . $version;
            }
        }
        foreach ($os as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
                $info['os'] = ucfirst(strtolower($val));
        }
        return $info;

    }
}
