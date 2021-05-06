<?php

namespace App\Entity;

use App\Repository\FaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaceRepository::class)
 */
class Face
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="faces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    /**
     * @ORM\Column(type="integer")
     */
    private $face_index;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mana_cost;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_line;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $oracle_text;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $flavor_text;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $toughness;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $loyalty;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $watermark;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $image_uri_normal;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $image_uri_png;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getFaceIndex(): ?int
    {
        return $this->face_index;
    }

    public function setFaceIndex(int $face_index): self
    {
        $this->face_index = $face_index;

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

    public function getManaCost(): ?string
    {
        return $this->mana_cost;
    }

    public function setManaCost(string $mana_cost): self
    {
        $this->mana_cost = $mana_cost;

        return $this;
    }

    public function getTypeLine(): ?string
    {
        return $this->type_line;
    }

    public function setTypeLine(string $type_line): self
    {
        $this->type_line = $type_line;

        return $this;
    }

    public function getOracleText(): ?string
    {
        return $this->oracle_text;
    }

    public function setOracleText(?string $oracle_text): self
    {
        $this->oracle_text = $oracle_text;

        return $this;
    }

    public function getFlavorText(): ?string
    {
        return $this->flavor_text;
    }

    public function setFlavorText(?string $flavor_text): self
    {
        $this->flavor_text = $flavor_text;

        return $this;
    }

    public function getPower(): ?string
    {
        return $this->power;
    }

    public function setPower(?string $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getToughness(): ?string
    {
        return $this->toughness;
    }

    public function setToughness(?string $toughness): self
    {
        $this->toughness = $toughness;

        return $this;
    }

    public function getLoyalty(): ?string
    {
        return $this->loyalty;
    }

    public function setLoyalty(?string $loyalty): self
    {
        $this->loyalty = $loyalty;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getWatermark(): ?string
    {
        return $this->watermark;
    }

    public function setWatermark(?string $watermark): self
    {
        $this->watermark = $watermark;

        return $this;
    }

    public function getImageUriNormal(): ?string
    {
        return $this->image_uri_normal;
    }

    public function setImageUriNormal(?string $image_uri_normal): self
    {
        $this->image_uri_normal = $image_uri_normal;

        return $this;
    }

    public function getImageUriPng(): ?string
    {
        return $this->image_uri_png;
    }

    public function setImageUriPng(?string $image_uri_png): self
    {
        $this->image_uri_png = $image_uri_png;

        return $this;
    }
}
