<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Customer;
use App\Form\BookingFormType;
use App\Repository\AllergenRepository;
use DateTimeImmutable;
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
        EntityManagerInterface $entityManager,
        AllergenRepository $allergenRepository
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
        if ($user && $user instanceof Customer && !$booking_form->isSubmitted()){

            // On récupère les données son forme
            // d'objet Booking
            $data = $booking_form->getData();

            // On défini les valeur par défaut de l'utilisateur
            $data->setCustomerFirstname($user->getFirstname());
            $data->setCustomerLastname($user->getLastname());
            $data->setCustomerPhone($user->getPhone());
            $data->setCustomerMail($user->getEmail());
            $data->setCovers($user->getDefaultCovers());


            foreach($user->getCustomerAllergens() as $allergen) {
                $data->addAllergens($allergen);
            }

            // Et on rempli le formulaire des données utilisateur
            $booking_form->setData($data);
        } 

        if($booking_form->isSubmitted() && $booking_form->isValid()) {

            $data = $booking_form->getData();

            // On sépare les heures et les minutes
            $slot = explode(':', $request->request->get('radio_books'));
            // On crée un timestamp(int) à partir des heures et minutes +
            // la sélection dans le calendrier
            
            if(count($slot) < 2) {
                $this->addFlash('danger', 'Veuillez choisir un créneau horaire');
                return $this->redirectToRoute('app_booking');
            }

            foreach($booking_form->get('allergens')->getData() as $allergen) {
                $booking->addAllergen($allergen);
                $i = $allergenRepository->find($allergen->getId());
                $i->addBooking($booking);
            }


            $book_slot_time = mktime(
                $slot[0], 
                $slot[1], 
                0, 
                $booking_form->getData()->getBookingDate()->format('n'), 
                $booking_form->getData()->getBookingDate()->format('j'), 
                $booking_form->getData()->getBookingDate()->format('Y')
            );

            // On applique le nouveau DateTimeImmutable avec le nouveau timestamp
            $data->setBookingDate((new DateTimeImmutable())->setTimestamp($book_slot_time));
            if($user) {
                
                $data->setCustomer($user);
            }

            $this->addFlash('success', 'Réservation effectué avec succès');

            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }


        return $this->render('booking/index.html.twig', [
            'bookingForm' => $booking_form->createView(),
        ]); 
    }
}
