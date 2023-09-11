# API for Scorekeep Web and Mobile Applications (English)

This API enables interaction with various resources using HTTP methods such as GET, POST, PUT, and DELETE. It is built on top of the Laravel 10 framework, providing a robust and efficient means of managing your application's data.

## Base URL

The base URL for all API requests is: `https://api.scoreekeep.org/api`

## Authentication

This API employs token-based authentication to secure endpoints. To authenticate a request, include the `Authorization` header with the value `Bearer {your_token}`. You can obtain a token by making a `POST` request to the `/auth/login` endpoint.

Additionally, an API key is required to enable user registration in the Scorekeep tables.

## Endpoints

### Connect User

- **URL:** `/user/login`
- **Method:** POST with email and password
- **Response:**
  ```json
  {
    "data": {
      "access_token": "...",
      "token_type" : "Bearer"
    }
  }
  ```

### Logout User

- **URL:** `/user/logout`
- **Method:** POST
- **Response:**
  ```json
  {
    "data": {
      "status": true,
      "message" : "logged out"
    }
  }
  ```

### Get User Details

- **URL:** `/user/me`
- **Method:** GET
- **Response:**
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

### Get Game Details

- **URL:** `/games/{gameId}`
- **Method:** GET
- **Response:**
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

### Update Game Information

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

### Create a New Game

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

### Get Visitor Team Details

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

### Update Visitor Team Information

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
    "message": "Équipe visiteur mise à jour avec succès"
  }
  ```

### Get Local Teams

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

### Get Local Team Details

- **URL:** `/local-teams/{localTeamId}`
- **Method:** GET
- **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Local Team 1",
      "logo" : "Logo 1",
      "token" : 111,
    }
  }
  ```

### Create a New Room Manager

- **URL:** `/room-managers/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "token" : 111,
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Responsable de salle enregistré avec succès",
  }
  ```

### Create a New Secretary

- **URL:** `/secretaries/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "token" : 111,
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Secrétaire enregistré avec succès",
  }
  ```

### Create a New Timekeeper

- **URL:** `/timekeepers/store`
- **Method:** POST
- **Request Body:**
  ```json
  {
    "name" : "string",
    "token" : 111,
    "gameId" : "int"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Chronométreur enregistré avec succès",
  }
  ```

## Error Responses

If an error occurs while processing a request, the API will respond with an appropriate error message and HTTP status code. Common error status codes include:

- **400 Bad Request:** The request was invalid or missing required parameters.
- **401 Unauthorized:** Authentication failed or the user does not have permission.
- **404 Not Found:** The requested resource was not found.
- **500 Internal Server Error:** An unexpected error occurred on the server.

## Conclusion

This concludes the documentation for the Scorekeep API. You can now begin building and integrating your applications using the provided endpoints. If you have any questions or need further assistance, feel free to contact our support team at contact@scorekeep.org. Happy coding!
