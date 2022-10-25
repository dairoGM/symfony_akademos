<?php

namespace App\Controller\Admin;

use App\Entity\Security\Rol;
use App\Entity\Security\RolEstructura;
use App\Form\Admin\RolType;
use App\Repository\Estructura\EstructuraRepository;
use App\Repository\Security\RolEstructuraRepository;
use App\Repository\Security\RolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/administracion/rol")
 * @IsGranted("ROLE_ADMIN", "ROLE_GEST_ROLES")
 */
class RolController extends AbstractController
{

    /**
     * @Route("/", name="app_rol_index", methods={"GET"})
     * @param RolRepository $rolRepository
     * @return Response
     */
    public function index(RolRepository $rolRepository)
    {
        try {
            return $this->render('modules/admin/rol/index.html.twig', [
                'registros' => $rolRepository->findBy([], ['activo' => 'desc', 'id' => 'desc']),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/registrar", name="app_rol_registrar", methods={"GET", "POST"})
     * @param Request $request
     * @param RolRepository $rolRepository
     * @return Response
     */
    public function registrar(Request $request, RolRepository $rolRepository, EntityManagerInterface $em, EstructuraRepository $estructuraRepository)
    {
        try {
            $rolEntity = new Rol();
            $form = $this->createForm(RolType::class, $rolEntity, ['action' => 'registrar']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $rolRepository->add($rolEntity, true);

                $allPost = $request->request->all();
                if (isset($allPost['rol']['estructuras'])) {
                    foreach ($allPost['rol']['estructuras'] as $value) {
                        $nuevoIndicadorEstruct = new RolEstructura();
                        $nuevoIndicadorEstruct->setRol($rolEntity);
                        $nuevoIndicadorEstruct->setEstructura($estructuraRepository->find($value));
                        $em->persist($nuevoIndicadorEstruct);
                    }
                }
                $em->flush();

                $this->addFlash('success', 'El elemento ha sido creado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('modules/admin/rol/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_registrar', [], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/modificar", name="app_rol_modificar", methods={"GET", "POST"})
     * @param Request $request
     * @param Rol $rol
     * @param RolRepository $rolRepository
     * @return Response
     */
    public function modificar(Request $request, Rol $rol, EstructuraRepository $estructuraRepository, RolRepository $rolRepository, RolEstructuraRepository $rolEstructuraRepository, EntityManagerInterface $em)
    {
        try {
            $form = $this->createForm(RolType::class, $rol, ['action' => 'modificar']);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $rolRepository->edit($rol);

                $allPost = $request->request->all();

                if (isset($allPost['rol']['estructuras'])) {
                    foreach ($rolEstructuraRepository->findBy(['rol' => $rol->getId()]) as $value) {
                        $rolEstructuraRepository->remove($value);
                    }
                    $em->flush();
                    foreach ($allPost['rol']['estructuras'] as $value) {
                        $nuevoIndicadorEstruct = new RolEstructura();
                        $nuevoIndicadorEstruct->setRol($rol);
                        $nuevoIndicadorEstruct->setEstructura($estructuraRepository->find($value));
                        $em->persist($nuevoIndicadorEstruct);
                    }
                } else {
                    foreach ($rolEstructuraRepository->findBy(['rol' => $rol->getId()]) as $value) {
                        $rolEstructuraRepository->remove($value);
                    }
                }
                $em->flush();
                $this->addFlash('success', 'El elemento ha sido actualizado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
            }

            $estructs = $rolEstructuraRepository->findBy(['rol' => $rol->getId()]);
            $estructuras = [];
            foreach ($estructs as $value) {
                $estructuras[] = $value->getEstructura()->getId();
            }

            return $this->render('modules/admin/rol/edit.html.twig', [
                'form' => $form->createView(),
                'estructuras' => json_encode($estructuras)
            ]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_modificar', ['id' => $rol->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/eliminar", name="app_rol_eliminar", methods={"GET"})
     * @param Request $request
     * @param Rol $rol
     * @param RolRepository $rolRepository
     * @return Response
     */
    public function eliminar(Request $request, Rol $rol, RolRepository $rolRepository)
    {
        try {
            if ($rolRepository->find($rol) instanceof Rol) {
                $rolRepository->remove($rol, true);
                $this->addFlash('success', 'El elemento ha sido eliminado satisfactoriamente.');
                return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('error', 'Error en la entrada de datos');
            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/detail", name="app_rol_detail", methods={"GET", "POST"})
     * @param Request $request
     * @param Rol $rol
     * @return Response
     */
    public function detail(Request $request, Rol $rol, RolEstructuraRepository $rolEstructuraRepository)
    {
        return $this->render('modules/admin/rol/detail.html.twig', [
            'item' => $rol,
            'estructuras'=> $rolEstructuraRepository->findBy(['rol'=>$rol->getId()])
        ]);
    }
}
