<?php

namespace App\Controller\Admin;

use App\Entity\CourseCategory;
use App\Repository\BookingRepository;
use App\Repository\CourseCategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\HoursRepository;
use App\Repository\SetMenuRepository;
use DateTimeImmutable;
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

            $courses_toexport = [];
            foreach ($courses as $course) {
                $course_toexport = [
                    'id' => $course->getId(),
                    'title' => $course->getTitle()
                ];

                array_push($courses_toexport, $course_toexport);
            }

            array_push($all_courses, $courses_toexport);
        }
        
        // On renvoie la réponse sous un format JSON
        $response = new JsonResponse($all_courses);
        return $response;
    }

    #[Route('/api/fetch/booking', name: 'app_api_fetch_booking')]
    public function fetchBooking(
        Request $request,
        BookingRepository $bookingRepository,
        HoursRepository $hoursRepository
    ): Response
    {
        // On récupère le timestamp du jour envoyé
        $timestamp = $request->query->get('timestamp');

        // On convertit le timestamp en date en 1 jour de la
        // semaine pour récupérer ensuite les horaires
        // correspondante au jour sélectionner
        $day = strtolower(date('l', $timestamp));
        $day_hours = $hoursRepository->findBy(['label' => $day]);

        // On récupère les réservations du jour
        $all_booking = $bookingRepository->findByDate(
            new DateTimeImmutable(strtotime($timestamp)), 
            new DateTimeImmutable(strtotime(strval(intval($timestamp) +24*60*60)))
        );

        // Et on récupère leurs horaires en timestamp dans un nouveau tableau
        $all_booking_date = [];
        foreach ($all_booking as $booking) {
            array_push($all_booking_date, $booking->getBookingDate()->getTimestamp());
        }

        // On crée un tableau avec toute les horaires par tranche de 15min
        // Et on y insère les les valeur sous format hh:mm (book) et si le créneau et
        // déjà réserver ($took)
        // On met en index de ce tableau si c'est le midi ou le dinner ($preiod)
        $hours_available = [];

        foreach($day_hours as $d) {
            $opening = strtotime($d->getOpening());
            $closure = strtotime($d->getClosure());

            $half_day_hours_available = [];

            if ($d->isOpen()) {
                for($i = $opening; $i <= $closure; $i += 60*15) {
                    $took = array_search($i, $all_booking_date);
                    array_push($half_day_hours_available, [
                        'book' => date( 'H:i', $i),
                        'took' => $took,
                    ]);
                }

                $period = $d->isLunch() ? 'lunch' : 'dinner';
                $hours_available = [...$hours_available,
                    $period => $half_day_hours_available
                ];
            }
        }

        // On renvoie les données pour traitement et affichage
        return new JsonResponse($hours_available);
    }
}
