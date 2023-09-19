<?php

namespace pizzashop\shop\domain\services\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;

interface iBrowseCatalogue
{
    public function getAllProducts(): array;
    public function getProduitsParCategorie(): array;
}