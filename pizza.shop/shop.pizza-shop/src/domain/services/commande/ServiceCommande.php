<?php
namespace pizzashop\shop\domain\services\commande;

use DI\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Logging\Exception;
use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\services\exceptions\CreerCommandeException;
use pizzashop\shop\domain\services\catalogue\ServiceCatalogue;
use pizzashop\shop\domain\services\exceptions\ProduitIntrouvableException;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeEnregistrementException;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeNotFoundException;
use pizzashop\shop\domain\services\exceptions\ServiceUnvalidDataException;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\NullableException;
use Respect\Validation\Validator as v;

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
            throw new ServiceCommandeNotFoundException("commande not found");
        }
        return $commande->toDTO();
    }

    /**
     * @throws ServiceCommandeInvalidException
     * @throws ServiceCommandeNotFoundException
     * @throws ServiceCommandeEnregistrementException
     */
    function validerCommande(String $UUID){
        try {
            $commande = Commande::where('id', $UUID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ServiceCommandeNotFoundException("commande not found");
        }
        if ($commande->etat > Commande::ETAT_VALIDE){
            throw new ServiceCommandeInvalidException("commande invalid");
        }
        try {
            $commande->etat = Commande::ETAT_VALIDE;
            $commande->saveOrFail();
        } catch(\Throwable $throwable) {
            throw new ServiceCommandeEnregistrementException("Une erreur est survenue lors de la sauvegarde: $throwable");
        }
        return $commande->toDTO();
    }

    private function validerDonneesCommande(CommandeDTO $commandeDTO): void {
        try {
            v::attribute('mail_client', v::email())
                ->attribute('type_livraison', v::in([Commande::LIVRAISON_PLACE, Commande::LIVRAISON_EMPORTER, Commande::ETAT_CREE]))
                    ->attribute('items', v::arrayVal()->notEmpty()
                        ->each(v::attribute('numero', v::intVal()->positive())
                            ->attribute('taille', v::in([1,2]))
                            ->attribute('quantite', v::intVal()->positive())
                        )
                    )
                    ->assert($commandeDTO);
        } catch(NestedValidationException $e) {
            throw new ServiceUnvalidDataException('données de commande invalides');
        } catch(NotFoundException $e) {
            throw new ServiceUnvalidDataException('dgtyhu-(,');
        }
    }

    /**
     * @param commandeDTO $commandeDTO
     * @return commandeDTO
     * @throws CreerCommandeException
     * @throws ProduitIntrouvableException
     */
    public function creerCommande(CommandeDTO $commandeDTO) {
        $montant_total = 0;
        $commandeId = Uuid::uuid4()->toString();
        foreach ($commandeDTO->items as $item) {
            $infoProduit = $this->serviceCatalogue->getProduit($item->numero, $item->taille);
            $montant_total += $infoProduit->tarif * $item->quantite;
            $item->libelle = $infoProduit->libelle_produit;
            $item->tarif = $infoProduit->tarif;
            $item->libelleTaille = $infoProduit->libelle_taille;
            $itemData = array('numero' => $item->numero, 'libelle' => $item->libelle,
                'taille' => $item->taille, 'libelle_taille' => $item->libelleTaille,
                'tarif' => $item->tarif, 'quantite' => $item->quantite, 'commande_id' => $commandeId);
            Item::create($itemData);
        }

        try {
            $newCommande = new Commande();
            $newCommande->id = $commandeId;
            $newCommande->date_commande = \date("Y-m-d h:i:s");
            $newCommande->etat = Commande::ETAT_CREE;
            $newCommande->montant_total = $montant_total;
            $newCommande->mail_client = $commandeDTO->email_client;
            $newCommande->type_livraison = $commandeDTO->type_livraison;
            $newCommande->saveOrFail();
        } catch (\Throwable | Exception $e) {
            throw new CreerCommandeException($e->getMessage());
        }
        $commandeDTO->etat = $newCommande->etat;
        $commandeDTO->id = $newCommande->id;
        $commandeDTO->delai = 0;
        $commandeDTO->montant = $montant_total;
        $commandeDTO->date_commande = $newCommande->date_commande;

        return $commandeDTO;
    }

    /**
     * @throws ServiceCommandeNotFoundException
     * @throws ServiceCommandeInvalidException
     */
    public function checkIfUserIsOwner($commandeId, $email): true
    {
        try {
            $commande = Commande::where('id', $commandeId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ServiceCommandeNotFoundException("commande not found");
        }
        if ($commande->mail_client !== $email){
            throw new ServiceCommandeInvalidException("not owner");
        }
        return true;
    }
}