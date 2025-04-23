<?php

namespace App\Repository;

use App\Entity\Billetera;
use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Billetera>
 */
class BilleteraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManager();
        parent::__construct($registry, Billetera::class);
    }

    public function registrar(Cliente $cliente): Billetera
    {
        $billetera = new Billetera();
        $billetera->setSaldo(0.0);
        $billetera->setEstado('A');
        $billetera->setFechaCreacion(new \DateTime());
        $billetera->setCliente($cliente);
        
        $this->em->persist($billetera);
        $this->em->flush();

        return $billetera;
    }

    public function actualizarSaldo(Billetera $billetera, $monto): Float
    {
        $billetera->setSaldo($billetera->getSaldo() + $monto);

        $this->em->persist($billetera);
        $this->em->flush();

        return $billetera->getSaldo();
    }
}
