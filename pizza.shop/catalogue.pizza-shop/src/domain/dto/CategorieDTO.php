<?php

namespace pizzashop\catalogue\domain\dto;

class CategorieDTO extends DTO
{
    public int $id;
    public string $libelle;

    /**
     * CategorieDTO constructor.
     *
     * @param int $id Identifiant de la catégorie
     * @param string $libelle Libellé de la catégorie
     */
    public function __construct(int $id, string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
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
