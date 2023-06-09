<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Form\CoursesFormType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/plats', name: 'app_courses_')]
class CoursesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        CourseRepository $courseRepository
    ): Response
    {
        return $this->render('admin/courses/index.html.twig', [
            'courses' => $courseRepository->findAll()
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response
    {
        // Vérifie le role de l'utilisateur connecter
        // La méthode denyAccessUnlessGranted renverra
        // à une page "Access Denied" si l'utilisateur
        // n'a pas le role demandé.
        //
        // On peut attribuer des permissions plus finement
        // avec les Voters en associant avec des actions
        // le role minimum. A la place du role il faut mettre 
        // l'actions et l'entité manipuler dans les 
        // paramètres. 
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée un nouveau plat
        $course = new Course();
        // On crée un nouveau formulaire associé
        // à l'entité Course (plat)
        $course_form = $this->createForm(CoursesFormType::class, $course);

        // On récupère la requête du formulaire pour
        // pouvoir la traiter.
        // Il faut vérifier que le formulaire est envoyé
        // et valide avant de traiter 
        $course_form->handleRequest($request);

        // isSubmitted vérifie si le formulaire est correctement 
        // envoyé et isValid vérifie si il n' y a pas d'erreurs
        if($course_form->isSubmitted() && $course_form->isValid()) {

            //On persiste dans la base de données
            $entityManager->persist($course);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenu pendant l\'enregistrement des éléments dans la base de données.');
            }

            // On affiche un message de confirmation d'ajout
            $this->addFlash('success', 'Plat ajouté avec succès');

            return $this->redirectToRoute('app_courses_index');

        }

        return $this->render('admin/courses/add.html.twig', [
            'courseForm' => $course_form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Course $course,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $course_form = $this->createForm(CoursesFormType::class, $course);

        $course_form->handleRequest($request);

        if($course_form->isSubmitted() && $course_form->isValid()) {


            $entityManager->persist($course);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenu pendant l\'enregistrement des éléments dans la base de données.');
            }
            
            // On affiche un message de confirmation d'ajout
            $this->addFlash('success', 'Plat modifié avec succès');

            return $this->redirectToRoute('app_courses_index');

        }

        return $this->render('admin/courses/edit.html.twig', [
            'courseForm' => $course_form->createView(),
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(
        Course $course,
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($course);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenu pendant la suppression des éléments dans la base de données.');
        }

        $this->addFlash('success', 'Plat supprimé avec succès');

        return $this->redirect('app_courses_index');
    }
}
