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

    public function registrarCliente(array $data): Cliente
    {
        $cliente = $this->clienteRepository->registrar($data);
        $this->billeteraRepository->registrar($cliente);

        return $cliente;
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
}
