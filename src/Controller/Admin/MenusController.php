<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Form\MenuFormType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/menus', name: 'app_menus_')]
class MenusController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/menus/index.html.twig', [
            'controller_name' => 'MenusController',
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request,
        CourseRepository $courseRepository,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $menu = new Menu();

        $menu_form = $this->createForm(MenuFormType::class, $menu);

        $menu_form->handleRequest($request);

        if($menu_form->isSubmitted() && $menu_form->isValid()) {
            $count = 0;

            // On boucle sur tous les select des catégories de plat
            while ($request->request->has('menu_form_courses'.$count)) {

                foreach($menu_form->get('menu_form_courses'.$count) as $course) {
                    $menu->addCourse($courseRepository->find($course));
                }

                $courseId = $request->request->get('menu_form_courses'.$count);
                
                $count ++;
            }

            $entityManager->persist($menu);
            $entityManager->flush();

            $this->addFlash('success', 'Menu ajouté avec succès');

            return $this->redirectToRoute('app_menus_index');

        }

        return $this->render('admin/menus/add.html.twig', [
            'menuForm' => $menu_form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Menu $menu,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $menu_form = $this->createForm(MenuFormType::class, $menu);

        $menu_form->handleRequest($request);

  

        if($menu_form->isSubmitted() && $menu_form->isValid()) {

            

            $entityManager->persist($menu);
            $entityManager->flush();

            $this->addFlash('success', 'Menu ajouté avec succès');

            return $this->redirectToRoute('app_menus_index');

        }

        return $this->render('admin/menus/edit.html.twig', [
            'menuForm' => $menu_form->createView(),
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/menus/index.html.twig', [
            'controller_name' => 'MenusController',
        ]);
    }
}
