<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountOfTickets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaidFor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getAmountOfTickets(): ?int
    {
        return $this->amountOfTickets;
    }

    public function setAmountOfTickets(int $amountOfTickets): self
    {
        $this->amountOfTickets = $amountOfTickets;

        return $this;
    }

    public function getIsPaidFor(): ?bool
    {
        return $this->isPaidFor;
    }

    public function setIsPaidFor(bool $isPaidFor): self
    {
        $this->isPaidFor = $isPaidFor;

        return $this;
    }
}
