# API for Scorekeep Web and Mobile Applications

[![Laravel](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml/badge.svg)](https://github.com/B45T13N/api-scorekeep/actions/workflows/laravel.yml)

This API enables interaction with various resources using HTTP methods such as GET, POST, PUT, and DELETE. It is built on top of the Laravel 10 framework, providing a robust and efficient means of managing your application's data.

## Prerequisites

Before starting to work on a Laravel 10 project with PHP 8.1, make sure you have the following components installed on your system:

1. **PHP 8.1**: Ensure that PHP 8.1 is installed on your system. You can verify this by running the following command in your terminal:

   ```bash
   php -v
   ```

   If PHP 8.1 is not installed, you can download it from the [official PHP website](https://www.php.net/downloads.php) or use a package manager like [Composer](https://getcomposer.org/) to install it.

2. **Composer**: Composer is an essential dependency manager for Laravel projects. Make sure you have it installed by following the instructions at [getcomposer.org](https://getcomposer.org/download/).

3. **Git**: Git is used for version control of your code. If you haven't already, install Git by following the instructions at [git-scm.com](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git).

## Cloning the Project with Git

Once you've confirmed that all the prerequisites are installed, you can retrieve the project by following these steps:

1. Open your terminal and navigate to the directory where you want to clone the repository.

2. Use Composer to create a new Laravel project by running the following command:

   ```bash
   git clone https://github.com/B45T13N/api-scorekeep.git project-name
   ```

   Replace `project-name` with the desired name for your project.

3. After the project creation is complete, navigate to the project directory using the `cd` command:

   ```bash
   cd project-name
   ```

4. Install project dependencies with Composer:

   ```bash
   php composer install
   ```

5. Run database migrations:

   ```bash
   php artisan migrate
   ```

6. If you need test data, you can modify the factory files and use the following command:

   ```bash
   php artisan db:seed
   ```
   
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
      "token_type" : "Bearer",
      "localTeamId": "integer"
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

### Get the List of Matches for the Current Week

- **URL:** `/weekGames`
- **Method:** GET
- **Request Parameters:**
    - `local_team_id` (required, int): The identifier of the local team for which you want to retrieve the matches.
    - `per_page` (int, optional): The number of items per page (default: 10).

- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "local_team_id": 2,
        "visitor_team_id": 1,
        "game_date": "2023-09-15",
        // Other match details
      },
      {
        "id": 2,
        "local_team_id": 2,
        "visitor_team_id": 3,
        "game_date": "2023-09-18",
        // Other match details
      },
      // Other matches of the week
    ],
    "links": {
      "first": "url_of_the_first_page",
      "last": "url_of_the_last_page",
      "prev": "url_of_the_previous_page",
      "next": "url_of_the_next_page"
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": 3,
      "path": "url_of_the_current_request",
      "per_page": 10,
      "to": 10,
      "total": 28
    }
  }
  ```

### Get Matches Based on Date Range

- **URL:** `/games`
- **Method:** GET
- **Request Body:**
  ```json
  {
    "local_team_id": "integer (required)",
    "start_date": "date (required, must be after or equal to today)",
    "end_date": "date (required, must be after or equal to today)"
  }
  ```
- **Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "localTeamId": 2,
        "visitorTeamId": 1,
        "gameDate": "yyyy-mm-dd",
        // Other match details
      },
      {
        "id": 2,
        "localTeamId": 2,
        "visitorTeamId": 3,
        "gameDate": "yyyy-mm-dd",
        // Other match details
      },
      // Other matches within the specified date range
    ],
    "links": {
      "first": "url_of_the_first_page",
      "last": "url_of_the_last_page",
      "prev": "url_of_the_previous_page",
      "next": "url_of_the_next_page"
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": n,
      "path": "url_of_the_current_request",
      "per_page": 10,
      "to": 10,
      "total": total_number_of_matches
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
    "address" :  string || null,
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
    "localTeamId" : "integer",
    "address" : "string",
    "category" : "string",
    "visitorTeamName" : "string",
    "isHomeMatch" : "boolean",
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

### Delete Match

- **URL:** `/api/games/delete`
- **Method:** POST
- **Request:**
  ```json
  {
    "gameId": 1
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "message": "Match supprimé avec succès"
  }
  ```

- **Response (404 Not Found):**
  ```json
  {
    "message": "Match non trouvé",
  }
  ```

### Confirm Match

- **URL:** `/api/games/confirm`
- **Method:** POST
- **Request:**
  ```json
  {
    "gameId": 1
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "message": "Match supprimé avec succès"
  }
  ```

- **Response (404 Not Found):**
  ```json
  {
    "message": "Match non trouvé",
  }
  ```

### Cancel Match

- **URL:** `/api/games/cancel`
- **Method:** POST
- **Request:**
  ```json
  {
    "gameId": 1
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "message": "Match supprimé avec succès"
  }
  ```

- **Response (404 Not Found):**
  ```json
  {
    "message": "Match non trouvé",
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
