<?php

namespace pizzashop\commande\domain\services\commande;
use pizzashop\commande\domain\dto\commandeDTO;

interface iCommander
{

    public function creerCommande(CommandeDTO $commandeDTO): commandeDTO;
}