<?php

namespace pizzashop\commande\domain\dto\commande;

class ItemDTO extends \pizzashop\commande\domain\dto\DTO{
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


    public function __set(string $name, $value): void
    {
        $this->$name = $value;
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