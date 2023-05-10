<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Form\MenuFormType;
use App\Repository\CourseRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/menus', name: 'app_menus_')]
class MenusController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        MenuRepository $menuRepository
    ): Response
    {
        return $this->render('admin/menus/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request,
        CourseRepository $courseRepository,
        EntityManagerInterface $entityManager,
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

                $courseId = $request->request->get('menu_form_courses'.$count);
                $course = $courseRepository->find($courseId);
                $menu->addCourse($course);
                $course->addMenu($menu);
                
                $count ++;
            }
            $entityManager->persist($menu);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenu pendant l\'enregistrement des éléments dans la base de données.');
            }

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
        EntityManagerInterface $entityManager,
        CourseRepository $courseRepository
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $menu_form = $this->createForm(MenuFormType::class, $menu);

        $menu_form->handleRequest($request);
        $post = $_POST;

        if($menu_form->isSubmitted() && $menu_form->isValid()) {
            $count = 0;
            // On boucle sur tous les select des catégories de plat
            while ($request->request->has('menu_form_courses'.$count)) {
            
                $courseIds = $post['menu_form_courses'.$count];

                foreach ($courseIds as $courseId){
                    $course = $courseRepository->find($courseId);
                    $menu->addCourse($course);
                    $course->addMenu($menu);
                }
                
                $count ++;
            }

            $entityManager->persist($menu);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenu pendant l\'enregistrement des éléments dans la base de données.');
            }

            $this->addFlash('success', 'Menu modifié avec succès');

            return $this->redirectToRoute('app_menus_index');

        }

        return $this->render('admin/menus/edit.html.twig', [
            'menuForm' => $menu_form->createView(),
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(
        Menu $menu,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($menu);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenu pendant la suppression des éléments dans la base de données.');
        }

        $this->addFlash('success', 'Menu supprimé avec succès');

        return $this->redirect('app_menus_index');
    }
}
