<?php

namespace pizzashop\src\domain\services\commande;

use pizzashop\commande\domain\dto\commande\commandeDTO;

interface iCommander
{

    public function creerCommande(CommandeDTO $commandeDTO): void;
}