<?php

namespace pizzashop\src\domain\services\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;

class ServiceCatalogue implements iInfoProduit
{

    /** Pour plus tard */
    /**
     * public function getAllProducts(): array
     * {
     * $produits = Produit::all();
     * $produitsDTO = [];
     * foreach ($produits as $produit) {
     * $taille = $produit->tailles()->first();
     * $produitsDTO[] = new ProduitDTO($produit->numero, $produit->libelle, $produit->categorie_id,$taille->libelle,$taille->tarif->tarif);
     * }
     * return $produitsDTO;
     * }
     *
     * public function getProduitsParCategorie(): array
     * {
     *
     * }
     */

     /**
     * @throws ProduitIntrouvableException
     */
    public function getProduit(int $num, int $taille): ProduitDTO
    {
        try {
            $produit = Produit::where('numero', $num)->first();
            $taille = $produit->tailles()->where('id', $taille)->first();
            return new ProduitDTO($produit->numero, $produit->libelle, $produit->categorie_id, $taille->libelle, $taille->pivot->tarif);
        }catch (\Exception $e) {
            throw new ProduitIntrouvableException();
        }
    }
}