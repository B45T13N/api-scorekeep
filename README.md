# API pour l'application Scorekeep Web et Mobile

[English Version](/README_EN.md)

[![Laravel](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml/badge.svg)](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml)

Cette API vous permet d'interagir avec différentes ressources en utilisant les méthodes HTTP telles que GET, POST, PUT et DELETE. Elle est construite sur le framework Laravel 10.

## Prérequis

Avant de commencer à travailler sur un projet Laravel 10 avec PHP 8.1, assurez-vous d'avoir installé les éléments suivants sur votre système :

1. **PHP 8.1** : Assurez-vous d'avoir PHP 8.1 installé sur votre système. Vous pouvez le vérifier en exécutant la commande suivante dans votre terminal :
   
   ```bash
   php -v
   ```

   Si PHP 8.1 n'est pas installé, vous pouvez le télécharger depuis le [site officiel PHP](https://www.php.net/downloads.php) ou utiliser un gestionnaire de paquets tel que [Composer](https://getcomposer.org/) pour l'installer.

2. **Composer** : Composer est un gestionnaire de dépendances essentiel pour les projets Laravel. Assurez-vous de l'avoir installé en suivant les instructions sur [getcomposer.org](https://getcomposer.org/download/).

3. **Git** : Git est utilisé pour gérer les versions de votre code. Si ce n'est pas déjà fait, installez Git en suivant les instructions sur [git-scm.com](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git).

## Récupération du projet avec Git

Une fois que vous avez vérifié que tous les prérequis sont installés, vous pouvez récupérer le projet en suivant ces étapes :

1. Ouvrez votre terminal et accédez au répertoire où vous souhaitez cloner le dépôt.

2. Utilisez Composer pour créer un nouveau projet Laravel en exécutant la commande suivante :

   ```bash
   git clone https://github.com/B45T13N/api-scorekeep.git nom-du-projet
   ```

   Remplacez `nom-du-projet` par le nom que vous souhaitez donner à votre projet.

3. Une fois la création du projet terminée, accédez au répertoire du projet en utilisant la commande `cd` :

   ```bash
   cd nom-du-projet
   ```

4. Récupérer les dépendances avec Composer :

   ```bash
   php composer install
   ```

5. Lancer les migrations de la base de données :

   ```bash
   php artisan migrate
   ```
6. Pour des données de tests vous pouvez modifier les fichiers de factory et utiliser la commande suivante :

   ```bash
   php artisan db:seed
   ```

## URL de Base

L'URL de base pour toutes les requêtes API est : `http://localhost/api`

## Authentification

Cette API utilise l'authentification basée sur des jetons (tokens) pour sécuriser les points d'accès. Pour authentifier une requête, incluez l'en-tête `Authorization` avec la valeur `Bearer {votre_token}`. Vous pouvez obtenir un jeton en effectuant une requête `POST` vers le point d'accès `/auth/login`.

De plus, une clé d'API est nécessaire pour permettre l'inscription des utilisateurs dans les tables Scorekeep.

## Points d'Accès

### Connecter l'Utilisateur

- **URL:** `/user/login`
- **Méthode:** POST avec email et mot de passe
- **Réponse :**
  ```json
  {
    "data": {
      "access_token": "...",
      "token_type" : "Bearer"
    }
  }
  ```

### Déconnecter l'Utilisateur

- **URL:** `/user/logout`
- **Méthode:** POST
- **Réponse :**
  ```json
  {
    "data": {
      "status": true,
      "message" : "logged out"
    }
  }
  ```

### Récupérer les Détails de l'Utilisateur

- **URL:** `/user/me`
- **Méthode:** GET
- **Réponse :**
  ```json
  {
    "data": {
      "status": true,
      "user": {        
        "id": 1,
        "nom": "John Doe",
        "email": "johndoe@example.com",
        "created_at": "2023-08-17T12:00:00Z",
        "updated_at": "2023-08-17T12:00:00Z"  
      }
    }
  }
  ```
  
### Obtenir la Liste des Matchs pour la semaine en cours

- **URL:** `/weekGames`
- **Méthode:** GET
- **Paramètres de la Requête :**
  - `local_team_id` (obligatoire, int) : L'identifiant de l'équipe locale pour laquelle vous souhaitez obtenir les matchs.
  - `per_page` (int, facultatif) : Le nombre d'éléments par page (par défaut : 10).

- **Réponse :**
  ```json
  {
    "data": [
      {
        "id": 1,
        "local_team_id": 2,
        "visitor_team_id": 1,
        "game_date": "2023-09-15",
        // Autres détails du match
      },
      {
        "id": 2,
        "local_team_id": 2,
        "visitor_team_id": 3,
        "game_date": "2023-09-18",
        // Autres détails du match
      },
      // Autres matchs de la semaine
    ],
    "links": {
      "first": "url_de_la_première_page",
      "last": "url_de_la_dernière_page",
      "prev": "url_de_la_page_précédente",
      "next": "url_de_la_page_suivante"
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": 3,
      "path": "url_de_la_page_actuelle",
      "per_page": 10,
      "to": 10,
      "total": 28
    }
  }
  ```
### Obtenir des Matchs en Fonction de la Plage de Dates

- **URL :** `/games`
- **Méthode :** GET
- **Corps de la Requête :**
  ```json
  {
    "local_team_id": "entier (obligatoire)",
    "start_date": "date (obligatoire, doit être postérieure ou égale à aujourd'hui)",
    "end_date": "date (obligatoire, doit être postérieure ou égale à aujourd'hui)"
  }
  ```
