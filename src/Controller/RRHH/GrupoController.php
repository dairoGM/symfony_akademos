<?php

namespace App\Controller\RRHH;


use App\Entity\RRHH\Grupo;

use App\Form\RRHH\GrupoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/rrhh/grupos")
 * @IsGranted("ROLE_ADMIN", "ROLE_RRHH_GEST_GRUPOS")
 */
class GrupoController extends AbstractController
{
    /**
     * @Route("/", name="app_grupo_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $registros = $entityManager->getRepository(Grupo::class)->findAll();

        return $this->render('modules/rrhh/grupo/index.html.twig', [
            'registros' => $registros,
        ]);
    }

    /**
     * @Route("/registrar", name="app_grupo_registrar")
     */
    public function registrar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($grupo->getEstructuras() as $estructura) {
                $estructura->setGrupo($grupo); // 👈 setear FK en cada estructura
                $entityManager->persist($estructura);
            }

            $entityManager->persist($grupo);
            $entityManager->flush();

            $this->addFlash('success', 'Grupo creado correctamente.');
            return $this->redirectToRoute('app_grupo_index');
        }

        return $this->render('modules/rrhh/grupo/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/modificar", name="app_grupo_modificar")
     */
    public function modificar(Request $request, Grupo $grupo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($grupo->getEstructuras() as $estructura) {
                $estructura->setGrupo($grupo); // 👈 setear FK en cada estructura
                $entityManager->persist($estructura);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Grupo modificado correctamente.');
            return $this->redirectToRoute('app_grupo_index');
        }

        return $this->render('modules/rrhh/grupo/edit.html.twig', [
            'form' => $form->createView(),
            'grupo' => $grupo,
        ]);
    }

    /**
     * @Route("/{id}/detail", name="app_grupo_detail")
     */
    public function detail(Grupo $grupo): Response
    {
        return $this->render('modules/rrhh/grupo/detail.html.twig', [
            'grupo' => $grupo,
        ]);
    }

    /**
     * @Route("/{id}/eliminar", name="app_grupo_eliminar")
     */
    public function eliminar(Grupo $grupo, EntityManagerInterface $entityManager): Response
    {
        if ($grupo->getEstructuras()->count() > 0) {
            $this->addFlash('error', 'No se puede eliminar el grupo porque tiene estructuras asignadas.');
            return $this->redirectToRoute('app_grupo_index');
        }

        $entityManager->remove($grupo);
        $entityManager->flush();

        $this->addFlash('success', 'Grupo eliminado correctamente.');
        return $this->redirectToRoute('app_grupo_index');
    }
}