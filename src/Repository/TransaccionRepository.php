<?php

namespace App\Repository;

use App\Entity\Transaccion;
use App\Entity\Billetera;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaccion>
 */
class TransaccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManager();
        parent::__construct($registry, Transaccion::class);
    }

    public function registrarCarga(Billetera $billetera, Float $monto): void
    {
        $transaccion = new Transaccion();
        $transaccion->setBilletera($billetera);
        $transaccion->setTipo('I'); // Tipo "Ingreso"
        $transaccion->setDescripcion('Cargo de saldo');
        $transaccion->setMonto($monto);
        $transaccion->setEstado('C'); // Confirmado
        $transaccion->setFechaCreacion(new \DateTime());
        $transaccion->setFechaConfirmacion(new \DateTime());
        
        $this->em->persist($transaccion);
        $this->em->flush();
    }

    public function registrarCompra(Billetera $billetera, array $data): void
    {
        $transaccion = new Transaccion();
        $transaccion->setBilletera($billetera);
        $transaccion->setTipo('E'); // Tipo "Egreso"
        $transaccion->setDescripcion('Pago compra');
        $transaccion->setMonto($data['monto']);
        $transaccion->setCodigoConfirmacion($data['codigo_confirmacion']);
        $transaccion->setSessionId($data['sesion_id']);
        $transaccion->setEstado('P'); // Pendiente
        $transaccion->setFechaCreacion(new \DateTime());
        
        $this->em->persist($transaccion);
        $this->em->flush();
    }

    public function confirmarCompra(Transaccion $transaccion): void
    {
        $transaccion->setEstado('C');
        
        $this->em->persist($transaccion);
        $this->em->flush();
    }

//    /**
//     * @return Transaccion[] Returns an array of Transaccion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaccion
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
