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
            //['documento'=>'44444444', 'nombres'=>'Marge Simpson', 'email'=>'marge@mail.com', 'celular'=>'944444444'],
            //['documento'=>'55555555', 'nombres'=>'Ned Flanders', 'email'=>'ned@mail.com', 'celular'=>'955555555'],
            //['documento'=>'66666666', 'nombres'=>'Magie Simpson', 'email'=>'magie@mail.com', 'celular'=>'66666666'],
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

        for($i=1; $i<=count($clientes); $i++){
            $cliente = $manager->getRepository(Cliente::class)->find($i);
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
