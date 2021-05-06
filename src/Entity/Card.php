<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Edition::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $collector_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $cmc;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $color_identity = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rarity;

    /**
     * @ORM\Column(type="date")
     */
    private $released_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_reprint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $layout;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $border_color;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price_usd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price_eur;

    /**
     * @ORM\OneToMany(targetEntity=Face::class, mappedBy="card", orphanRemoval=true)
     */
    private $faces;

    /**
     * @ORM\OneToMany(targetEntity=Legality::class, mappedBy="card", orphanRemoval=true)
     */
    private $legalities;

    public function __construct()
    {
        $this->faces = new ArrayCollection();
        $this->legalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    public function getCollectorNumber(): ?string
    {
        return $this->collector_number;
    }

    public function setCollectorNumber(string $collector_number): self
    {
        $this->collector_number = $collector_number;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCmc(): ?float
    {
        return $this->cmc;
    }

    public function setCmc(float $cmc): self
    {
        $this->cmc = $cmc;

        return $this;
    }

    public function getColorIdentity(): ?array
    {
        return $this->color_identity;
    }

    public function setColorIdentity(array $color_identity): self
    {
        $this->color_identity = $color_identity;

        return $this;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeInterface
    {
        return $this->released_at;
    }

    public function setReleasedAt(\DateTimeInterface $released_at): self
    {
        $this->released_at = $released_at;

        return $this;
    }

    public function getIsReprint(): ?bool
    {
        return $this->is_reprint;
    }

    public function setIsReprint(bool $isReprint): self
    {
        $this->is_reprint = $isReprint;

        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getPriceUsd(): ?float
    {
        return $this->price_usd;
    }

    public function setPriceUsd(?float $price_usd): self
    {
        $this->price_usd = $price_usd;

        return $this;
    }

    public function getPriceEur(): ?float
    {
        return $this->price_eur;
    }

    public function setPriceEur(?float $price_eur): self
    {
        $this->price_eur = $price_eur;

        return $this;
    }

    /**
     * @return Collection|Face[]
     */
    public function getFaces(): Collection
    {
        return $this->faces;
    }

    public function addFace(Face $face): self
    {
        if (!$this->faces->contains($face)) {
            $this->faces[] = $face;
            $face->setCard($this);
        }

        return $this;
    }

    public function removeFace(Face $face): self
    {
        if ($this->faces->removeElement($face)) {
            // set the owning side to null (unless already changed)
            if ($face->getCard() === $this) {
                $face->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Legality[]
     */
    public function getLegalities(): Collection
    {
        return $this->legalities;
    }

    public function addLegality(Legality $legality): self
    {
        if (!$this->legalities->contains($legality)) {
            $this->legalities[] = $legality;
            $legality->setCard($this);
        }

        return $this;
    }

    public function removeLegality(Legality $legality): self
    {
        if ($this->legalities->removeElement($legality)) {
            // set the owning side to null (unless already changed)
            if ($legality->getCard() === $this) {
                $legality->setCard(null);
            }
        }

        return $this;
    }
}
