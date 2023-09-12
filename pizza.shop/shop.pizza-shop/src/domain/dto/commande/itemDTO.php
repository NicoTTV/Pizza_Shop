<?php

namespace pizzashop\shop\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;

class itemDTO extends \pizzashop\shop\domain\dto\DTO{
    public $numero;
    public $libelle;
    public $taille;
    public $tarif;
    public $quantite;

    public function __get($att){
        return $this->$att;
    }
}