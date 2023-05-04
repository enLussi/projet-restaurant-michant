<?php

namespace App\Entity;

use App\Entity\Trait\LabelTrait;
use App\Repository\AllergenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllergenRepository::class)]
class Allergen
{

    use LabelTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'allergens')]
    private Collection $customers;

    #[ORM\ManyToMany(targetEntity: Booking::class, inversedBy: 'allergens')]
    private Collection $bookings;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->addCustomerAllergen($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removeCustomerAllergen($this);
        }

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
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        $this->bookings->removeElement($booking);

        return $this;
    }
}
