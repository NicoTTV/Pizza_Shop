<?php

namespace pizzashop\catalogue\domain\services\catalogue;
interface iBrowseCatalogue
{
    public function getAllProducts(): array;
    public function getProduitsParCategorie(): array;
}