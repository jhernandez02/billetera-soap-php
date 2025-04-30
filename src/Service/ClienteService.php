<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use App\Repository\BilleteraRepository;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;

class ClienteService
{
    public function __construct(
        ClienteRepository $clienteRepository,
        BilleteraRepository $billeteraRepository,
    ) {
        $this->clienteRepository = $clienteRepository;
        $this->billeteraRepository = $billeteraRepository;
    }

    public function registrarCliente(array $data): ?Cliente
    {
        $documento = $data['documento'];
        $email = $data['email'];

        $cliente = $this->clienteRepository->createQueryBuilder('c')
            ->where('c.documento = :documento')
            ->orWhere('c.email = :email')
            ->setParameter('documento', $documento)
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if ($cliente) {
            throw new \Exception("El documento o correo ya ha sido registrado");
        }

        $nuevoCliente = $this->clienteRepository->registrar($data);
        $this->billeteraRepository->registrar($nuevoCliente);
        
        return $nuevoCliente;
    }

    public function loginCliente(array $data): ?Cliente
    {
        $email = $data['email'];
        $password = $data['password'];
        
        $cliente = $this->clienteRepository->findOneBy(['email'=>$email, 'estado' => 'A']);
        
        if (!$cliente) {
            throw new \Exception("Credenciales incorrectas - 001");
        }

        $passwordHasher = new NativePasswordHasher();

        if ($passwordHasher->verify($cliente->getPassword(), $password)) {
            return $cliente;
        } else {
            throw new \Exception("Credenciales incorrectas - 002");
        }
    }

    public function datosCliente(array $data): ?Cliente
    {
        $idcliente = $data['id'];
        $cliente = $this->clienteRepository->find($idcliente);

        if (!$cliente) {
            throw new \Exception("El usuario no existe");
        }

        return $cliente;
    }
}
