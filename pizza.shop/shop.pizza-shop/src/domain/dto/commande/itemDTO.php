<?php

namespace pizzashop\shop\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;

class ItemDTO extends \pizzashop\shop\domain\dto\DTO{
    public $numero;
    public $libelle;
    public $taille;
    public $tarif;
    public $quantite;
    private $libelle_taille;


    public function __construct($numero, $taille, $quantite)
    {
        $this->numero = $numero;
        $this->taille = $taille;
        $this->quantite = $quantite;
    }


    public function __get($att){
        return $this->$att;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @param mixed $tarif
     */
    public function setTarif($tarif): void
    {
        $this->tarif = $tarif;
    }

    /**
     * @param mixed $libelle_taille
     */
    public function setLibelleTaille($libelle_taille): void
    {
        $this->libelle_taille = $libelle_taille;
    }



}