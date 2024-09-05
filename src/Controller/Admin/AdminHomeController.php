<?php

namespace App\Controller\Admin;

use App\Repository\Personal\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminHomeController extends AbstractController
{
    /**
     * @Route("/relaciones_internacionales/home", name="app_admin_home_ri")
     */
    public function indexRi(): Response
    {
        return $this->render('modules/admin/ri.html.twig');
    }

    /**
     * @Route("/akademos/home", name="app_admin_home")
     */
    public function index(): Response
    {
        return $this->render('modules/admin/akademos.html.twig');
    }


    /**
     * @Route("/akademos/perfil", name="app_admin_perfil")
     */
    public function perfil(): Response
    {
        return $this->render('modules/admin/perfil/index.html.twig');
    }

    /**
     * @Route("/eliminar/{id}", name="eliminar_todo_admin_dash")
     * @param EntityManagerInterface $entityManager
     * @param TodoLista $id
     * @return Response
     */
    public function eliminar(EntityManagerInterface $entityManager, TodoLista $id): Response
    {


        $entityManager->remove($id);
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_home');
    }
}
