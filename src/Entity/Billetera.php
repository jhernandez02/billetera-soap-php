<?php

namespace App\Entity;

use App\Repository\BilleteraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BilleteraRepository::class)]
class Billetera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'billetera', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cliente $cliente = null;

    #[ORM\Column]
    private ?float $saldo = null;

    #[ORM\Column(length: 1, options: ["comment" => "A:activo|I:inactivo"])]
    private ?string $estado = null;

    #[ORM\Column]
    private ?\DateTime $fecha_creacion = null;

    /**
     * @var Collection<int, Transaccion>
     */
    #[ORM\OneToMany(targetEntity: Transaccion::class, mappedBy: 'billetera')]
    private Collection $transacciones;

    public function __construct()
    {
        $this->transacciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(float $saldo): static
    {
        $this->saldo = $saldo;

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

    /**
     * @return Collection<int, Transaccion>
     */
    public function getTransacciones(): Collection
    {
        return $this->transacciones;
    }

    public function addTransaccion(Transaccion $transaccion): static
    {
        if (!$this->transacciones->contains($transaccion)) {
            $this->transacciones->add($transaccion);
            $transaccion->setBilletera($this);
        }

        return $this;
    }

    public function removeTransaccion(Transaccion $transaccion): static
    {
        if ($this->transacciones->removeElement($transaccion)) {
            // set the owning side to null (unless already changed)
            if ($transaccion->getBilletera() === $this) {
                $transaccion->setBilletera(null);
            }
        }

        return $this;
    }
}
