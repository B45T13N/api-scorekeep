# API pour l'application Scorekeep Web et Mobile

[English Version](/README_EN.md)

[![Laravel](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml/badge.svg)](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml)

Cette API vous permet d'interagir avec différentes ressources en utilisant les méthodes HTTP telles que GET, POST, PUT et DELETE. Elle est construite sur le framework Laravel 10, offrant un moyen robuste et efficace de gérer les données de votre application.

## URL de Base

L'URL de

base pour toutes les requêtes API est : `https://api.scoreekeep.org/api`

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
