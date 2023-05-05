<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        PictureRepository $pictureRepository
    ): Response
    {

        $gallery = $pictureRepository->findAll();

        if(count($gallery) <= 6) {
            $last_picture = $gallery;
        } else {
            $last_picture = array_slice($gallery, count($gallery)-6);
        }

        $last_picture = array_reverse($last_picture);


        return $this->render('main/index.html.twig', [
            'gallery' => $last_picture,
        ]);
    }
}
