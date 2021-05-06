<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $auth_key;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $languages = [];

    /**
     * @ORM\OneToMany(targetEntity=Buylist::class, mappedBy="user", orphanRemoval=true)
     */
    private $buylists;

    /**
     * @ORM\OneToMany(targetEntity=Deck::class, mappedBy="user", orphanRemoval=true)
     */
    private $decks;

    public function __construct()
    {
        $this->buylists = new ArrayCollection();
        $this->decks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function setAuthKey(?string $auth_key): self
    {
        $this->auth_key = $auth_key;

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
     * @return Collection|Buylist[]
     */
    public function getBuylists(): Collection
    {
        return $this->buylists;
    }

    public function addBuylist(Buylist $buylist): self
    {
        if (!$this->buylists->contains($buylist)) {
            $this->buylists[] = $buylist;
            $buylist->setUser($this);
        }

        return $this;
    }

    public function removeBuylist(Buylist $buylist): self
    {
        if ($this->buylists->removeElement($buylist)) {
            // set the owning side to null (unless already changed)
            if ($buylist->getUser() === $this) {
                $buylist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Deck[]
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Deck $deck): self
    {
        if (!$this->decks->contains($deck)) {
            $this->decks[] = $deck;
            $deck->setUser($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): self
    {
        if ($this->decks->removeElement($deck)) {
            // set the owning side to null (unless already changed)
            if ($deck->getUser() === $this) {
                $deck->setUser(null);
            }
        }

        return $this;
    }
}
