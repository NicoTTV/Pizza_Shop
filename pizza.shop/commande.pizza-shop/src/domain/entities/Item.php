<?php

namespace pizzashop\commande\domain\entities;
use pizzashop\commande\domain\dto\ItemDTO;

class Item extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'commande';
    protected $fillable = ['numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    function toDTO(){
        $itemDTO = new ItemDTO($this->numero, $this->taille, $this->quantite);
        $itemDTO->setLibelle($this->libelle);
        $itemDTO->setTarif($this->tarif);
        $itemDTO->setLibelleTaille($this->libelle_taille);
        return $itemDTO;
    }
}