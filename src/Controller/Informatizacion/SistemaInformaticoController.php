<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\SistemaInformatico;
use App\Entity\Informatizacion\SistemaInformaticoProceso;
use App\Form\Informatizacion\SistemaInformaticoType;
use App\Repository\Informatizacion\ProcesoRepository;
use App\Repository\Informatizacion\SistemaInformaticoProcesoRepository;
use App\Repository\Informatizacion\SistemaInformaticoRepository;
use App\Repository\Personal\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/sistema_informatico")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_SISTEMA_INFORMATICO")
 */
class SistemaInformaticoController extends AbstractController
{

    /**
     * @Route("/", name="app_sistema_informatico_index", methods={"GET"})
     * @param SistemaInformaticoRepository $sistemaInformaticoRepository
     * @return Response
     */
    public function index(SistemaInformaticoRepository $sistemaInformaticoRepository, SistemaInformaticoProcesoRepository $sistemaInformaticoProcesoRepository)
    {
        $response = [];
        $regisros = $sistemaInformaticoRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']);
        foreach ($regisros as $value) {
            $sistemaProceso = $sistemaInformaticoProcesoRepository->findBy(['sistemaInformatico' => $value->getId()]);
            $asociados = [];
            foreach ($sistemaProceso as $value2) {
                $asociados[] = $value2->getProceso()->getNombre();
            }
            $value->procesos = implode(', ', $asociados);
            $response[] = $value;
        }
        return $this->render('modules/informatizacion/sistemaInformatico/index.html.twig', [
            'registros' => $response,
        ]);
    }

    /**
     * @Route("/registrar", name="app_sistema_informatico_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param SistemaInformaticoRepository $sistemaInformaticoRepository
     * @return Response
     */
    public function registrar(Request $request, SistemaInformaticoProcesoRepository $sistemaInformaticoProcesoRepository, ProcesoRepository $procesoRepository, PersonaRepository $personaRepository, SistemaInformaticoRepository $sistemaInformaticoRepository)
    {
        try {
            $entidad = new SistemaInformatico();
            $form = $this->createForm(SistemaInformaticoType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $allPost = $request->request->all();
                $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                $entidad->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                $sistemaInformaticoRepository->add($entidad, true);

                if (isset($allPost['sistema_informatico']['proceso']) && is_array($allPost['sistema_informatico']['proceso'])) {
                    foreach ($allPost['sistema_informatico']['proceso'] as $value) {
                        $sistemaProceso = new SistemaInformaticoProceso();
                        $sistemaProceso->setSistemaInformatico($entidad);
                        $sistemaProceso->setProceso($procesoRepository->find($value));
                        $sistemaInformaticoProcesoRepository->add($sistemaProceso, true);
                    }
                }
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_informatico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/sistemaInformatico/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_informatico_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_sistema_informatico_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param SistemaInformatico $sistemaInformatico
     * @param SistemaInformaticoRepository $sistemaInformaticoRepository
     * @return Response
     */
    public function modificar(Request $request, ProcesoRepository $procesoRepository, SistemaInformaticoProcesoRepository $sistemaInformaticoProcesoRepository, PersonaRepository $personaRepository, SistemaInformatico $sistemaInformatico, SistemaInformaticoRepository $sistemaInformaticoRepository)
    {
        try {
            $form = $this->createForm(SistemaInformaticoType::class, $sistemaInformatico, ['action' => 'modificar']);
            $form->handleRequest($request);
            $sistemaProceso = $sistemaInformaticoProcesoRepository->findBy(['sistemaInformatico' => $sistemaInformatico->getId()]);
            $asociados = [];

            foreach ($sistemaProceso as $value) {
                $asociados[] = $value->getProceso()->getId();
                $sistemaInformaticoProcesoRepository->remove($value);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $allPost = $request->request->all();
                if (!method_exists($sistemaInformatico->getEstructura(), 'getId')) {
                    $personaAutenticada = $personaRepository->findOneBy(['usuario' => $this->getUser()->getId()]);
                    $sistemaInformatico->setEstructura($personaAutenticada->getEstructura()->getEstructura());
                }
                if (isset($allPost['sistema_informatico']['proceso']) && is_array($allPost['sistema_informatico']['proceso'])) {
                    foreach ($allPost['sistema_informatico']['proceso'] as $value) {
                        $sistemaProceso = new SistemaInformaticoProceso();
                        $sistemaProceso->setSistemaInformatico($sistemaInformatico);
                        $sistemaProceso->setProceso($procesoRepository->find($value));
                        $sistemaInformaticoProcesoRepository->add($sistemaProceso, true);
                    }
                }

                $sistemaInformaticoRepository->edit($sistemaInformatico);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_informatico_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/sistemaInformatico/edit.html.twig', [
                'form' => $form->createView(),
                'sistemaInformatico' => $sistemaInformatico,
                'asociados' => json_encode($asociados)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_informatico_modificar', ['id' => $sistemaInformatico->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_sistema_informatico_detail", methods={"GET", "POST"})
     * @param sistemaInformatico $sistemaInformatico
     * @return Response
     */
    public function detail(SistemaInformatico $sistemaInformatico, SistemaInformaticoProcesoRepository $sistemaInformaticoProcesoRepository)
    {
        $sistemaProceso = $sistemaInformaticoProcesoRepository->findBy(['sistemaInformatico' => $sistemaInformatico->getId()]);
        $asociados = [];
        foreach ($sistemaProceso as $value) {
            $asociados[] = $value->getProceso()->getNombre();
        }

        return $this->render('modules/informatizacion/sistemaInformatico/detail.html.twig', [
            'item' => $sistemaInformatico,
            'procesos' => implode(', ', $asociados),
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_sistema_informatico_eliminar", methods={"GET"})
     * @param SistemaInformatico $sistemaInformatico
     * @param SistemaInformaticoRepository $sistemaInformaticoRepository
     * @return Response
     */
    public function eliminar(SistemaInformatico $sistemaInformatico, SistemaInformaticoRepository $sistemaInformaticoRepository)
    {
        try {
            if ($sistemaInformaticoRepository->find($sistemaInformatico) instanceof SistemaInformatico) {
                $sistemaInformaticoRepository->remove($sistemaInformatico, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_sistema_informatico_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_sistema_informatico_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_sistema_informatico_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
