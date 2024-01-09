<?php

namespace pizzashop\catalogue\domain\services\catalogue;

use pizzashop\catalogue\domain\dto\ProduitDTO;

interface iInfoProduit
{
    public function getProduit(int $num): ProduitDTO;
}