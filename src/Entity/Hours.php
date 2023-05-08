<?php

namespace App\Entity;

use App\Repository\HoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoursRepository::class)]
class Hours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $label_day = null;

    #[ORM\Column(length: 5)]
    private ?string $opening = null;

    #[ORM\Column(length: 5)]
    private ?string $closure = null;

    #[ORM\Column]
    private ?bool $open = null;

    #[ORM\Column]
    private ?bool $lunch = null;

    #[ORM\Column(length: 20)]
    private ?string $label = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $max_booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelDay(): ?string
    {
        return $this->label_day;
    }

    public function setLabelDay(string $label_day): self
    {
        $this->label_day = $label_day;

        return $this;
    }

    public function getOpening(): ?string
    {
        return $this->opening;
    }

    public function setOpening(string $opening): self
    {
        $this->opening = $opening;

        return $this;
    }

    public function getClosure(): ?string
    {
        return $this->closure;
    }

    public function setClosure(string $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function isLunch(): ?bool
    {
        return $this->lunch;
    }

    public function setLunch(bool $lunch): self
    {
        $this->lunch = $lunch;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getMaxBooking(): ?int
    {
        return $this->max_booking;
    }

    public function setMaxBooking(int $max_booking): self
    {
        $this->max_booking = $max_booking;

        return $this;
    }
}