- **Réponse :**
  ```json
  {
    "data": [
      {
        "id": 1,
        "localTeamId": 2,
        "visitorTeamId": 1,
        "gameDate": "aaaa-mm-jj",
        // Autres détails du match
      },
      {
        "id": 2,
        "localTeamId": 2,
        "visitorTeamId": 3,
        "gameDate": "aaaa-mm-jj",
        // Autres détails du match
      },
      // Autres matchs dans la plage de dates spécifiée
    ],
    "links": {
      "first": "URL_de_la_première_page",
      "last": "URL_de_la_dernière_page",
      "prev": "URL_de_la_page_précédente",
      "next": "URL_de_la_page_suivante"
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": n,
      "path": "chemin_de_la_requête_actuelle",
      "per_page": 10,
      "to": 10,
      "total": nombre_total_de_matchs
    }
  }
  ```
    
### Récupérer les Détails du Match

- **URL:** `/games/{gameId}`
- **Méthode:** GET
- **Réponse :**
  ```json
  {
    "data": {
      "id": 1,
      "address" : "Address ",
      "category" : "Category",
      "gameDate" : "Datetime",
      "timekeeper" : null || Timekeeper,
      "secretary" : null || Secretary,
      "roomManager" : null || RoomManager,
      "visitorTeam" : VisitorTeam,
    }
  }
  ```

### Mettre à Jour les Informations du Match

- **URL:** `/games/{gameId}`
- **Méthode:** PUT
- **Corps de la Requête :**
  ```json
  {
    "timekeeperId" : integer || null,
    "secretaryId" : integer || null,
    "roomManagerId" :  integer || null,
    "gameDate" : "datetime" > date.now(),
  }
  ```
- **Réponse :**
  ```json
  {
    "message" : "Match mis à jour avec succès",
  }
  ```

### Créer un Nouveau Match

- **URL:** `/games`
- **Méthode:** POST
- **Corps de la Requête :**
  ```json
  {
    "address" : "string",
    "category" : "string",
    "visitorTeamName" : "string",
    "gameDate" : "datetime" > date.now()
  }
  ```
- **Réponse :**
  ```json
  {
    "message": "Match enregistré avec succès",
  }
  ```

### Récupérer les Détails de l'Équipe Visiteuse

- **URL:** `/visitor-teams/{visitorTeamId}`
- **Méthode:** GET
- **Réponse :**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Équipe Visiteuse"
    }
  }
  ```

### Mettre à Jour les Informations de l'Équipe Visiteuse

- **URL:** `/visitor-teams/{visitorTeamId}`
- **Méthode:** PUT
- **Corps de la Requête :**
  ```json
  {
    "nom": "Nom de l'Équipe Visiteuse Mis à Jour"
  }
  ```
- **Réponse :**
  ```json
  {
    "message": "Équipe visiteur mise à jour avec succès"
  }
  ```

### Récupérer les Équipes Locales

- **URL:** `/local-teams`
- **Méthode:** GET
- **Réponse :**
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "Local Team 1",
        "logo" : "Logo 1",
        "token" : 111,
      },
      {
        "id": 2,
        "name": "Local Team 2",
        "logo" : "Logo 2",
        "token" : 111,
      }
    ]
  }
  ```

### Récupérer les Détails de l'Équipe Locale

- **URL:** `/local-teams/{localTeamId}`
- **Méthode:** GET
- **Réponse :**
  ```json
  {
    "data": {
      "id": 1,
      "nom": "Équipe Locale 1",
      "logo" : "Logo 1",
      "token" : 111,
    }
  }
  ```

### Créer un Nouveau Responsable de Salle

- **URL:** `/room-managers/store`
- **Méthode:** POST
- **Corps de la Requête :**
  ```json
  {
    "nom" : "chaîne",
    "token" : 111,
    "idMatch" : "int"
  }
  ```
- **Réponse :**
  ```json
  {
    "message": "Responsable de salle enregistré avec succès",
  }
  ```

### Créer un Nouveau Secrétaire

- **URL:** `/secretaries/store`
- **Méthode:** POST
- **Corps de la Requête :**
  ```json
  {
    "nom" : "chaîne",
    "token" : 111,
    "idMatch" : "int"
  }
  ```
- **Réponse :**
  ```json
  {
    "message": "Secrétaire enregistré avec succès",
  }
  ```

### Créer un Nouveau Chronométreur

- **URL:** `/timekeepers/store`
- **Méthode:** POST
- **Corps de la Requête :**
  ```json
  {
    "nom" : "chaîne",
    "token" : 111,
    "idMatch" : "int"
  }
  ```
- **Réponse :**
  ```json
  {
    "message": "Chronométreur enregistré avec succès

",
}
  ```

## Réponses d'Erreur

Si une erreur se produit lors du traitement d'une requête, l'API répondra avec un message d'erreur approprié et un code d'état HTTP correspondant. Les codes d'erreur courants comprennent :

- **400 Requête Incorrecte :** La requête était invalide ou des paramètres requis sont manquants.
- **401 Non Autorisé :** L'authentification a échoué ou l'utilisateur n'a pas les permissions nécessaires.
- **404 Non Trouvé :** La ressource demandée n'a pas été trouvée.
- **500 Erreur Interne du Serveur :** Une erreur inattendue s'est produite sur le serveur.

## Conclusion

Ceci conclut la documentation pour l'API Scorekeep. Vous pouvez maintenant commencer à construire et à intégrer vos applications en utilisant les points d'accès fournis. Si vous avez des questions ou avez besoin d'assistance supplémentaire, n'hésitez pas à contacter notre équipe de support à l'adresse contact@scorekeep.org. Bon développement !
