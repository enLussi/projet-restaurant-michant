<?php

namespace App\Controller;

use App\Repository\CourseCategoryRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuPageController extends AbstractController
{
    #[Route('/menu', name: 'app_menu_page')]
    public function index(
        CourseRepository $courseRepository,
        CourseCategoryRepository $courseCategoryRepository
    ): Response
    {

        $categories = $courseCategoryRepository->findAll();

        $courses = $courseRepository->findAll();

        $sorted_courses = [];

        foreach ($courses as $course) {
            if(isset($sorted_courses[$course->getCategory()->getId()])) {
                array_push($sorted_courses[$course->getCategory()->getId()], $course);
            } else {
                $sorted_courses[$course->getCategory()->getId()] = [];
                array_push($sorted_courses[$course->getCategory()->getId()], $course);
            }
        }

        $sorted_category = [];
        foreach ($categories as $category) { 
            $sorted_category[$category->getId()] = $category->getLabel();
        }

        return $this->render('menu_page/index.html.twig', [
            'sortedCourses' => $sorted_courses,
            'categories' => $sorted_category,
        ]);
    }
}
