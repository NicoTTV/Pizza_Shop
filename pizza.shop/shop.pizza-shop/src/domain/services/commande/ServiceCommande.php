<?php
namespace pizzashop\shop\domain\services\commande;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Logging\Exception;
use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\services\exceptions\CreerCommandeException;
use pizzashop\shop\domain\services\catalogue\ServiceCatalogue;
use pizzashop\shop\domain\services\exceptions\ProduitIntrouvableException;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Rules\Date;

class ServiceCommande {

    private ServiceCatalogue $serviceCatalogue;

    public function __construct(ServiceCatalogue $serviceCatalogue) {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function accederCommande($UUID) {
        try {
            $commande = Commande::findOrFail($UUID);
        } catch (ModelNotFoundException $e) {
            throw new serviceCommandeNotFoundException(`commande {$UUID} not found`);
        }
        return $commande->toDTO();
    }

    function validerCommande(String $UUID){
        try{
            $commande = Commande::where('id', $UUID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new serviceCommandeNotFoundException(`commande {$UUID} not found`);
        }
        if ($commande->etat > Commande::ETAT_VALIDE){
            throw new ServiceCommandeInvalidException(`commande {$UUID} invalid`);
        }
        $commande->etat = Commande::ETAT_VALIDE;
        $commande->save();
        return $commande->toDTO();
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
            $newCommande->date_commande = \date("Y-m-d h:i:s");
            $newCommande->etat = Commande::ETAT_CREE;
            $newCommande->montant_total = $montant_total;
            $newCommande->mail_client = $commandeDTO->email_client;
            $newCommande->type_livraison = $commandeDTO->type_livraison;
            $newCommande->saveOrFail();
        }catch (\Throwable | Exception $e) {
            throw new CreerCommandeException($e->getMessage());
        }
        $commandeDTO->id = $newCommande->id;
        $commandeDTO->delai = 0;
        $commandeDTO->montant = $montant_total;
        $commandeDTO->date_commande = $newCommande->date_commande;
        return $commandeDTO;
    }
}