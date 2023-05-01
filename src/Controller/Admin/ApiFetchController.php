<?php

namespace App\Controller\Admin;

use App\Entity\CourseCategory;
use App\Repository\CourseCategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\SetMenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiFetchController extends AbstractController
{
    #[Route('/api/fetch/courses', name: 'app_api_fetch_course')]
    public function fetchCourse(
        Request $request,
        CourseRepository $courseRepository,
        SetMenuRepository $setMenuRepository,
        CourseCategoryRepository $courseCategoryRepository
    ): Response
    {

        // On récupère l'id envoyé à travers l'url
        $setmenuId = $request->query->get('setmenu');

        // On récupère les Catégories de plats lié à la formule
        // via une méthode personnalisé (un exmple dans les classes
        // "Repository") qui renvoie un objet de type CourseCategory
        $courseCategories = $courseCategoryRepository->findBySetmenuId($setmenuId);

        $all_courses = [];

        foreach ($courseCategories as $category) {
            $courses = $courseRepository->findBy(['category' => $category->getId()]);

            array_push($all_courses, $courses);
        }
        

        $response = new JsonResponse();
        $response->setData($all_courses);
        
        return $response;
    }
}
