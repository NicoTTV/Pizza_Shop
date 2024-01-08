<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\services\catalogue\ServiceCatalogue;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ListerProduitsAction extends AbstractAction
{

    private ServiceCatalogue $cata;

    /**
     * @param ServiceCatalogue $comm
     */
    public function __construct(ServiceCatalogue $comm)
    {
        $this->cata = $comm;
    }
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $produits = $this->cata->getAllProducts();
        $data = $this->formaterCatalogue($produits, $request);
        $response->getBody()->write(json_encode($data));
        //var_dump($data);
        $response->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*')
            ->withStatus(200);
        return $response;
    }
}