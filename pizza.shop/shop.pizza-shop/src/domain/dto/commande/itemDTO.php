<?php

namespace pizzashop\shop\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;

class itemDTO extends \pizzashop\shop\domain\dto\DTO{
    private Int $numero;
    private String $libelle;
    private Int $taille;
    private Double $tarif;
    private Int $quantite;

    public function __get($att){
        return $this->$att;
    }
}