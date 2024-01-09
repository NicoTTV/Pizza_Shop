<?php

namespace pizzashop\commande\domain\dto\catalogue;

class TarifDTO extends \pizzashop\commande\domain\dto\DTO
{
    public int $produit_id;
    public int $taille_id;
    public float $tarif;

    public $taille;

    /**
     * TarifDTO constructor.
     *
     * @param int $produit_id Identifiant du produit
     * @param int $taille_id Identifiant de la taille
     * @param float $tarif Montant du tarif
     */
    public function __construct(int $produit_id, int $taille_id, float $tarif)
    {
        $this->produit_id = $produit_id;
        $this->taille_id = $taille_id;
        $this->tarif = $tarif;
    }

    /**
     * Magique getter pour accéder aux propriétés de l'objet.
     *
     * @param string $name Nom de la propriété
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Magique setter pour assigner des valeurs aux propriétés de l'objet.
     *
     * @param string $name Nom de la propriété
     * @param mixed $value Valeur à assigner
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
