<?php

namespace App\Controller\Informatizacion;

use App\Entity\Informatizacion\LineaCelular;
use App\Entity\Informatizacion\LineaCelularRecargas;
use App\Entity\Informatizacion\LineaCelularResponsable;
use App\Entity\Personal\Persona;
use App\Entity\Pregrado\ComisionNacional;
use App\Entity\Pregrado\MiembrosComisionNacional;
use App\Form\Informatizacion\LineaCelularRecargarType;
use App\Form\Informatizacion\LineaCelularType;
use App\Form\Pregrado\ComisionNacionalType;
use App\Repository\Informatizacion\LineaCelularRecargasRepository;
use App\Repository\Informatizacion\LineaCelularRepository;
use App\Repository\Informatizacion\LineaCelularResponsableRepository;
use App\Repository\Personal\PersonaRepository;
use App\Repository\Postgrado\MiembrosComisionRepository;
use App\Repository\Postgrado\RolComisionRepository;
use App\Repository\Pregrado\ComisionNacionalRepository;
use App\Repository\Pregrado\MiembrosComisionNacionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/informatizacion/linea_celular")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_LINEA_CELULAR")
 */
class LineaCelularController extends AbstractController
{

    /**
     * @Route("/", name="app_linea_celular_index", methods={"GET"})
     * @param LineaCelularRepository $LineaCelularRepository
     * @return Response
     */
    public function index(LineaCelularRepository $LineaCelularRepository)
    {
        return $this->render('modules/informatizacion/lineaCelular/index.html.twig', [
            'registros' => $LineaCelularRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_linea_celular_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function registrar(Request $request, PersonaRepository $personaRepository, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            $entidad = new LineaCelular();
            $form = $this->createForm(LineaCelularType::class, $entidad, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $lineaCelularRepository->add($entidad, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/lineaCelular/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_linea_celular_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function modificar(Request $request, PersonaRepository $personaRepository, LineaCelular $lineaCelular, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            $form = $this->createForm(LineaCelularType::class, $lineaCelular, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $lineaCelularRepository->edit($lineaCelular);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/informatizacion/lineaCelular/edit.html.twig', [
                'form' => $form->createView(),
                'lineaCelular' => $lineaCelular
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_modificar', ['id' => $lineaCelular->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_linea_celular_detail", methods={"GET", "POST"})
     * @param lineaCelular $lineaCelular
     * @return Response
     */
    public function detail(LineaCelular $lineaCelular, LineaCelularRecargasRepository $lineaCelularRecargasRepository, LineaCelularResponsableRepository $lineaCelularResponsableRepository)
    {
        $asignadas = [];
        $temp = $lineaCelularResponsableRepository->findBy(['lineaCelular' => $lineaCelular->getId()], ['id' => 'desc']);
        foreach ($temp as $value) {
            $asignadas[] = $value->getResponsable();
        }

        $recargas = $lineaCelularRecargasRepository->findBy(['lineaCelular' => $lineaCelular->getId()], ['id' => 'desc']);
        return $this->render('modules/informatizacion/lineaCelular/detail.html.twig', [
            'item' => $lineaCelular,
            'recargas' => $recargas,
            'asignadas' => $asignadas
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_linea_celular_eliminar", methods={"GET"})
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function eliminar(LineaCelular $lineaCelular, LineaCelularRepository $lineaCelularRepository)
    {
        try {
            if ($lineaCelularRepository->find($lineaCelular) instanceof LineaCelular) {
                $lineaCelularRepository->remove($lineaCelular, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/recargar", name="app_linea_celular_recargar", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function recargar(Request $request, LineaCelular $lineaCelular, LineaCelularRepository $lineaCelularRepository, LineaCelularRecargasRepository $lineaCelularRecargasRepository)
    {
        try {
            $recarga = new LineaCelularRecargas();
            $form = $this->createForm(LineaCelularRecargarType::class, $recarga, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $recarga->setLineaCelular($lineaCelular);
                $lineaCelularRecargasRepository->edit($recarga);


                if (!empty($recarga->getPlanDatos())) {
                    $lineaCelular->setPlanDatos($recarga->getPlanDatos());
                }
                if (!empty($recarga->getPlanSms())) {
                    $lineaCelular->setPlanSms($recarga->getPlanSms());
                }
                if (!empty($recarga->getPlanVoz())) {
                    $lineaCelular->setPlanVoz($recarga->getPlanVoz());
                }
                $lineaCelularRepository->edit($lineaCelular, true);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_recargar', ['id' => $lineaCelular->getId()], Response::HTTP_SEE_OTHER);
            }
            $recargas = $lineaCelularRecargasRepository->findBy(['lineaCelular' => $lineaCelular->getId()], ['id' => 'desc']);
            return $this->render('modules/informatizacion/lineaCelular/recargar.html.twig', [
                'form' => $form->createView(),
                'lineaCelular' => $lineaCelular,
                'recargas' => $recargas
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_index', ['id' => $lineaCelular->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/responsable", name="app_linea_celular_responsable", methods={"GET", "POST"})
     * @param Request $request
     * @param LineaCelular $lineaCelular
     * @param LineaCelularRepository $lineaCelularRepository
     * @return Response
     */
    public function responsable(Request $request, LineaCelular $lineaCelular, LineaCelularResponsableRepository $lineaCelularResopnsableRepository, PersonaRepository $personaRepository, LineaCelularRepository $lineaCelularRepository, LineaCelularRecargasRepository $lineaCelularRecargasRepository)
    {
        try {
            $arrayIdAsignados = [];
            $asignadas = [];
            $temp = $lineaCelularResopnsableRepository->findBy(['lineaCelular' => $lineaCelular->getId()], ['id' => 'desc']);
            foreach ($temp as $value) {
                $arrayIdAsignados[] = $value->getResponsable()->getId();
                $asignadas[] = $value->getResponsable();
            }
            $request->getSession()->set('idLineaCelular', $lineaCelular->getId());
            $personas = $personaRepository->getPersonasNoAsignadasDadoArrayIdPersonas($arrayIdAsignados);
            return $this->render('modules/informatizacion/lineaCelular/responsable.html.twig', [
                'lineaCelular' => $lineaCelular,
                'personas' => $personas,
                'asignadas' => $asignadas,
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_index', ['id' => $lineaCelular->getId()], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/asociar_responsable", name="app_linea_celular_asociar_responsable", methods={"GET", "POST"})
     * @param Request $request
     * @param PersonaRepository $personaRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function asociarResponsable(Request $request, PersonaRepository $personaRepository, LineaCelularRepository $lineaCelularRepository, EntityManagerInterface $em)
    {
        try {
            $arrayIds = $request->request->get('arrayId');
            if (is_array($arrayIds)) {
                foreach ($arrayIds as $value) {
                    $responsable = new LineaCelularResponsable();
                    $responsable->setResponsable($personaRepository->find($value['id_persona']));
                    $responsable->setLineaCelular($lineaCelularRepository->find($request->getSession()->get('idLineaCelular')));
                    $em->persist($responsable);
                }
                $em->flush();
            }
            return $this->json('OK');
        } catch (\Exception $exception) {
            return $this->json(true);
        }
    }

    /**
     * @Route("/{id}/eliminar_responsable", name="app_linea_celular_eliminar_responsable", methods={"GET"})
     * @param Request $request
     * @param Persona $persona
     * @param PersonaRepository $personaRepository
     * @param LineaCelularResponsableRepository $lineaCelularResponsableRepository
     * @return Response
     */
    public function eliminarResponsable(Request $request, Persona $persona, PersonaRepository $personaRepository, LineaCelularResponsableRepository $lineaCelularResponsableRepository)
    {
        try {
            $lineaCelular = $request->getSession()->get('idLineaCelular');
            if ($personaRepository->find($persona->getId()) instanceof Persona) {
                $miembro = $lineaCelularResponsableRepository->findOneBy(['responsable' => $persona->getId(), 'lineaCelular' => $lineaCelular]);
                $lineaCelularResponsableRepository->remove($miembro, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_linea_celular_responsable', ['id' => $lineaCelular], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_linea_celular_responsable', ['id' => $lineaCelular], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_linea_celular_responsable', ['id' => $lineaCelular], Response::HTTP_SEE_OTHER);
        }
    }
}
