<?php

namespace pizzashop\shop\domain\entities\commande;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public  $incrementing = false;
    public $keyType='string';

    function items(){
        return $this->hasMany(Item::class, 'commande_id');
    }
}