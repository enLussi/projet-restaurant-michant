<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/reservation', name: 'app_booking')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {


        $booking = new Booking();

        $booking_form = $this->createForm(BookingFormType::class, $booking);

        $booking_form->handleRequest($request);

        if($booking_form->isSubmitted() && $booking_form->isValid()) {

            $this->addFlash('success', 'Réservation effectué avec succès');

            $entityManager->persist($booking);
            $entityManager->flush();
        }


        return $this->render('booking/index.html.twig', [
            'bookingForm' => $booking_form->createView(),
        ]); 
    }
}
