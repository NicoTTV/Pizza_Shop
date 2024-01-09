<?php

namespace pizzashop\catalogue\app\actions;

use pizzashop\catalogue\domain\services\catalogue\ServiceCatalogue;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AccederProduitAction extends AbstractAction
{
    private ServiceCatalogue $cata;
    public function __construct(ServiceCatalogue $comm)
    {
        $this->cata = $comm;
    }

    public function __invoke(Request $request, Response $response, $args): ResponseInterface {
        $id = $request->getAttribute('id_produit');
        $produit[] = $this->cata->getProduit($id);

        $data = $this->formaterCatalogue($produit, $request);
        $response->getBody()->write(json_encode($data));
        $respons = $response->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*')
            ->withStatus(200);

        return $respons;
    }

}