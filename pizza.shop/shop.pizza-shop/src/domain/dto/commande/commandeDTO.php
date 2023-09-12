<?php

namespace pizzashop\shop\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;

class commandeDTO extends \pizzashop\shop\domain\dto\DTO{

    private String $id;
    private  String $date_commande;
    private Int $type_date;
    private Double $montant;
    private Int $delai;
    private String $emial_client;
    private array $items;

    public function __get($att){
        return $this->$att;
    }
}