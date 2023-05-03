<?php

namespace App\Controller\Customer;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilCustomerController extends AbstractController
{
    #[Route('/profil', name: 'app_profil_customer')]
    public function index(
        BookingRepository $bookingRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CUSTOMER');

        /** @var $user Customer */
        $user = $this->getUser();

        $user_books = $bookingRepository->findBy(['customer' => $user]);

        return $this->render('profil_customer/index.html.twig', [
            'user' => $user,
            'books' => $user_books
        ]);
    }
}
