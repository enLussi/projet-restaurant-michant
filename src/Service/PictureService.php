<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use function PHPUnit\Framework\throwException;

class PictureService
{

  public function __construct(private ParameterBagInterface $params) {}

  public function add(UploadedFile $picture, ?string $folder = "", ?int $width = 250, ?int $height = 250, ?int $cutXPos = 0, ?int $cutYPos = 0) 
  {
    // On donne un nouveau nom à l'image pour 
    // éviter que deux fichier se retrouve à avoir
    // le même nom par erreur.
    // on utilise uniqid
    $file_name = md5(uniqid(rand(), true)) . '.png';

    //on récupère les infos de l'image
    $infos = getimagesize($picture);

    // on récupère la taille de l'image source
    $pictureW = $infos[0]; $pictureH = $infos[1];

    if($infos === false) {
      throw new Exception('Un problème est survenu');
    }

    // si il n'y a pas eu d'erreur
    // on vérifie le format de l'image
    // et on renvoie un Exception si le format ne 
    // correspond pas.
    switch($infos['mime']) {
      case 'image/png':
        $source = imagecreatefrompng($picture);
        break;
      case 'image/jpeg':
        $source = imagecreatefromjpeg($picture);
        break;
      case 'image/webp':
        $source = imagecreatefromwebp($picture);
        break;
      default:
        throw new Exception('Format d\'image non valide');
    }

    // On vérifie que le redimensionnement voulu ne déborde
    // pas du cadre de l'image source, ou rectifie les "cut"
    // si nécessaire.
    if ( ($height + $cutYPos) > $pictureH ) { $cutYPos = $pictureH - $height; }
    if ( ($width + $cutXPos) > $pictureW ) { $cutXPos = $pictureW - $width; }

    // On crée l'image de destination
    $new_picture = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_picture, $source, 0, 0, $cutXPos, $cutYPos, $width, $height, $pictureW, $pictureH);


    // On crée le chemin pour la nouvelle image
    // oncrée le dossier de destination si nécessaire
    // et on enregistre l'image dans le dossier demandé
    $path = $this->params->get('images_directory') . $folder;

    if(!file_exists($path)) { mkdir($path, 0755, true); }

    imagepng($new_picture, $path . '/' . $file_name);

    return $file_name;
  }

  public function delete(string $file_name, ?string $folder = "") {

    //On initialise un booléen succès à false
    $success = false;

    // On récupère le chemin vers l'image
    $path = $this->params->get('images_directory') . $folder;
    $picture = $path . '/' . $file_name;

    // On vérifie si l'image existe à ce nom et
    // si oui on supprime l'image et on change 
    // success à true 
    if(file_exists($picture)) {
      unlink($picture);
      $success = true;
    }

    return $success;
  }
}