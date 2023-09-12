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
            $commande = Commande::findOrFail($UUID);

            // Créer un objet CommandeDTO
            $commandeDTO = new CommandeDTO();
            $commandeDTO->id = $commande->id;
            $commandeDTO->date_commande = $commande->date_commande;
            $commandeDTO->type_livraison = $commande->type_livraison;
            $commandeDTO->montant_total = $commande->montant_total;
            $commandeDTO->delai = $commande->delai;
            $commandeDTO->email_client = $commande->client->email; // Supposons que la relation client soit définie

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
            echo $commandeDTO;
            return $commandeDTO;
        } catch (ModelNotFoundException $e) {
            // Gérer le cas où la commande avec l'ID donné n'a pas été trouvée.
            return "Commande non trouvée.";
        }
    }

}