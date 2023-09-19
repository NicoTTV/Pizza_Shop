<?php

namespace pizzashop\src\domain\services\commande;

use pizzashop\shop\domain\dto\commande\commandeDTO;

interface iCommander
{
    public function creerCommande(CommandeDTO $commandeDTO): void;
}