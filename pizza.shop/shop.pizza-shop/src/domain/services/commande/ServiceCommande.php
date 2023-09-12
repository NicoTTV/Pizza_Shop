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

        // Créer un objet CommandeDTO
        $commandeDTO = new CommandeDTO();
        $commandeDTO->id = $commande->id;
        $commandeDTO->date_commande = $commande->date_commande;
        $commandeDTO->type_livraison = $commande->type_livraison;
        $commandeDTO->montant_total = $commande->montant_total;
        $commandeDTO->delai = $commande->delai;

        // Créer une liste d'ItemDTO
        $itemDTOs = [];
        foreach ($commande->items as $item) {
            $itemDTO = new ItemDTO();
            $itemDTO->numero = $item->numero;
            $itemDTO->libelle = $item->libelle;
            $itemDTO->taille = $item->taille;
            $itemDTO->tarif = $item->tarif;
            $itemDTO->quantite = $item->quantite;
            $itemDTOs[] = $itemDTO;
        }


        $commandeDTO->items = $itemDTOs;
        return $commandeDTO;
    }

}