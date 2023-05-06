<?php

namespace App\Controller;

use App\Repository\CourseCategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\MenuRepository;
use App\Repository\SetMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuPageController extends AbstractController
{
    #[Route('/menu', name: 'app_menu_page')]
    public function index(
        CourseRepository $courseRepository,
        CourseCategoryRepository $courseCategoryRepository,
        MenuRepository $menuRepository,
    ): Response
    {

        $categories = $courseCategoryRepository->findAll();

        $courses = $courseRepository->findAll();

        $menus = $menuRepository->findAll();

        $all_menus = [];
        $all_courses = [];

        foreach ( $menus as $menu) {

            foreach($menu->getCourses() as $course) {
                array_push($all_courses, $course);
            }

            $all_menus[$menu->getId()] = [
                'title' => $menu->getTitle(),
                'setmenu' => $menu->getSetMenu()->getTitle(),
                'setmenuCourseCat' => $menu->getSetMenu()->getCourseCategory(),
                'summary' => $menu->getSetMenu()->getSummary(),
                'price' => $menu->getSetMenu()->getPrice(),
                'courses' => $all_courses,
            ];
        }

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
            'menus' => $all_menus,
        ]);
    }
}
