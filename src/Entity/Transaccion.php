<?php

namespace App\Entity;

use App\Repository\TransaccionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransaccionRepository::class)]
class Transaccion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transacciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Billetera $billetera = null;

    #[ORM\Column(length: 1, options: ["comment" => "I:Ingreso|E:egreso"])]
    private ?string $tipo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?float $monto = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $codigo_confirmacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sesion_id = null;

    #[ORM\Column(length: 1, options: ["comment" => "P:pendiente|C:confirmado|A:anulado"])]
    private ?string $estado = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTime $fecha_creacion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $fecha_confirmacion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $fecha_anulacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBilletera(): ?Billetera
    {
        return $this->billetera;
    }

    public function setBilletera(?Billetera $billetera): static
    {
        $this->billetera = $billetera;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): static
    {
        $this->monto = $monto;

        return $this;
    }

    public function getCodigoConfirmacion(): ?string
    {
        return $this->codigo_confirmacion;
    }

    public function setCodigoConfirmacion(?string $codigo_confirmacion): static
    {
        $this->codigo_confirmacion = $codigo_confirmacion;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sesion_id;
    }

    public function setSessionId(string $sesion_id): static
    {
        $this->sesion_id = $sesion_id;

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

    public function getFechaConfirmacion(): ?\DateTime
    {
        return $this->fecha_confirmacion;
    }

    public function setFechaConfirmacion(?\DateTime $fecha_confirmacion): static
    {
        $this->fecha_confirmacion = $fecha_confirmacion;

        return $this;
    }

    public function getFechaAnulacion(): ?\DateTime
    {
        return $this->fecha_anulacion;
    }

    public function setFechaAnulacion(?\DateTime $fecha_anulacion): static
    {
        $this->fecha_anulacion = $fecha_anulacion;

        return $this;
    }
}
