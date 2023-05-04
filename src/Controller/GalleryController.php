<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallerie', name: 'app_gallery')]
    public function index(
        PictureRepository $pictureRepository
    ): Response
    {
        $gallery = $pictureRepository->findAll();

        return $this->render('gallery/index.html.twig', [
            'gallery' => $gallery
        ]);
    }
}
