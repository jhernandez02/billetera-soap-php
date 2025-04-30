<?php

namespace App\Soap;

use Symfony\Component\HttpFoundation\Request;
use App\Service\BilleteraService;
use App\Helper\SoapHelper;

class BilleteraSoapHandler
{
    public function __construct(
        BilleteraService $service
    ){
        $this->service = $service;
    }

    public function cargarSaldo($content): array
    {
        $data = SoapHelper::toArray($content);

        try {
            $result = $this->service->cargarSaldo($data);

            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'nuevo_saldo' => $result['nuevo_saldo'],
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

    public function realizarCompra($content): array
    {
        $data = SoapHelper::toArray($content);
        try {
            $result = $this->service->realizarCompra($data);
            
            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'url_host' => $result['url_host'],
                    'codigo_confirmacion' => $result['codigo_confirmacion'],
                    'sesion_id' => $result['sesion_id'],
                    'monto_compra' => $result['monto'],
                    'mensaje' => 'Compra generada exitosamente',
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

    public function confirmarCompra($content): array
    {
        $data = SoapHelper::toArray($content);
        try {
            $result = $this->service->confirmarCompra($data);
            
            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'nuevo_saldo' => $result['nuevo_saldo'],
                    'mensaje' => 'Compra confirmada exitosamente',
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

    public function consultarSaldo($content): array
    {
        $data = SoapHelper::toArray($content);
        
        try {
            $result = $this->service->consultarSaldo($data);

            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => SoapHelper::toSoapMap([
                    'billetera_id' => $result['billetera_id'],
                    'saldo' => $result['saldo'],
                    'cliente' => $result['cliente'],
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

    public function consultarHistorial($content): array
    {
        $data = SoapHelper::toArray($content);
        
        try {
            $result = $this->service->historialMovimientos($data);
            $response = [
                'success'=> true,
                'cod_error' => '00',
                'message_error' => '',
                'data' => $result,
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
