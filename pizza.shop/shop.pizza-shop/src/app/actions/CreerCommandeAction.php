<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\dto\commande\ItemDTO;
use pizzashop\shop\domain\services\commande\ServiceCommande;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CreerCommandeAction extends AbstractAction {
    private ServiceCommande $comm;

    /**
     * @param ServiceCommande $comm
     */
    public function __construct(ServiceCommande $comm)
    {
        $this->comm = $comm;
    }

    public function __invoke (Request $request,Response $response, $args): ResponseInterface {
        $data = json_decode($request->getBody()->getContents(), true);
        $commandeDTO = new CommandeDTO($data['mail_client'],$data['type_livraison']);
        foreach ($data['items'] as $item){
            $commandeDTO->addItem(new ItemDTO($item['numero'], $item['taille'], $item['quantite']));
        }
        $commande = $this->comm->creerCommande($commandeDTO);
        var_dump($commande);
        $api = $this->formaterCommande($commande);
        $response->getBody()->write(json_encode($api));

        return
            $response->withHeader('Content-Type','application/json')
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withStatus(200);
            }

}