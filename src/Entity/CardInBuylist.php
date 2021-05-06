<?php

namespace App\Entity;

use App\Repository\CardInBuylistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardInBuylistRepository::class)
 */
class CardInBuylist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Buylist::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buylist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $card_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuylist(): ?Buylist
    {
        return $this->buylist;
    }

    public function setBuylist(?Buylist $buylist): self
    {
        $this->buylist = $buylist;

        return $this;
    }

    public function getCardName(): ?string
    {
        return $this->card_name;
    }

    public function setCardName(string $card_name): self
    {
        $this->card_name = $card_name;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
