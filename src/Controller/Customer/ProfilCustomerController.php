<?php

namespace App\Controller\Customer;

use App\Form\ModifyCustomerInfoFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilCustomerController extends AbstractController
{
    #[Route('/profil', name: 'app_profil_customer')]
    public function index(
        BookingRepository $bookingRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CUSTOMER');

        /** @var $user Customer */
        $user = $this->getUser();

        $user_form = $this->createForm(ModifyCustomerInfoFormType::class, $user);

        $user_form->handleRequest($request);

        if ($user_form->isSubmitted() && $user_form->isValid()) {

            $this->addFlash('success', 'Informations modifiées avec succès');

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil_customer');
        }

        $user_books = $bookingRepository->findBy(['customer' => $user]);

        return $this->render('profil_customer/index.html.twig', [
            'user' => $user,
            'books' => $user_books,
            'userForm' => $user_form
        ]);
    }
}
