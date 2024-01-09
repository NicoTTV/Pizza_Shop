<?php

namespace pizzashop\catalogue\domain\entities;

class Tarif extends \Illuminate\database\eloquent\Model {

    protected $table = 'tarif';
    protected $primaryKey = 'produit_id';
    public $timestamps = false;

    protected $connection = 'catalog';
    protected $fillable = ['taille_id', 'tarif'];

    function taille() {
        return $this->belongsTo(Taille::class, 'taille_id');
    }

}