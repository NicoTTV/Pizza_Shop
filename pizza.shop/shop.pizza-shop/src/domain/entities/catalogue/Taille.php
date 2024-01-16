<?php

namespace pizzashop\shop\domain\entities\catalogue;

class Taille extends \Illuminate\Database\Eloquent\Model
{


    protected $table = 'taille';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [ 'libelle'];
    protected $connection = 'catalog';

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'tarif', 'taille_id', 'produit_id');
    }

}