<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait LabelTrait
{

  #[ORM\Column(length: 100)]
  private ?string $label = null;

  public function getLabel(): ?string
  {
      return $this->label;
  }

  public function setLabel(string $label): self
  {
      $this->label = $label;

      return $this;
  }

}