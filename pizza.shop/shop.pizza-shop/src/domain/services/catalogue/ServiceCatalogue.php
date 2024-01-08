<?php

namespace pizzashop\shop\domain\services\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;
use pizzashop\shop\domain\entities\catalogue\Taille;
use pizzashop\shop\domain\services\exceptions\ProduitIntrouvableException;

class ServiceCatalogue implements iInfoProduit
{

     public function getAllProducts(): array {
         $produits = Produit::all();
         $produitsDTO = [];
         foreach ($produits as $produit) {
             $produitsDTO[] = new ProduitDTO($produit->numero, $produit->libelle, $produit->categorie_id);
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
            return new ProduitDTO($produit->numero, $produit->libelle, $produit->categorie_id, $taille->libelle, $taille->pivot->tarif);
        }catch (\Exception $e) {
            throw new ProduitIntrouvableException($e);
        }
    }
}