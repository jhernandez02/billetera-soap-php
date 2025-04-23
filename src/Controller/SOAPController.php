<?php

namespace App\Controller;

use App\Soap\ClienteSoapHandler;
use App\Soap\BilleteraSoapHandler;
use App\Service\ClienteService;
use App\Service\BilleteraService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SOAPController extends AbstractController{
    #[Route('/test', name: 'test')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }

    #[Route('/soap/cliente', name: 'soap_cliente')]
    public function cliente(Request $request,  ClienteService $clienteService): Response
    {
        $wsdl = __DIR__ . '/../Resources/wsdl/cliente.wsdl';
        $app_host = getenv('APP_HOST');

        $server = new \SoapServer($wsdl, [
            'uri' => $app_host.'/soap/cliente',
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $handler = new ClienteSoapHandler($clienteService);
        $server->setObject($handler);

        ob_start();
        $request->getContent();
        $server->handle();
        $response = ob_get_clean();

        return new Response($response, 200, ['Content-Type' => 'text/xml']);
    }

    #[Route('/soap/billetera', name: 'soap_billetera')]
    public function billetera(Request $request,  BilleteraService $billeteraService): Response
    {
        $wsdl = $this->getParameter('kernel.project_dir') . '/src/Resources/wsdl/billetera.wsdl';
        $app_host = getenv('APP_HOST');

        $server = new \SoapServer($wsdl, [
            'uri' => $app_host.'/soap/billetera',
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $handler = new BilleteraSoapHandler($billeteraService);     
        $server->setObject($handler);

        ob_start();
        $request->getContent();
        $server->handle();
        $response = ob_get_clean();

        return new Response($response, 200, ['Content-Type' => 'text/xml']);
    }
}
