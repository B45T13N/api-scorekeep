# API des applications web et mobile Scorekeep / API for Scorekee web/mobile app

This API allows you to interact with various resources using the HTTP methods GET, POST, PUT, and DELETE. 
It"s built on top of the Laravel 10 framework, providing a robust and efficient way to manage your application"s data.

Cette API vous permet d"interagir avec différentes ressources en utilisant les méthodes HTTP GET, POST, PUT et DELETE. 
Elle est construite sur le framework Laravel 10, offrant ainsi un moyen robuste et efficace de gérer les données de votre application.

## Base URL / URL de base

The base URL for all API requests is: `https://api.scoreekeep.org/api`
L"URL de base pour toutes les requêtes API est : `https://api.scoreekeep.org/api`

## Authentication

This API uses token-based authentication to secure endpoints. 
To authenticate a request, include the `Authorization` header with the value `Bearer {your_token}`. 
You can obtain a token by making a `POST` request to the `/auth/login` endpoint.

There is also an API key required to allow user registration to the scoreekeep tables.

Cette API utilise l"authentification basée sur des jetons (tokens) pour sécuriser les points d"accès. 
Pour authentifier une requête, incluez l"en-tête `Authorization` avec la valeur `Bearer {votre_token}`. 
Vous pouvez obtenir un jeton en effectuant une requête `POST` vers le point d"accès `/user/login`.

Il y a également une clé d"API nécessaire pour permettre l"enregistrement des utilisateurs aux tables de marques.

## Endpoints 

### Connect user / Connecter l"utilisateur

- **URL:** `/user/login`
- **Method:** POST with email and password / avec email et mot de passe
- **Response:**
  ```json
  {
    "data": {
      "sucess": true,
      "token" : ...
    }
  }
  ```

  

### Logout user / Déconnecter l"utilisateur

- **URL:** `/user/logout`
- **Method:** POST
- **Response:**
  ```json
  {
    "data": {
      "sucess": true,
    }
  }
  ```

### Get user details / Récupérer les détails de l"utilisateur

- **URL:** `/user/me`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "John Doe",
      "email": "johndoe@example.com",
      "created_at": "2023-08-17T12:00:00Z",
      "updated_at": "2023-08-17T12:00:00Z"
    }
  }
  ```
### Get game details / Récupérer les détails du match

- **URL:** `/games/{gameId}`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "address" : "Adresse ",
      "category" : "Category",
      "gameDate" : "Datetime",
      "timekeeper" : null || Timekeeper,
      "secretary" : null || Secretary,
      "roomManager" : null || RoomManager,
      "visitorTeam" : VisitorTeam,
    }
  }
  ```

### Update game information / Mettre à jour les informations du jeu

- **URL:** `/games/{gameId}`
- **Method:** PUT
- **Request Body:**
  ```json
  {
    "timekeeperId" : integer || null,
    "secretaryId" : integer || null,
    "roomManagerId" :  integer || null,
    "gameDate" : "datetime" > date.now(),
  }
  ```
- **Response:**
  ```json
  {
    "message" : "Match mis à jour avec succès",
  }
  ```

### Create a new game / Créer un nouveau jeu

- **URL:** `/games`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "address" : "string",
    "category" : "string",
    "visitorTeamName" : "string",
    "gameDate" : "datetime" > date.now()
  }
  ```
- **Response:**
  ```json
  {
    "message": "Match enregistré avec succès",
  }
  ```

### Get visitor team details / Récupérer les détails de l"équipe visiteuse

- **URL:** `/visitor-teams/{visitorTeamId}`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Visitor Team"
    }
  }
  ```

### Update visitor team information / Mettre à jour les informations de l"équipe visiteuse

- **URL:** `/visitor-teams/{visitorTeamId}`
- **Method:** PUT
- **Request Body:**
  ```json
  {
    "name": "Updated Visitor Team Name"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Equipe visiteur mise à jour avec succès"
  }
  ```

### Get local teams / Récupérer les équipes locales

- **URL:** `/local-teams`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "Local Team 1",
        "logo" : "Logo 1",
      },
      {
        "id": 2,
        "name": "Local Team 2",
        "logo" : "Logo 2",
      }
    ]
  }
  ```

### Get local team details / Récupérer les détails de l"équipe locale

- **URL:** `/local-teams/{localTeamId}`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Local Team 1",
      "logo" : "Logo 1",
    }
  }
  ```

### Create a new room manager / Créer un nouveau responsable de salle

- **URL:** `/room-managers/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "email" : "email",
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Responsable de salle enregistré avec succès",
  }
  ```

### Create a new secretary / Créer un nouveau secrétaire

- **URL:** `/secretaries/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "email" : "email",
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Secrétaire enregistré avec succès",
  }
  ```

### Create a new timekeeper / Créer un

 nouveau chronométreur

- **URL:** `/timekeepers/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "email" : "email",
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Chronométreur enregistré avec succès",
  }
  ```
  

## Error Responses / Réponses d"erreur

If an error occurs while processing a request, the API will respond with an appropriate error message and HTTP status code. Common error status codes include:

- **400 Bad Request:** The request was invalid or missing required parameters.
- **401 Unauthorized:** Authentication failed or user does not have permission.
- **404 Not Found:** The requested resource was not found.
- **500 Internal Server Error:** An unexpected error occurred on the server.

Si une erreur se produit lors du traitement d"une requête, l"API répondra avec un message d"erreur approprié et un code d"état HTTP correspondant. Les codes d"erreur courants comprennent :

- **400 Requête incorrecte :** La requête était invalide ou des paramètres requis sont manquants.
- **401 Non autorisé :** L"authentification a échoué ou l"utilisateur n"a pas les permissions nécessaires.
- **404 Non trouvé :** La ressource demandée n"a pas été trouvée.
- **500 Erreur interne du serveur :** Une erreur inattendue s"est produite sur le serveur.

## Conclusion

This concludes the documentation for the API Scorekeep. 
You can now start building and integrating your applications with the provided endpoints. 
If you have any questions or need further assistance, feel free to contact our support team at contact@scorekeep.org. Happy coding!

Ceci conclut la documentation de l"API Scorekeep. 
Vous pouvez désormais commencer à construire et à intégrer vos applications en utilisant les points d"accès fournis. 
Si vous avez des questions ou avez besoin d"assistance, n"hésitez pas à contacter notre équipe de support à l"adresse contact@scorekeep.org. Bon développement !
