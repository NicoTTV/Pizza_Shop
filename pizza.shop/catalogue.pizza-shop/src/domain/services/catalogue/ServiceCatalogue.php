<?php

namespace pizzashop\catalogue\domain\services\catalogue;
use pizzashop\catalogue\domain\dto\CategorieDTO;
use pizzashop\catalogue\domain\dto\ProduitDTO;
use pizzashop\catalogue\domain\dto\TailleDTO;
use pizzashop\catalogue\domain\dto\TarifDTO;
use pizzashop\catalogue\domain\entities\Produit;
use pizzashop\catalogue\domain\services\exceptions\CategorieIntrouvableException;
use pizzashop\catalogue\domain\services\exceptions\ProduitIntrouvableException;
use pizzashop\catalogue\domain\services\exceptions\TailleIntrouvableException;
use pizzashop\catalogue\domain\services\exceptions\TarifIntrouvableException;


class ServiceCatalogue implements iInfoProduit
{

    public function getAllProducts(): array {
        $produits = Produit::all();
        $produitsDTO = [];
        foreach ($produits as $produit) {
            $categ = $produit->categorie()->first();
            $categDTO = new CategorieDTO($categ->id, $categ->libelle);
            $produitsDTO[] = new ProduitDTO($produit->numero, $produit->libelle, $categDTO);
        }
        return $produitsDTO;
    }

    function produitByCategorie(int $id): array
    {
        $produits = Produit::where('categorie_id', $id)->get();

        if ($produits->isEmpty()) {
            throw new ProduitIntrouvableException("Aucun produit trouvé pour la catégorie ID: $id");
        }

        $produitsDTO = [];
        foreach ($produits as $produit) {
            $categ = $produit->categorie()->first();
            if (!$categ) {
                throw new CategorieIntrouvableException("Catégorie introuvable pour le produit ID: {$produit->id}");
            }
            $categDTO = new CategorieDTO($categ->id, $categ->libelle);
            $produitDTO = new ProduitDTO($produit->numero, $produit->libelle, $categDTO);

            $tarifs = $produit->tarif()->get();

            foreach ($tarifs as $tarif) {
                if (!$tarif) {
                    throw new TarifIntrouvableException("Tarif introuvable pour le produit ID: {$produit->id}");
                }
                $tarifDTO = new TarifDTO($tarif->produit_id, $tarif->taille_id, $tarif->tarif);

                $taille = $tarif->taille()->first();
                if (!$taille) {
                    throw new TailleIntrouvableException("Taille introuvable pour le tarif ID: {$tarif->id}");
                }
                $tarifDTO->taille = new TailleDTO($taille->id, $taille->libelle);
                $produitDTO->tarifs[] = $tarifDTO;
            }
            $produitsDTO[] = $produitDTO;
        }

        return $produitsDTO;
    }


    function getProduit(int $id): ProduitDTO
    {
        $produit = Produit::where('id', $id)->first();
        $categ = $produit->categorie()->first();
        $categDTO = new CategorieDTO($categ->id, $categ->libelle);
        $produitDTO = new ProduitDTO($produit->numero, $produit->libelle, $categDTO);

        $tarifs = $produit->tarif()->get();

        foreach ($tarifs as $tarif) {
            $tarifDTO = new TarifDTO($tarif->produit_id, $tarif->taille_id, $tarif->tarif);

            $taille = $tarif->taille()->first();
            $tarifDTO->taille = new TailleDTO($taille->id, $taille->libelle);
            $produitDTO->tarifs[] = $tarifDTO;

        }
        return $produitDTO;
    }
}