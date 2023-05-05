<?php

namespace App\EventListener;

use App\Repository\HoursRepository;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class DefaultDataListener
{

  public function __construct(private HoursRepository $hoursRepository){}

  public function onKernelResponse(ResponseEvent $event) {

    $response = $event->getResponse();
    $message = $this->setFooterMessage();

    $response->setContent(str_replace("{ footer_hours_message }", $message, $response->getContent()));
  }

  private function setFooterMessage(){

    $all_hours = $this->hoursRepository->findAll();

    $message = "Restaurant fermé ";

    for($week_index = 0; $week_index < 14; $week_index += 2) {

      if(!$all_hours[$week_index]->isOpen() && !$all_hours[$week_index+1]->isOpen()) 
        $message .= "le " . $all_hours[$week_index]->getLabelDay() . ", ";
      elseif(!$all_hours[$week_index]->isOpen()) 
        $message .= "le " . $all_hours[$week_index]->getLabelDay() . " matin, ";
      elseif(!$all_hours[$week_index+1]->isOpen()) 
        $message .= "le " . $all_hours[$week_index]->getLabelDay() . " soir, ";

    }

    $message = $this->replaceCharacter($message, ',', '.');
    $message = $this->replaceCharacter($message, ',', ' et');

    return $message;

  }

  private function replaceCharacter(string $text, string $target, string $replacer): string
  {
    $comma_pos = strrpos($text, $target);
    $message = substr_replace($text, $replacer, $comma_pos, 1);

    return $message;
  }
}