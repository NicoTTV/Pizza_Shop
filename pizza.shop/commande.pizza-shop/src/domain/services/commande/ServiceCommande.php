<?php
namespace pizzashop\commande\domain\services\commande;

use DI\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Logging\Exception;
use pizzashop\commande\domain\dto\commandeDTO;
use pizzashop\commande\domain\entities\Commande;
use pizzashop\commande\domain\entities\Item;
use pizzashop\commande\domain\services\exceptions\CreerCommandeException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeEnregistrementException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use pizzashop\commande\domain\services\exceptions\ServiceUnvalidDataException;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ServiceCommande implements ICommander{

    private iInfoProduit $serviceCatalogue;

    public function __construct(iInfoProduit $serviceCatalogue) {
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
     */
    public function creerCommande(CommandeDTO $commandeDTO): commandeDTO
    {
        $montant_total = 0;
        $commandeId = Uuid::uuid4()->toString();
        foreach ($commandeDTO->items as $item) {
            $infoProduit = $this->serviceCatalogue->getProduit($item->numero);
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
    public function checkIfUserIsOwner($commandeId, $email): bool
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