<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer extends User
{

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'customers')]
    private Collection $allergens;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $default_covers = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->allergens = new ArrayCollection();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setCustomer($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getCustomer() === $this) {
                $booking->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Allergen>
     */
    public function getCustomerAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addCustomerAllergen(Allergen $allergen): self
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    public function removeCustomerAllergen(Allergen $allergen): self
    {
        $this->allergens->removeElement($allergen);

        return $this;
    }

    public function getDefaultCovers(): ?int
    {
        return $this->default_covers;
    }

    public function setDefaultCovers(int $default_covers): self
    {
        $this->default_covers = $default_covers;

        return $this;
    }
}
