<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Customer;
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

        // On récupère l'entité User connecté ou false si 
        // l'utilisateur n'est pas connecté
        //
        // L'annotation @var $user User permet à Symfony
        // de savoir que $user est de type UserInterface
        // et permet d'utiliser les méthodes accesseurs tel
        // que getFirstname()
        /** @var $user Customer */
        $user = $this->getUser();

        // On vérifie si l'utilisateur est connecté
        // et que l'objet User et bien un Customer
        // un Admin n'aura pas les mêmes méthodes et
        // accesseurs.
        if ($user && $user instanceof Customer){

            // On récupère les données son forme
            // d'objet Booking
            $data = $booking_form->getData();

            // On défini les valeur par défaut de l'utilisateur
            $data->setCustomerFirstname($user->getFirstname());
            $data->setCustomerLastname($user->getLastname());
            $data->setCustomerPhone($user->getPhone());
            $data->setCustomerMail($user->getEmail());
            $data->setCovers($user->getDefaultCovers());
            $data->setCustomer($user);

            foreach($user->getAllergens() as $allergen) {
                $data->addAllergens($allergen);
            }

            // Et on rempli le formulaire des données utilisateur
            $booking_form->setData($data);
        } 

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
