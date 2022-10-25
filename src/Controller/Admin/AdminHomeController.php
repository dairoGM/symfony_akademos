<?php

namespace App\Controller\Admin;

use App\Repository\Personal\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN", "ROLE_PORTADA_ADMIN")
 */
class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="app_admin_home")
     */
    public function index(PersonaRepository $personaRepository): Response
    {

        $personas = $personaRepository->findAll();


        $municipios = [];
        $categorias = [];

        foreach ($personas as $persona){
            $municipios[$persona->getMunicipio()->getNombre()][] = $persona->getId();

            $categorias[$persona->getClasificacionPersona()->getNombre()][] = $persona->getId();
        }

        $categoriasChat = [];

        foreach ($categorias as $clave => $valor){
            $aux = [];
            $aux['name'] = $clave;
            $aux['y'] = count($valor);
            $categoriasChat[] = $aux;
        }


        return $this->render('modules/admin/index.html.twig', [
            'data' => $municipios,
            'clasificacion' => json_encode($categoriasChat),
            'lista' => [],
        ]);
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
