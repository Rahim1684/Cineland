<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="float")
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     */
    private $dateSortie;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Regex(pattern="/^([0-9])|([1][0-9])|([2][0])$/",message="La note doit être comprise entre 0 et 20")
     */
    private $note;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Regex(pattern="/^([0-9])|([1][0-9])|([2][0])$/",message="La note doit être comprise entre 0 et 20")
     */
    private $ageMinimal;

    /**
     * @ORM\ManyToMany(targetEntity=Acteur::class, mappedBy="film_jouees")
     */
    private $acteur_joues;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="films",cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genre;

    public function __construct()
    {
        $this->acteur_joues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(float $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAgeMinimal(): ?int
    {
        return $this->ageMinimal;
    }

    public function setAgeMinimal(int $ageMinimal): self
    {
        $this->ageMinimal = $ageMinimal;

        return $this;
    }

    /**
     * @return Collection|Acteur[]
     */
    public function getActeurJoues(): Collection
    {
        return $this->acteur_joues;
    }

    public function addActeurJoue(Acteur $acteurJoue): self
    {
        if (!$this->acteur_joues->contains($acteurJoue)) {
            $this->acteur_joues[] = $acteurJoue;
            $acteurJoue->addFilmJouee($this);
        }

        return $this;
    }

    public function removeActeurJoue(Acteur $acteurJoue): self
    {
        if ($this->acteur_joues->removeElement($acteurJoue)) {
            $acteurJoue->removeFilmJouee($this);
        }

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
    
    public function __toString(){
        return $this->getTitre();
    }
}
