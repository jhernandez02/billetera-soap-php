<?php

namespace App\DataFixtures;

use App\Entity\Cliente;
use App\Entity\Billetera;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;

class AppFixtures extends Fixture
{
    
    private NativePasswordHasher $passwordHasher;

    public function __construct()
    {
        $this->passwordHasher = new NativePasswordHasher();
    }

    public function load(ObjectManager $manager): void
    {
        $fecha_creacion = new \DateTime();

        $clientes = [
            ['documento'=>'11111111', 'nombres'=>'Bart Simpson', 'email'=>'bart@mail.com', 'celular'=>'911111111'],
            ['documento'=>'22222222', 'nombres'=>'Homero Simpson', 'email'=>'homero@mail.com', 'celular'=>'922222222'],
            ['documento'=>'33333333', 'nombres'=>'Lisa Simpson', 'email'=>'lisa@mail.com', 'celular'=>'933333333'],
        ];

        foreach($clientes as $c){
            $hashedPassword = $this->passwordHasher->hash($c['documento']);

            $cliente = new Cliente();
            $cliente->setDocumento($c['documento']);
            $cliente->setNombres($c['nombres']);
            $cliente->setEmail($c['email']);
            $cliente->setPassword($hashedPassword);
            $cliente->setCelular($c['celular']);
            $cliente->setFechaCreacion($fecha_creacion);
            $cliente->setEstado('A');

            $manager->persist($cliente);
            $manager->flush();
        }

        
        
        $billeteras = [
            ['cliente_id'=>1],
            ['cliente_id'=>2],
            ['cliente_id'=>3],
        ];

        foreach($billeteras as $b){
            $cliente = $manager->getRepository(Cliente::class)->find($b['cliente_id']);
            $billetera = new Billetera();
            $billetera->setCliente($cliente);
            $billetera->setSaldo(200);
            $billetera->setFechaCreacion($fecha_creacion);
            $billetera->setEstado('A');

            $manager->persist($billetera);
            $manager->flush();
        }
    }
}
