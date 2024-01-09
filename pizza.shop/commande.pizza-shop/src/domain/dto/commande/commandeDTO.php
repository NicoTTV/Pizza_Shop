<?php

namespace pizzashop\commande\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;
use pizzashop\commande\domain\services\exceptions\PropertyDoesNotExist;

class commandeDTO extends \pizzashop\commande\domain\dto\DTO{

    private $id;
    private $date_commande;
    private $type_livraison;
    private $montant;
    private $delai;
    private $email_client;
    private array $items = array();
    private $etat;

    public function __construct(String $email_client, Int $type_livraison) {

        $this->type_livraison = $type_livraison;
        $this->email_client = $email_client;
    }

    public function __get($att){
        if (property_exists($this, $att))
            return $this->$att;
        else throw new PropertyDoesNotExist("Property $att does not exist");
    }

    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name))
            $this->$name = $value;
        else throw new PropertyDoesNotExist("Property $name does not exist");
    }

    function addItem($item){
        $this->items[] = $item;
    }

}