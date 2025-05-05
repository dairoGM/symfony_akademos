<?php

namespace App\Controller\RRHH;

use App\Entity\RRHH\CategoriaDocente;
use App\Entity\Security\User;
use App\Form\RRHH\CategoriaDocenteType;
use App\Repository\RRHH\CategoriaDocenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/rrhh/categoria_docente_especial")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_GEST_CAT_DOC")
 */
class CategoriaDocenteController extends AbstractController
{

    /**
     * @Route("/", name="app_rrhh_categoria_docente_index", methods={"GET"})
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function index(CategoriaDocenteRepository $categoriaDocenteRepository)
    {
        return $this->render('modules/rrhh/categoria_docente/index.html.twig', [
            'registros' => $categoriaDocenteRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
        ]);
    }

    /**
     * @Route("/registrar", name="app_rrhh_categoria_docente_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function registrar(Request $request, CategoriaDocenteRepository $categoriaDocenteRepository, SerializerInterface $serializer)
    {
        try {
            $catDocenteEntity = new CategoriaDocente();
            $form = $this->createForm(CategoriaDocenteType::class, $catDocenteEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaDocenteRepository->add($catDocenteEntity, true);
                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_categoria_docente_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/rrhh/categoria_docente/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_categoria_docente_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rrhh_categoria_docente_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaDocente
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function modificar(Request $request, CategoriaDocente $categoriaDocente, CategoriaDocenteRepository $categoriaDocenteRepository)
    {
        try {
            $form = $this->createForm(CategoriaDocenteType::class, $categoriaDocente, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoriaDocenteRepository->edit($categoriaDocente);
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_categoria_docente_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/rrhh/categoria_docente/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_categoria_docente_modificar', ['id' => $categoriaDocente], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/detail", name="app_rrhh_categoria_docente_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param User $categoriaDocente
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function detail(Request $request, CategoriaDocente $categoriaDocente)
    {
        return $this->render('modules/rrhh/categoria_docente/detail.html.twig', [
            'item' => $categoriaDocente,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_rrhh_categoria_docente_eliminar", methods={"GET"})
     * @param Request $request
     * @param CategoriaDocente $categoriaDocente
     * @param CategoriaDocenteRepository $categoriaDocenteRepository
     * @return Response
     */
    public function eliminar(Request $request, CategoriaDocente $categoriaDocente, CategoriaDocenteRepository $categoriaDocenteRepository)
    {
        try {
            if ($categoriaDocenteRepository->find($categoriaDocente) instanceof CategoriaDocente) {
                $categoriaDocenteRepository->remove($categoriaDocente, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rrhh_categoria_docente_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rrhh_categoria_docente_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rrhh_categoria_docente_index', [], Response::HTTP_SEE_OTHER);
        }
    }

}
