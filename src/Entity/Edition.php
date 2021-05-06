<?php

namespace App\Entity;

use App\Repository\EditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditionRepository::class)
 */
class Edition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $set_type;

    /**
     * @ORM\Column(type="date")
     */
    private $released_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $block_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $block;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $icon_svg_uri;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $languages = [];

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="edition", orphanRemoval=true)
     */
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getSetType(): ?string
    {
        return $this->set_type;
    }

    public function setSetType(string $set_type): self
    {
        $this->set_type = $set_type;

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

    public function getBlockCode(): ?string
    {
        return $this->block_code;
    }

    public function setBlockCode(?string $block_code): self
    {
        $this->block_code = $block_code;

        return $this;
    }

    public function getBlock(): ?string
    {
        return $this->block;
    }

    public function setBlock(?string $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getIconSvgUri(): ?string
    {
        return $this->icon_svg_uri;
    }

    public function setIconSvgUri(string $icon_svg_uri): self
    {
        $this->icon_svg_uri = $icon_svg_uri;

        return $this;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function setLanguages(array $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setEdition($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getEdition() === $this) {
                $card->setEdition(null);
            }
        }

        return $this;
    }
}
