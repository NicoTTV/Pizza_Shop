<?php

namespace pizzashop\shop\domain\services\catalogue;

use pizzashop\shop\domain\dto\catalogue\CategorieDTO;
use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\dto\catalogue\TailleDTO;
use pizzashop\shop\domain\dto\catalogue\TarifDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;
use pizzashop\shop\domain\entities\catalogue\Taille;
use pizzashop\shop\domain\services\exceptions\ProduitIntrouvableException;

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
        $produitsDTO = [];
        foreach ($produits as $produit) {
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
            $produitsDTO[] = $produitDTO;
        }

        return $produitsDTO;
    }


    /**
     * @throws ProduitIntrouvableException
     */
    public function getProduit(int $num, int $taille): ProduitDTO
    {
        try {
            $produit = Produit::where('numero', $num)->first();
            $taille = $produit->tailles()->where('id',$taille)->first();
            $categ = $produit->categorie()->first();
            $categDTO = new CategorieDTO($categ->id, $categ->libelle);
            return new ProduitDTO($produit->numero, $produit->libelle, $categDTO);
        }catch (\Exception $e) {
            throw new ProduitIntrouvableException($e);
        }
    }

    function getCategorie(int $id): string
    {
        $categorie = Categorie::where('id', $id)->first();
        return $categorie->libelle;
    }

    function getProduct(int $id): ProduitDTO
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