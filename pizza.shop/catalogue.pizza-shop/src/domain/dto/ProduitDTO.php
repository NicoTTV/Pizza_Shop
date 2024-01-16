<?php

namespace pizzashop\catalogue\domain\dto;

class ProduitDTO extends DTO
{

    public int $numero_produit;
    public string $libelle_produit;
    public string $image;

    public CategorieDTO $categorie;
    public array $tarifs = [];

    public function __construct(int $numero_produit, string $libelle_produit, $categ)
    {
        $this->numero_produit = $numero_produit;
        $this->libelle_produit = $libelle_produit;
        $this->categorie = $categ;
    }

    function __get($name){
        return $this->$name;
    }

    function __set($name, $value){
        $this->$name = $value;
    }




}