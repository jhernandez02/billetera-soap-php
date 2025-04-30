<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use App\Repository\BilleteraRepository;
use App\Repository\TransaccionRepository;
use App\Helper\TokenHelper;
use App\Helper\SessionHelper;
use App\Service\NotificacionService;

class BilleteraService
{
    public function __construct(
        ClienteRepository $clienteRepository,
        BilleteraRepository $billeteraRepository,
        TransaccionRepository $transaccionRepository,
        SessionHelper $sessionHelper,
        NotificacionService $notificacionService,
    ) {
        $this->clienteRepository = $clienteRepository;
        $this->billeteraRepository = $billeteraRepository;
        $this->transaccionRepository = $transaccionRepository;
        $this->sessionHelper = $sessionHelper;
        $this->notificacionService = $notificacionService;
    }

    public function cargarSaldo(array $data): array
    {
        $cliente = $this->clienteRepository->buscarPorDocumentoCelular($data['documento'], $data['celular']);

        if (!$cliente) {
            throw new \Exception("Cliente no encontrado o no está activo");
        }

        $billetera = $cliente->getBilletera();
        
        if (!$billetera) {
            throw new \Exception("Billetera no encontrada o no está activa");
        }

        $nuevoSaldo = $this->billeteraRepository->actualizarSaldo($billetera, $data['valor']);

        $this->transaccionRepository->registrarCarga($billetera, $data['valor']);

        return [
            'nuevo_saldo' => $nuevoSaldo,
        ];
    }

    public function realizarCompra(array $data): array
    {
        $billeteraId = (int)$data['billetera_id'];
        $monto = $data['monto'];
        $url_host = $data['url_host'] ?? $_SERVER['APP_HOST_API_REST'];
        
        $billetera = $this->billeteraRepository->find($billeteraId);

        if (!$billetera) {
            throw new \Exception("Billetera no encontrada o no está activa");
        }

        if ($billetera->getSaldo() < $monto) {
            throw new \Exception('Saldo insuficiente');
        }

        $cliente = $billetera->getCliente();

        if (!$cliente) {
            throw new \Exception("Cliente no encontrado o no está activo");
        }

        $codigoConfirmacion = TokenHelper::generarCodigo();
        $sesionId = $this->sessionHelper->getId();

        $datosCompra = [
            'monto' => $monto,
            'codigo_confirmacion' => $codigoConfirmacion,
            'sesion_id' => $sesionId,
        ];
        
        $this->transaccionRepository->registrarCompra($billetera, $datosCompra);
        $mensaje = "Haz clic en este enlace para confirmar tu pago: $url_host/confirmarpago/$codigoConfirmacion/$sesionId";
        $this->notificacionService->enviarCorreo($cliente->getEmail(), 'Confirmar Pago', $mensaje);

        return $datosCompra;
    }

    public function confirmarCompra(array $data): array
    {
        $codigo = $data['codigo_confirmacion'];
        $sesionId = $data['sesion_id'];
        $transaccion = $this->transaccionRepository->findOneBy(['codigo_confirmacion'=>$codigo, 'estado'=>'P']);

        if (!$transaccion) {
            throw new \Exception("Transaccion no encontrada o no está activa");
        }

        $billetera = $transaccion->getBilletera();
        $monto = $transaccion->getMonto();
        // Se debita el monto de la billetera
        $nuevoSaldo = $this->billeteraRepository->actualizarSaldo($billetera, -($monto));

        $this->transaccionRepository->confirmarCompra($transaccion);

        return [
            'nuevo_saldo' => $nuevoSaldo,
        ];
    }

    public function consultarSaldo(array $data): array
    {
        $cliente = $this->clienteRepository->buscarPorDocumentoCelular($data['documento'], $data['celular']);

        if (!$cliente) {
            throw new \Exception("Cliente no encontrado o no está activo");
        }

        $billetera = $cliente->getBilletera();
        
        if (!$billetera) {
            throw new \Exception("Billetera no encontrada o no está activa");
        }

        return [
            'billetera_id' => $billetera->getId(),
            'saldo' => $billetera->getSaldo(),
            'cliente' => $cliente->getNombres(),
        ];
    }

    public function historialMovimientos(array $data)
    {
        $clienteId = (int)$data['cliente_id'];
        $cliente = $this->clienteRepository->find($clienteId);
        $billetera = $cliente->getBilletera();
        $transacciones = $billetera->getTransacciones();
        $result = [];
        
        if ($transacciones) {
            foreach($transacciones as $transaccion){
                $result[] = [
                    'tipo' => $transaccion->getTipo(),
                    'descripcion' => $transaccion->getDescripcion(),
                    'monto' => $transaccion->getMonto(),
                    'estado' => $transaccion->getEstado(),
                ];
            }
        }
        return $result;
    }
}
