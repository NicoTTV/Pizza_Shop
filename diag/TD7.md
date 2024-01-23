## Exercice 1 : API passerelle

### Créer une Commande

- **Méthode** : POST
- **URI** : `/commandes[/]`
- **Code Retour** : 201
- **Code(s) erreur(s)** : 500, 401
- **Description** : Créer une nouvelle commande. Les données échangées incluent les informations de l'utilisateur et les détails de la commande comme les produits, quantités, et le prix total.

### Obtenir une Commande par ID

- **Méthode** : GET
- **URI** : `/commande/{id_commande}[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500, 401
- **Description** : Récupérer les détails d'une commande spécifique à l'aide de son identifiant. Aucune donnée échangée à part l'ID dans l'URI.

### Modifier une Commande

- **Méthode** : PATCH
- **URI** : `/commande/{id_commande}[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500, 401
- **Description** : Modifier les détails d'une commande existante. Les données échangées peuvent inclure les quantités de produits modifiées, les produits ajoutés ou supprimés, et les mises à jour de prix.

### Lister tous les Produits

- **Méthode** : GET
- **URI** : `/produits[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500
- **Description** : Obtenir une liste de tous les produits disponibles. Les données échangées sont généralement les informations des produits.

### Obtenir un Produit par ID

- **Méthode** : GET
- **URI** : `/produit/{id_produit}[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500
- **Description** : Récupérer les détails d'un produit spécifique à l'aide de son identifiant. Aucune donnée échangée à part l'ID dans l'URI.

### Lister les Produits d'une Catégorie

- **Méthode** : GET
- **URI** : `/categorie/{id_categorie}/produits[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500
- **Description** : Obtenir une liste de tous les produits d'une catégorie spécifique. Les données échangées sont les identifiants de catégorie et les informations des produits associés.

### Inscription Utilisateur

- **Méthode** : POST
- **URI** : `/signup[/]`
- **Code Retour** : 201
- **Code(s) erreur(s)** : 500, 401
- **Description** : Enregistrer un nouvel utilisateur. Les données échangées incluent des informations personnelles comme l'adresse email, et le mot de passe.

### Connexion Utilisateur

- **Méthode** : POST
- **URI** : `/signin[/]`
- **Code Retour** : 201
- **Code(s) erreur(s)** : 500, 401
- **Description** : Connecter un utilisateur. Les données échangées incluent l'adresse email et le mot de passe.


## Exercice 2 : compléments sur l’api passerelle

### Ajouter une Catégorie au Catalogue

- **Méthode** : POST
- **URI** : `/categorie[/]`
- **Code Retour** : 201
- **Code(s) erreur(s)** : 400, 401, 500
- **Description** : Ajouter une catégorie au catalogue. Données nécessaires : nom de la catégorie.

### Ajouter un Produit au Catalogue

- **Méthode** : POST
- **URI** : `/produit[/]`
- **Code Retour** : 201
- **Code(s) erreur(s)** : 400, 401, 500
- **Description** : Ajouter un produit au catalogue. Données nécessaires : détails du produit.

### Associer un Produit Existant à une Catégorie Existant

- **Méthode** : POST
- **URI** : `/categorie/{id_categorie}/produit/{id_produit}[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 400, 401, 404, 500
- **Description** : Associer un produit existant à une catégorie existante. Données nécessaires : identifiants du produit et de la catégorie.

### Modifier un Produit du Catalogue

- **Méthode** : PATCH
- **URI** : `/produit/{id_produit}[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 400, 401, 404, 500
- **Description** : Modifier un produit du catalogue. Données nécessaires : détails mis à jour du produit.

### Paginer les Listes de Produits

- **Méthode** : GET
- **URI** : `/produits[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500
- **Description** : Lister les produits avec pagination. Paramètres de requête pour la pagination (ex : page, limit).

### Filtrer les Listes de Produits avec un Mot Clé

- **Méthode** : GET
- **URI** : `/produits/search[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 500
- **Description** : Filtrer les listes de produits avec un mot clé. Paramètre de requête pour le mot clé (ex : q).

### Lister les Commandes d’un Utilisateur

- **Méthode** : GET
- **URI** : `/utilisateur/{id_utilisateur}/commandes[/]`
- **Code Retour** : 200
- **Code(s) erreur(s)** : 401, 404, 500
- **Description** : Lister les commandes d’un utilisateur. Données nécessaires : identifiant de l'utilisateur.
