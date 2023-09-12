<?php

namespace pizzashop\shop\domain\dto\commande;

use pizzashop\shop\domain\dto\DTO;

class CommandeDTO extends DTO
{
    public $id;
    public $date_commande;
    public $type_livraison;
    public $montant_total;
    public $delai;
    public $email_client;
    public $items; // Une liste d'ItemDTO

    public function __get($att){
        return $this->$att;
    }
}