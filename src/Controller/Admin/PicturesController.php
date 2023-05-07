<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use App\Form\PictureFormType;
use App\Repository\PictureRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/gallerie', name: 'app_gallery_')]
class PicturesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        PictureRepository $pictureRepository
    ): Response
    {

        return $this->render('admin/gallery/index.html.twig', [
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function add(        
        Request $request,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response
    {

        $picture = new Picture();

        $picture_form = $this->createForm(PictureFormType::class, $picture);

        $picture_form->handleRequest($request);

        if ($picture_form->isSubmitted() && $picture_form->isValid()) {

            $picture_file = $picture_form->get('picture')->getData();

            // On assigne le dossier de destination
            $folder = 'gallery';

            $file = $pictureService->add($picture_file, $folder, 500, 500);
            $picture->setFileName($file);

            $entityManager->persist($picture);
            $entityManager->flush();

            $this->addFlash('success', 'Formule ajoutée avec succès');

            return $this->redirectToRoute('app_gallery_index');

        }

        return $this->render('admin/gallery/add.html.twig', [
            'setmenuForm' => $picture_form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Picture $picture,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $picture_form = $this->createForm(PictureFormType::class, $picture);

        $picture_form->handleRequest($request);

        if ($picture_form->isSubmitted() && $picture_form->isValid()) {

            $entityManager->persist($picture);
            $entityManager->flush();

            $this->addFlash('success', 'Formule modifiée avec succès');

            return $this->redirectToRoute('app_gallery_index');

        }
        return $this->render('admin/gallery/edit.html.twig', [
            'setmenuForm' => $picture_form->createView(),
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(
        Picture $picture,
        EntityManagerInterface $entityManager
    ): Response
    {

        $entityManager->remove($picture);
        $entityManager->flush();

        return $this->redirectToRoute('app_gallery_index');
    }
}
