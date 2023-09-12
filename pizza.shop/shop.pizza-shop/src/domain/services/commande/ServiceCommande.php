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
    public function accederCommande($UUID)
    {
        try {
            $commande = Commande::with('items')->findOrFail($UUID);

            echo $commande;
        } catch (ModelNotFoundException $e) {
            return throw new serviceCommandeNotFoundException();
        }

        $itemDTOs = [];
        foreach ($commande->items as $item) {
            $itemDTO = new ItemDTO($item->numero, $item->libelle, $item->taille, $item->tarif, $item->quantite);

            $itemDTOs[] = $itemDTO;
        }

        return new CommandeDTO($commande->id, $commande->date_commande,$commande->type_livraison, $commande->montant_total, $commande->delai, $commande->id_client, $itemDTOs);

    }

}