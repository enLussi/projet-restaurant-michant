<?php

namespace App\Controller\Admin;

use App\Entity\Hours;
use App\Form\HoursFormType;
use App\Repository\HoursRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hours', name: 'app_hours_')]
class HoursController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        HoursRepository $hoursRepository
    ): Response
    {
        return $this->render('admin/hours/index.html.twig', [
            'hours' => $hoursRepository->findAll(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        HoursRepository $hoursRepository
    ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $hours_form = $this->createForm(HoursFormType::class);

        // On récupère toutes les horaires en base de données
        // (de lundi à dimanche midi et soir -> données inchangeable)
        $hours_data = $hoursRepository->findAll();

        $data = [];
        $labels = [];

        // On boucle sur le retour de la requête à la base de données
        // et on les ajoute dans un tableau avec pour indice correspondant
        // à l'index du builder du formulaire
        foreach ($hours_data as $h) {
            
            // On défini les index dont on aura besoin
            $index_opening = $h->getLabel() . '_' . ($h->isLunch() ? 'lunch' : 'dinner') . '_opening';
            $index_closure = $h->getLabel() . '_' . ($h->isLunch() ? 'lunch' : 'dinner') . '_closure';
            $index_open = $h->getLabel() . '_' . ($h->isLunch() ? 'lunch' : 'dinner') . '_open';
            $index_max = $h->getLabel() . '_' . ($h->isLunch() ? 'lunch' : 'dinner') . '_max_bookings';

            // Et on les insère dans le tableau data les valeurs
            // selon les index prédéfini 

            $data[$index_opening] = DateTime::createFromFormat( 'H:i' , $h->getOpening());
            $data[$index_closure] = DateTime::createFromFormat( 'H:i' , $h->getClosure());
            $data[$index_open] = $h->isOpen();
            $data[$index_max] = $h->getMaxBooking();



            // On stocke le label pour l'affichage
            if(!in_array($h->getLabelDay(), $labels)){
                $labels = [...$labels,
                    $h->getLabelDay()
                ];
            }


        }

        $hours_form->handleRequest($request);

        if($hours_form->isSubmitted() && $hours_form->isValid()) {

            // $hours_form_data = $request->request->all()['hours_form']; 

            // en vérifiant au préalable
            // que l'heure d'ouverture est bien avant l'heure de fermeture

            foreach($hours_form->getData() as $index => $form_data) {

                $state = explode('_', $index);
                if(count($state) === 3) {

                    if($hours_form->getData()[$state[0].'_'.$state[1].'_opening'] > $hours_form->getData()[$state[0].'_'.$state[1].'_closure']) {
                        $this->addFlash('danger', 'L\'Heure d\'ouverture doit être avant l\'heure de fermeture ('.$state[1].')');
    
                        return $this->redirectToRoute('app_hours_edit');
                    }

                    switch($state[2]) {
                        case "opening":
                            $index_data = $this->searchInData($hours_data, $state);
                            if($index_data !== false) {
                                $hours_data[$index_data]->setOpening(
                                    $form_data->format('H:i')
                                ); 
                                $entityManager->persist($hours_data[$index_data]);
                            }
                            break;
                        case "closure":
                            $index_data = $this->searchInData($hours_data, $state);
                            if($index_data !== false) {
                                $hours_data[$index_data]->setClosure(
                                    $form_data->format('H:i')
                                ); 
                                $entityManager->persist($hours_data[$index_data]);
                            }
                            break;
                        case "open":
                            $index_data = $this->searchInData($hours_data, $state);
                            if($index_data !== false) {
                                $hours_data[$index_data]->setOpen(
                                    $form_data
                                ); 
                                $entityManager->persist($hours_data[$index_data]);
                            }
                        case "maxBooking":
                            $index_data = $this->searchInData($hours_data, $state);
                            if($index_data !== false) {
                                $hours_data[$index_data]->setMaxBooking(
                                    $form_data
                                ); 
                                $entityManager->persist($hours_data[$index_data]);
                            }
                        default:
                            break;
                    } 
                }
                
            }
            // foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $i => $day) {
            //     if(isset($hours_form_data[$day.'_lunch_open']) && $hours_form_data[$day.'_lunch_open'] == 1) {
            //         $hours_data[$i*2]->setOpen(true);
            //         $entityManager->persist($hours_data[$i*2]);
            //     } else {
            //         $hours_data[$i*2]->setOpen(false);
            //         $entityManager->persist($hours_data[$i*2]);
            //     }

                

            //     if(isset($hours_form_data[$day.'_dinner_open']) && $hours_form_data[$day.'_dinner_open'] == 1) {
            //         $hours_data[$i*2+1]->setOpen(true);
            //         $entityManager->persist($hours_data[$i*2+1]);
            //     } else {
            //         $hours_data[$i*2+1]->setOpen(false);
            //         $entityManager->persist($hours_data[$i*2+1]);
            //     }

            // }

            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenu pendant l\'enregistrement des éléments dans la base de données.');
            }


            $this->addFlash('success', 'Horaires modifiées avec succès');

            return $this->redirectToRoute('app_hours_index');
        }

        $hours_form->setData($data);

        return $this->render('admin/hours/edit.html.twig', [
            'hoursForm' => $hours_form->createView(),
            'labels' => $labels,
        ]);
    }


    // La méthode recherche dans un tableau datas
    // un index qui correspond à un objet Hours selon son
    // label et si il est du midi ou du dinner avec state
    private function searchInData( $datas, $state)
    {
        
        foreach($datas as $index => $data) {
            if($data->getLabel() == $state[0] && ($data->isLunch() ? 'lunch' : 'dinner') == $state[1] ) {
                return $index;
            }
        }

        return false;
    }
}
