<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait ProductTrait
{

  #[ORM\Column(type: Types::TEXT)]
  private ?string $summary = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $price = null;

  public function getSummary(): ?string
  {
      return $this->summary;
  }

  public function setSummary(string $summary): self
  {
      $this->summary = $summary;

      return $this;
  }

  public function getPrice(): ?int
  {
      return $this->price;
  }

  public function setPrice(int $price): self
  {
      $this->price = $price;

      return $this;
  }
}