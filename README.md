# Pizza_Shop
## Membres du Groupe

### Liste des membres du groupe

- **BONIN Lucas**
- **HERGOTT Emilien**
- **KUENEMANN Nicolas**

### Lien vers le dépôt Git

- **[Dépôt Git](https://github.com/NicoTTV/Pizza_Shop)**

## Installation

Suivez ces étapes pour installer et démarrer les composants de Pizza Shop :

### Lancer les Services avec Docker
Ouvrez votre terminal et naviguez vers le dossier `pizza.shop/pizza.shop.components`. Exécutez ensuite les commandes suivante pour démarrer les services en utilisant Docker Compose :

```bash
cd pizza.shop/pizza.shop.components
docker-compose up -d
```

Après le lancement des services, les fichiers SQL seront automatiquement ajoutés aux bases de données respectives, initialisant ainsi les structures et les données nécessaires pour l'application.
## Liens vers le rendu du TP 7

[Dossier du fichier markdown](./rendu/TD7.md)

## Liens vers les API en PHP

- **API Commande**
    - Port : http://localhost:2000
      - Créer une commande en POST : http://localhost:2000/commandes
      - Récupérer une commande en GET : http://localhost:2000/commandes/{id}
      - Valider une commande en PATCH : http://localhost:2000/commandes/{id}

- **API Catalogue**
    - Port : http://localhost:3000
      - Récupérer les produits en GET : http://localhost:3000/produits
      - Récupérer un produit en GET : http://localhost:3000/produits/{id}
      - Récupérer les produits d'une catégorie en GET : http://localhost:3000/categorie/{id_categorie}/produits
- **API Authentification**
    - Port : http://localhost:4000
      - Connexion (signin) en POST : http://localhost:4000/user/signin
      - Inscription (signup) en POST : http://localhost:4000/user/validate
      - Rafraîchir le token en GET : http://localhost:4000/user/refresh

- **API GateAway**
    - Port : http://localhost:6000
        - Créer une commande en POST : http://localhost:6000/commandes
        - Récupérer une commande en GET : http://localhost:6000/commande/{id_commande}
        - Valider une commande en PATCH : http://localhost:6000/commande/{id_commande}
        - Récupérer tous les produits en GET : http://localhost:6000/produits
        - Récupérer un produit spécifique en GET : http://localhost:6000/produit/{id_produit}
        - Récupérer les produits par catégorie en GET : http://localhost:6000/categorie/{id_categorie}/produits
        - Inscription (signup) en POST : http://localhost:6000/signup
        - Connexion (signin) en POST : http://localhost:6000/signin
        - Rafraîchir le token en GET : http://localhost:6000/refresh


