<?php

namespace pizzashop\shop\domain\dto\commande;

use PhpParser\Node\Expr\Cast\Double;
use pizzashop\shop\domain\services\exceptions\PropertyDoesNotExist;

class commandeDTO extends \pizzashop\shop\domain\dto\DTO{

    private String $id;
    private String $date_commande;
    private Int $type_livraison;
    private float $montant;
    private Int $delai;
    private String $email_client;
    private array $items;

    public function __construct(String $id, String $date_commande, Int $type_livraison, float $montant, Int $delai, String $email_client, array $items)
    {

        $this->id = $id;
        $this->date_commande = $date_commande;
        $this->type_livraison = $type_livraison;
        $this->montant = $montant;
        $this->delai = $delai;
        $this->email_client = $email_client;
        $this->items = $items;
    }

    /**
     * @throws PropertyDoesNotExist
     */
    public function __get($att){
        if (property_exists($this, $att))
            return $this->$att;
        else throw new PropertyDoesNotExist("Property $att does not exist");
    }

    /**
     * @throws PropertyDoesNotExist
     */
    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name))
            $this->$name = $value;
        else throw new PropertyDoesNotExist("Property $name does not exist");
    }


}