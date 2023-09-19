<?php
namespace pizzashop\shop\domain\services\commande;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\dto\commande\itemDTO;
use pizzashop\shop\domain\entities\commande\Commande;

use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\services\exceptions\CreerCommandeException;
use pizzashop\src\domain\services\catalogue\ProduitIntrouvableException;
use pizzashop\src\domain\services\catalogue\ServiceCatalogue;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Rules\Date;

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

    private ServiceCatalogue $serviceCatalogue;

    public function __construct(ServiceCatalogue $serviceCatalogue) {
        $this->serviceCatalogue = $serviceCatalogue;
    }


    /**
     * @param commandeDTO $commandeDTO
     * @return commandeDTO
     * @throws CreerCommandeException
     * @throws ProduitIntrouvableException
     */
    public function creerCommande(CommandeDTO $commandeDTO) {
        $montant_total = 0;
        foreach ($commandeDTO->items as $item) {
            $infoProduit = $this->serviceCatalogue->getProduit($item->numero, $item->taille);
            $montant_total += $infoProduit->tarif * $item->quantite;
        }

        try {
            $newCommande = new Commande();
            $newCommande->id = Uuid::uuid4()->toString();
            $newCommande->date_commande = new Date('Y-m-d H:i:s');
            $newCommande->etat = Commande::ETAT_CREER;
            $newCommande->montant_total = $montant_total;
            $newCommande->id_client = $commandeDTO->email_client;
            $newCommande->saveOrFail();
        }catch (\Throwable $e) {
            throw new CreerCommandeException($e->getMessage());
        }
        $commandeDTO->id = $newCommande->id;
        $commandeDTO->delai = 0;
        $commandeDTO->montant = $montant_total;
        $commandeDTO->date_commande = $newCommande->date_commande;
        return $commandeDTO;
    }
}