<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $documento = null;

    #[ORM\Column(length: 100)]
    private ?string $nombres = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    private ?string $celular = null;

    #[ORM\Column(length: 1, options: ["comment" => "A:activo|I:inactivo"])]
    private ?string $estado = null;

    #[ORM\Column]
    private ?\DateTime $fecha_creacion = null;

    #[ORM\OneToOne(mappedBy: 'cliente', cascade: ['persist', 'remove'])]
    private ?Billetera $billetera = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocumento(): ?string
    {
        return $this->documento;
    }

    public function setDocumento(string $documento): static
    {
        $this->documento = $documento;

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): static
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(string $celular): static
    {
        $this->celular = $celular;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTime $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getBilletera(): ?Billetera
    {
        return $this->billetera;
    }

    public function setBilletera(Billetera $billetera): static
    {
        // set the owning side of the relation if necessary
        if ($billetera->getCliente() !== $this) {
            $billetera->setCliente($this);
        }

        $this->billetera = $billetera;

        return $this;
    }
}
