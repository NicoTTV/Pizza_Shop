<?php
namespace pizzashop\shop\domain\services\commande;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\dto\commande\itemDTO;
use pizzashop\shop\domain\entities\commande\Commande;

class ServiceCommande {
    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function accederCommande($UUID) {
        try {
            $commande = Commande::findOrFail($UUID);
        } catch (ModelNotFoundException $e) {
            return throw new serviceCommandeNotFoundException(`commande {$UUID} not found`);
        }
        return new CommandeDTO($commande->id, $commande->date_commande,);
    }

    function validerCommande(String $UUID){
        try{
            $commande = Commande::findOrFail($UUID);
        } catch (ModelNotFoundException $e) {
            throw new serviceCommandeNotFoundException(`commande {$UUID} not found`);
        }
        if ($commande->etat > Commande::ETAT_VALIDE){
            throw new ServiceCommandeInvalidException(`commande {$UUID} invalid`);
        }
        $commande->update(['etat' => Commande::ETAT_VALIDE]);
        return $commande->toDTO();
    }



}