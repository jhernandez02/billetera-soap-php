<?php

namespace App\Soap;

use Symfony\Component\HttpFoundation\Request;
use App\Service\ClienteService;
use App\Helper\SoapHelper;

class ClienteSoapHandler
{
    public function __construct(
        ClienteService $service, 
    ){
        $this->service = $service;
    }

    public function registrarCliente($content): array
    {
        $data = SoapHelper::toArray($content);
        
        try {
            $cliente = $this->service->registrarCliente($data);

            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'id' => $cliente->getId(),
                    'documento' => $cliente->getDocumento(),
                    'nombres' => $cliente->getNombres(),
                    'email' => $cliente->getEmail(),
                    'celular' => $cliente->getCelular(),
                    'estado' => $cliente->getEstado(),
                ]),
            ];

            return $response;
        } catch (\Exception $e) {
            return [
                'success'=> false,
                'cod_error' => '500',
                'message_error' => $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function loginCliente($content): array
    {
        $data = SoapHelper::toArray($content);
        
        try {
            $cliente = $this->service->loginCliente($data);
            
            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'id' => $cliente->getId(),
                    'email' => $cliente->getEmail(),
                ]),
            ];

            return $response;
        } catch (\Exception $e) {
            return [
                'success'=> false,
                'cod_error' => '500',
                'message_error' => $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function datosCliente($content): array
    {
        $data = SoapHelper::toArray($content);
        
        try {
            $cliente = $this->service->datosCliente($data);
            $billetera = $cliente->getBilletera();
            
            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'id' => $cliente->getId(),
                    'documento' => $cliente->getDocumento(),
                    'nombres' => $cliente->getNombres(),
                    'email' => $cliente->getEmail(),
                    'celular' => $cliente->getCelular(),
                    'estado' => $cliente->getEstado(),
                    'billetera_id' => $billetera->getId(),
                    'saldo' => $billetera->getSaldo(),
                ]),
            ];

            return $response;
        } catch (\Exception $e) {
            return [
                'success'=> false,
                'cod_error' => '500',
                'message_error' => $e->getMessage(),
                'data' => [],
            ];
        }
    }
}
