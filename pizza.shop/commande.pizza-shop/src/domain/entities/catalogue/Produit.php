<?php

namespace pizzashop\commande\domain\entities\catalogue;

use pizzashop\commande\domain\dto\catalogue\ProduitDTO;

class Produit extends \Illuminate\database\eloquent\Model
{
    protected $connection = 'catalog';
    protected $table = 'produit';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['numero', 'libelle', 'description','image'];

    public function categorie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function tailles()
    {
        return $this->belongsToMany(Taille::class, 'produit_taille', 'produit_id', 'taille_id');
    }

    function tarif()
    {
        return $this->hasMany(Tarif::class, 'produit_id');
    }
}