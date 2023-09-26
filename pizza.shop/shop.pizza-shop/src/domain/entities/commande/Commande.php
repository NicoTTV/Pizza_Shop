<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\commande\commandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;
    const ETAT_PAYE= 3;
    const ETAT_LIVRE = 4;

    const LIVRAISON_PLACE = 1;
    const LIVRAISON_EMPORTER = 2;
    const LIVRAISON_DOMICILE = 3;

    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'commande';

    public  $incrementing = false;
    public $keyType='string';

    function items(){
        return $this->hasMany(Item::class, 'commande_id');
    }

    function calculerMontantTotal() : float {
        $montant = 0;
        foreach ($this->items as $item){
            $montant = $this->tarif * $item->quantite;
        }
        $this->montant_total = $montant;
        $this->save();
        return $montant;
    }

    function toDTO() : commandeDTO {
        $commandeDTO = new CommandeDTO($this->mail_client, $this->type_livraison);
        $commandeDTO->id = $this->id;
        $commandeDTO->date_commande = ($this->date_commande) /*->format(Y-m-d H: i:s')*/;
        $commandeDTO->etat = $this->etat;
        $commandeDTO->montant = $this->motant_total;
        $commandeDTO->delai = $this->delai ?? 0;
        foreach ($this->items as $item) {
            $commandeDTO->addItem($item->toDTO());
        }
        return $commandeDTO;
    }
}