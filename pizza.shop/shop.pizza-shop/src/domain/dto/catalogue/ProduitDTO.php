<?php

namespace pizzashop\shop\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\shop\domain\dto\DTO
{

    public int $numero_produit;
    public string $libelle_produit;
    public string $image;

    public int $categorie_id;

    public function __construct(int $numero_produit, string $libelle_produit, $idCat)
    {
        $this->numero_produit = $numero_produit;
        $this->libelle_produit = $libelle_produit;
        $this->categorie_id = $idCat;
    }

    function __get($name){
        return $this->$name;
    }

    function __set($name, $value){
        $this->$name = $value;
    }




}