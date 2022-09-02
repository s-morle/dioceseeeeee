<?php

namespace App\Entity;

use App\Repository\BienImmobilierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienImmobilierRepository::class)]
class BienImmobilier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $documents = null;


    #[ORM\Column(length: 255)]
    private ?string $bienImmobilier = null;


    #[ORM\ManyToOne(inversedBy: 'name')]
    private ?User $email = null;




    public function getId(): ?int

    {
        return $this->id;
    }

    public function getBienImmobilier(): ?string
    {
        return $this->bienImmobilier;
    }

    public function setBienImmobilier(string $bienImmobilier): self
    {
        $this->bienImmobilier = $bienImmobilier;

        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->documents;
    }

    public function setDocuments(string $documents): self
    {
        $this->documents = $documents;

        return $this;
    }





}
