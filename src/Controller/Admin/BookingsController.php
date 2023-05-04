<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingsController extends AbstractController
{
    #[Route('/admin/reservations', name: 'app_bookings')]
    public function index(): Response
    {
        return $this->render('admin/bookings/index.html.twig', [
        ]);
    }
}
