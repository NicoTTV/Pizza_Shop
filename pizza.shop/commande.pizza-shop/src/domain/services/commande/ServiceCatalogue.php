<?php

namespace pizzashop\commande\domain\services\commande;

use GuzzleHttp\Client;

class ServiceCatalogue implements iInfoProduit {

    public function getProduit(int $num) {
        $guzzle = new Client();
        echo "eheh 1";
        $response = $guzzle->request('GET', 'http://localhost:8080/produit/'.$num);
        echo "eheh 2";
        $produit = json_decode($response->getBody()->getContents());
        echo $produit;
    }
}