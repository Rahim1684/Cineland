<?php

namespace App\Entity;

use App\Repository\ActeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActeurRepository::class)
 */
class Acteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationalite;

    /**
     * @ORM\ManyToMany(targetEntity=Film::class, inversedBy="acteur_joues")
     */
    private $film_jouees;

    public function __construct()
    {
        $this->film_jouees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @return Collection|Film[]
     */
    public function getFilmJouees(): Collection
    {
        return $this->film_jouees;
    }

    public function addFilmJouee(Film $filmJouee): self
    {
        if (!$this->film_jouees->contains($filmJouee)) {
            $this->film_jouees[] = $filmJouee;
        }

        return $this;
    }

    public function removeFilmJouee(Film $filmJouee): self
    {
        $this->film_jouees->removeElement($filmJouee);

        return $this;
    }
    
    public function __toString(){
        return $this->getNomPrenom();
    }
}
