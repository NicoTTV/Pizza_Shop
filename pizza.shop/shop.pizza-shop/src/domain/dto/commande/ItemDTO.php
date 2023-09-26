<?php

namespace pizzashop\shop\domain\dto\commande;

class ItemDTO extends \pizzashop\shop\domain\dto\DTO{
    public $numero;
    public $libelle;
    public $taille;
    public $tarif;
    public $quantite;
    private $libelleTaille;

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
     * @param mixed $libelleTaille
     */
    public function setLibelleTaille($libelleTaille): void
    {
        $this->libelleTaille = $libelleTaille;
    }


}