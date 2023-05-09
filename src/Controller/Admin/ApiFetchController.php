<?php

namespace App\Controller\Admin;

use App\Entity\CourseCategory;
use App\Repository\AllergenRepository;
use App\Repository\BookingRepository;
use App\Repository\CourseCategoryRepository;
use App\Repository\CourseRepository;
use App\Repository\HoursRepository;
use App\Repository\SetMenuRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ApiFetchController extends AbstractController
{
    #[Route('/api/fetch/courses', name: 'app_api_fetch_course')]
    public function fetchCourse(
        Request $request,
        CourseRepository $courseRepository,
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

                $courses_toexport[$category->getLabel()] = $course_toexport;
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
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        $day_hours = $hoursRepository->findBy(['label' => $day]);

        // On récupère les réservations du jour
        $all_booking = $bookingRepository->findByDateInterval(
            (new DateTimeImmutable())->setTimestamp(intval($timestamp)), 
            (new DateTimeImmutable())->setTimestamp(intval($timestamp)+ 24*60*60)
        );

        // Et on récupère leurs horaires en timestamp dans un nouveau tableau
        $all_booking_date = [];
        foreach ($all_booking as $booking) {
            array_push($all_booking_date, $booking->getBookingDate()->getTimestamp());
        }

        // On crée un tableau avec toutes les horaires par tranche de 15min
        // Et on y insère les les valeurs sous format hh:mm (book) et si le créneau et
        // déjà réserver ($took)
        // On met en index de ce tableau si c'est le midi ou le dinner ($period)
        $hours_available = [];

        foreach($day_hours as $d) {

            // On prends le timestamp crée à partir d'un format de date à partir des données 
            // récupérées
            $opening = (new DateTime())
                ->createFromFormat('l-m-Y/H:i', ucfirst($day).'-'.$month.'-'.$year.'/'.$d->getOpening())
                ->getTimestamp();
            $closure = (new DateTime())
                ->createFromFormat('l-m-Y/H:i', ucfirst($day).'-'.$month.'-'.$year.'/'.$d->getClosure())
                ->getTimestamp();

            $half_day_hours_available = [];

            // Si le restaurant est ouvert on continue 
            if ($d->isOpen()) {

                //On vérifie si le nombre max de réservation n'est pas déjà attends
                $max = 0;
                $isMax = false;
                for($slot = $opening; $slot <= $closure - 60*60; $slot += 60*15) {
                    if(array_search($slot, $all_booking_date)){
                        $max += 1;
                    }
                    if($max >= $d->getMaxBooking()) {
                        $isMax = true;
                    }
                }

                for($i = $opening; $i <= $closure - 60*60; $i += 60*15) {
                    $took = array_search($i, $all_booking_date) !== false ? true : false;
                    if($isMax) $took = true;
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

    #[Route('/api/fetch/bookings', name: 'app_api_fetch_bookings')]
    public function fetchBookings(
        Request $request,
        BookingRepository $bookingRepository,
    ): Response
    {
        $date = $request->query->get('date');

        $formatted_date = (new DateTimeImmutable())->createFromFormat('Y-m-d', $date);

        $bookings = $bookingRepository->findByDateInterval(
            $formatted_date->format('Y-m-d'),
            (new DateTimeImmutable())
                ->createFromFormat(
                    'Y-m-d', 
                    date('Y-m-d', $formatted_date->getTimestamp()+24*60*60)
                )->format('Y-m-d')
        );

        $formatted_bookingsArray = [];
        foreach($bookings as $b) {
            $allergens = [];
            foreach($b->getAllergens() as $allergen) {
                array_push($allergens, $allergen->getLabel());
            }
            array_push($formatted_bookingsArray, [
                'date' => $b->getBookingDate(),
                'customerFirstname' => $b->getCustomerFirstname(),
                'customerLastname' => $b->getCustomerLastname(),
                'customerPhone' => $b->getCustomerPhone(),
                'customerEmail' => $b->getCustomerMail(),
                'covers' => $b->getCovers(),
                'allergens' => $allergens,
                'message' => $b->getMessage(),
            ]);
        }

        return new JsonResponse($formatted_bookingsArray);
    }
}
// function() use ($b) {
//     $allergens = [];
//     foreach($b->getAllergens() as $allergen) {
//         array_push($allergens, [
//             'label' => $allergen->getLabel()
//         ]);
//     };
//     dd($allergens);
//     return $allergens;
// }