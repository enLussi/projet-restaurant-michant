<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait TitleTrait
{

  #[ORM\Column(length: 100)]
  private ?string $title = null;

  public function getTitle(): ?string
  {
      return $this->title;
  }

  public function setTitle(string $title): self
  {
      $this->title = $title;

      return $this;
  }

}