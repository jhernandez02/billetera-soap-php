<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;

/**
 * @extends ServiceEntityRepository<Cliente>
 */
class ClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManager();
        parent::__construct($registry, Cliente::class);
    }

    public function registrar(array $data): Cliente
    {
        $passwordHasher = new NativePasswordHasher();
        $passwordEncriptada = $passwordHasher->hash($data['documento']);
        
        $cliente = new Cliente();
        $cliente->setDocumento($data['documento']);
        $cliente->setNombres($data['nombres']);
        $cliente->setEmail($data['email']);
        $cliente->setPassword($passwordEncriptada);
        $cliente->setCelular($data['celular']);
        $cliente->setEstado('A');
        $cliente->setFechaCreacion(new \DateTime());
        $this->em->persist($cliente);
        $this->em->flush();

        return $cliente;
    }

    public function buscarPorDocumentoCelular($documento, $celular): Cliente
    {
        $cliente = $this->findOneBy(['documento'=>$documento, 'celular'=>$celular, 'estado' => 'A']);
        
        return $cliente;
    }
}
