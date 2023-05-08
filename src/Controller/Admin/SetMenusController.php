<?php

namespace App\Controller\Admin;

use App\Entity\SetMenu;
use App\Form\SetMenuFormType;
use App\Repository\CourseCategoryRepository;
use App\Repository\SetMenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/formules', name: 'app_setmenus_')]
class SetMenusController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        SetMenuRepository $setMenuRepository
    ): Response
    {

        return $this->render('admin/setmenus/index.html.twig', [
            'setmenus' => $setMenuRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(        
        Request $request,
        EntityManagerInterface $entityManager,
        CourseCategoryRepository $courseCategoryRepository
    ): Response
    {

        $setmenu = new SetMenu();

        $setmenu_form = $this->createForm(SetMenuFormType::class, $setmenu);

        $setmenu_form->handleRequest($request);

        if ($setmenu_form->isSubmitted() && $setmenu_form->isValid()) {

            $entityManager->persist($setmenu);
            $entityManager->flush();

            foreach($setmenu_form->get('courseCategory')->getData() as $category) {
                $setmenu->addCourseCategory($category);
                $i = $courseCategoryRepository->find($category->getId());
                $i->addSetMenu($setmenu);
            }

            $this->addFlash('success', 'Formule ajoutée avec succès');

            return $this->redirectToRoute('app_setmenus_index');

        }

        return $this->render('admin/setmenus/add.html.twig', [
            'setmenuForm' => $setmenu_form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        SetMenu $setmenu,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $setmenu_form = $this->createForm(SetMenuFormType::class, $setmenu);

        $setmenu_form->handleRequest($request);

        if ($setmenu_form->isSubmitted() && $setmenu_form->isValid()) {

            $entityManager->persist($setmenu);
            $entityManager->flush();

            $this->addFlash('success', 'Formule modifiée avec succès');

            return $this->redirectToRoute('app_setmenus_index');

        }
        return $this->render('admin/setmenus/edit.html.twig', [
            'setmenuForm' => $setmenu_form->createView(),
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(
        SetMenu $setMenu,
        EntityManagerInterface $entityManager
    ): Response
    {

        $entityManager->remove($setMenu);
        $entityManager->flush();

        return $this->redirectToRoute('app_setmenus_index');
    }
}
