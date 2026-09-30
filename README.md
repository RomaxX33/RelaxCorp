# RelaxCorp

Web platform for managing controlled employee breaks through interactive minigames, user management, time control, score tracking, rankings, and statistics.

RelaxCorp was developed as the final project of the **Higher Technician in Network Computer Systems Administration (ASIR)** at **IES Luis Vives** during the 2025–2026 academic year.

**Author:** Romualdo Pérez Gómez

---

## Overview

RelaxCorp is a PHP/MySQL web application designed to provide employees with controlled access to short interactive breaks during the working day.

The platform combines authentication, role-based access control, daily play-time management, browser-based minigames, score persistence, rankings, and user statistics.

The application was designed for deployment in a local network environment and is served through Apache on a Linux server.

The project focuses on integrating several infrastructure and development technologies into a single working system:

* Linux server administration
* Apache
* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* Phaser 3
* Session-based authentication
* Database-backed score management

---

## Features

### Authentication

* User login and logout
* Password verification using PHP's password hashing functions
* Session-based authentication
* Session ID regeneration after authentication
* Role-based redirection

Two roles are supported:

* `user`
* `admin`

Users are redirected to the game platform, while administrators are redirected to the administration panel.

### User Management

Administrators can:

* View registered users
* Create users
* Delete users
* Manage access to the platform

Administrators cannot delete their own account.

### Daily Play-Time Control

Regular users receive a daily allowance of **20 minutes (1200 seconds)** for accessing the game area.

The remaining time is stored in the database and updated according to elapsed time between requests.

When the available time reaches zero:

* the session is terminated;
* access to the game area is blocked;
* the user is redirected to the timeout login state.

The daily allowance is reset on the following day.

Administrators are redirected to the administration panel and therefore do not consume game time through the normal user game flow.

### Minigames

RelaxCorp currently includes two games:

* **Klondike Solitaire**
* **Clicker**

Both games are integrated into the main authentication and time-control system.

### Score Management

Game scores are sent from the browser to the PHP backend through an HTTP JSON endpoint.

Scores are stored persistently in MySQL and can later be used by:

* rankings;
* user statistics.

### Rankings

Users can view rankings for individual games.

The ranking system displays the best score achieved by each user for the selected game.

### User Statistics

The statistics section provides information about the user's game performance, including:

* best score per game;
* recent game results.

---

## Architecture

RelaxCorp follows a simple client-server architecture.

```text
┌───────────────────────┐
│       Web Browser     │
│                       │
│ HTML / CSS / JS       │
│ Phaser 3 games        │
└───────────┬───────────┘
            │ HTTP
            ▼
┌───────────────────────┐
│       Apache          │
│                       │
│ PHP application       │
│ Authentication        │
│ Sessions              │
│ Time control          │
│ Administration        │
│ Score endpoint        │
└───────────┬───────────┘
            │ MySQL
            ▼
┌───────────────────────┐
│        MySQL          │
│                       │
│ users                 │
│ games                 │
│ scores                │
└───────────────────────┘
```

The application is organized around several logical layers:

### Frontend

HTML, CSS, and JavaScript provide the web interface.

### Game Layer

Phaser 3 is used for the Clicker game and for the Solitaire implementation.

### Backend

PHP handles:

* authentication;
* sessions;
* authorization;
* time control;
* user management;
* score persistence;
* rankings;
* statistics.

### Database

MySQL stores users, available games, and recorded scores.

---

## Technologies

| Technology          | Purpose                   |
| ------------------- | ------------------------- |
| Ubuntu Server 24.04 | Server operating system   |
| Apache 2.4          | Web server                |
| PHP 8.3             | Backend application       |
| MySQL 8.0           | Database                  |
| HTML5               | Web structure             |
| CSS3                | User interface            |
| JavaScript          | Client-side functionality |
| Phaser 3            | Browser game framework    |
| MySQLi              | PHP database access       |
| Git                 | Version control           |

The project was developed and tested using the Linux server environment used for the final project.

---

## Project Structure

```text
RelaxCorp/
├── admin/
│   ├── create_user.php
│   ├── dashboard.php
│   └── delete_user.php
│
├── api/
│   └── save_score.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── img/
│   │   └── fondo.jpeg
│   └── js/
│
├── auth/
│   ├── login.php
│   └── logout.php
│
├── games/
│   ├── index.php
│   ├── game1.php
│   ├── game2.php
│   └── solitario/
│       ├── index.html
│       ├── assets/
│       └── assets/js/
│
├── includes/
│   ├── admin.php
│   ├── auth.php
│   ├── db.php
│   ├── session.php
│   └── time_control.php
│
├── user/
│   ├── dashboard.php
│   ├── ranking_game.php
│   └── stats.php
│
├── index.php
├── .env.example
├── .gitignore
├── relaxcorp_schema.sql
└── README.md
```

---

## Requirements

A deployment of RelaxCorp requires:

* Linux server or compatible operating system
* Apache 2.4 or compatible web server
* PHP 8.3 or compatible PHP version
* MySQL 8.0 or compatible MySQL version
* PHP MySQLi extension
* Browser with JavaScript enabled

The application expects to be installed under the `/relaxcorp/` path.

For example:

```text
/var/www/html/relaxcorp/
```

Because application routes use the `/relaxcorp/` prefix, changing the installation path requires updating those routes.

---

## Installation

### 1. Clone or copy the project

Place the project inside the Apache document root:

```bash
sudo cp -r RelaxCorp /var/www/html/relaxcorp
```

The resulting structure should be:

```text
/var/www/html/relaxcorp/
```

### 2. Set appropriate permissions

The Apache process must be able to read the application files.

For example:

```bash
sudo chown -R www-data:www-data /var/www/html/relaxcorp
```

Permissions should be adjusted according to the target server's security policy.

### 3. Configure PHP

Make sure PHP and the MySQL extension are installed.

Example:

```bash
sudo apt install apache2 php mysql-server php-mysql
```

Verify the installed versions:

```bash
apache2 -v
php -v
mysql --version
```

---

## Configuration

RelaxCorp obtains database configuration through environment variables.

The following variables are expected:

```text
DB_HOST=localhost
DB_NAME=relaxcorp
DB_USER=relaxuser
DB_PASSWORD=YOUR_LOCAL_PASSWORD
```

An example configuration is provided in:

```text
.env.example
```

### Important

The application uses PHP's `getenv()` function to read these values.

The `.env.example` file is therefore a configuration reference; copying it to `.env` does **not by itself** make PHP load the variables.

The variables must be provided through the server/PHP environment using the configuration mechanism selected for the deployment.

Database credentials must never be committed to the repository.

---

## Database Setup

The repository includes the database schema:

```text
relaxcorp_schema.sql
```

The schema contains three tables:

```text
users
games
scores
```

### Users

Stores application accounts and their state.

Relevant fields include:

* `id`
* `username`
* `password`
* `role`
* `time_left`
* `created_at`
* `last_login`
* `last_seen`
* `last_reset`

### Games

Stores the games available to the platform.

Fields include:

* `id`
* `name`
* `description`
* `created_at`

### Scores

Stores game results.

Fields include:

* `id`
* `user_id`
* `game_id`
* `score`
* `created_at`

The table relationships are:

```text
users ──────< scores >────── games
```

Each score belongs to one user and one game.

Foreign keys use cascading deletion.

### Create the database

Example:

```sql
CREATE DATABASE relaxcorp
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;
```

Create a dedicated database user and grant the required permissions according to the target environment.

Then import the schema:

```bash
mysql -u relaxuser -p relaxcorp < relaxcorp_schema.sql
```

The schema contains table definitions only and does not include application user data.

---

## Running the Application

After Apache and MySQL are configured, the application can be accessed through:

```text
http://<SERVER-IP>/relaxcorp/
```

For example, on the development server used during the project:

```text
http://192.168.1.100/relaxcorp/
```

The exact server IP depends on the deployment environment.

The application entry point is:

```text
/relaxcorp/index.php
```

---

## Application Routes

### Authentication

```text
/relaxcorp/
```

Main login page.

```text
/relaxcorp/auth/login.php
```

Authentication handler.

```text
/relaxcorp/auth/logout.php
```

Logout handler.

### User Area

```text
/relaxcorp/user/dashboard.php
```

User dashboard.

```text
/relaxcorp/user/stats.php
```

User statistics.

```text
/relaxcorp/user/ranking_game.php?game=<game>
```

Game ranking.

### Games

```text
/relaxcorp/games/index.php
```

Game selection.

```text
/relaxcorp/games/game1.php
```

Solitaire integration.

```text
/relaxcorp/games/game2.php
```

Clicker game.

### Administration

```text
/relaxcorp/admin/dashboard.php
```

Administrator dashboard.

```text
/relaxcorp/admin/create_user.php
```

User creation.

```text
/relaxcorp/admin/delete_user.php
```

User deletion.

### Score Endpoint

```text
POST /relaxcorp/api/save_score.php
```

Expected JSON payload:

```json
{
  "score": 100,
  "game": "clicker"
}
```

or:

```json
{
  "score": 100,
  "game": "solitario"
}
```

Authentication is based on the PHP session cookie.

Possible responses include:

```json
{
  "status": "ok",
  "score": 100
}
```

and error responses for unauthenticated users, invalid requests, or unknown games.

This endpoint is an HTTP JSON endpoint; it is not intended to represent a complete REST API.

---

## Games

### Solitaire

RelaxCorp includes a Klondike-style Solitaire game implemented with Phaser 3.

The Solitaire implementation is **based on Scott Westover's Phaser 3 Solitaire tutorial series**:

**Create Solitaire with Phaser 3 – Full Series**

The original tutorial covers the construction of the Solitaire game, including:

* Phaser 3 setup;
* card mechanics;
* drag-and-drop interaction;
* deck creation and shuffling;
* dealing;
* pile rules;
* scoring;
* win-condition handling;
* animations and user interaction.

For RelaxCorp, the game was integrated into the application's existing architecture and extended with project-specific functionality.

RelaxCorp-specific integration includes:

* authentication through the platform session;
* integration with the daily play-time system;
* score submission to the PHP backend;
* persistent score storage in MySQL;
* integration with rankings;
* integration with user statistics;
* integration with the application's PHP/MySQL architecture.

The game is loaded through:

```text
/games/solitario/
```

and integrated into:

```text
/games/game1.php
```

### Clicker

The Clicker game is implemented using Phaser 3.

Each game round lasts **30 seconds**.

The player must click randomly positioned targets. Each successful click increases the score.

The resulting score is sent to the PHP backend and stored in MySQL.

The game is implemented in:

```text
/games/game2.php
```

Phaser 3.80.0 is used by the Clicker implementation.

---

## Security

The project implements several basic security measures.

### Password Hashing

User passwords are stored using PHP's password hashing API:

```php
password_hash()
```

Authentication uses:

```php
password_verify()
```

The application therefore does not store plaintext passwords.

### Password Requirements

User passwords must:

* contain at least 8 characters;
* contain at least one uppercase character;
* contain at least one number;
* contain no spaces;
* contain neither `/` nor `\`.

### Prepared Statements

Database operations involving user-provided data use prepared `mysqli` statements.

### Session Security

PHP sessions are used for authentication.

After successful login, the application regenerates the session identifier:

```php
session_regenerate_id(true);
```

### Role-Based Access Control

Administrative pages verify the user's role before allowing access.

Regular users cannot access administrative functionality through the normal application flow.

### Credential Separation

Database credentials are not intended to be stored in the public repository.

Only example configuration values are included.

---

## Testing

The project was tested across the main application areas described in the final project documentation.

Testing covered:

### Authentication

* valid login;
* invalid username;
* invalid password;
* logout;
* session handling;
* role-based redirection.

### User Management

* user creation;
* duplicate user handling;
* user deletion;
* administrator restrictions.

### Time Control

* daily time initialization;
* time consumption;
* timeout handling;
* daily reset behavior.

### Games

* game loading;
* gameplay;
* Solitaire victory condition;
* Clicker scoring;
* score submission.

### Scores and Rankings

* score persistence;
* ranking retrieval;
* user statistics;
* recent results.

### General Stability

* navigation;
* authentication flow;
* application routes;
* session behavior;
* score registration;
* visual consistency.

---

## Screenshots

Screenshots of the application are provided in:

```text
screenshots/
```

Recommended screenshots include:

1. Login page
2. User dashboard
3. Game selection
4. Solitaire
5. Clicker
6. Ranking or statistics page
7. Administration panel

---

## Deployment

RelaxCorp was designed for deployment on a Linux server within a local network.

The documented project environment used:

```text
Ubuntu Server 24.04
Apache 2.4
PHP 8.3
MySQL 8.0
```

The application can be served using Apache's standard document root.

Example:

```text
/var/www/html/relaxcorp
```

The project does not include a virtual machine image or deployment VM as part of the repository.

The repository contains the application source code and database schema rather than a preconfigured server image.

---

## Network Configuration

The application was developed for use in a local network environment.

The server can be assigned a static IPv4 address so that client machines can access the application through:

```text
http://<SERVER-IP>/relaxcorp/
```

The exact IP address, gateway, DNS configuration, and network topology depend on the deployment environment and should not be hardcoded into the application source.

The original project documentation also described local DNS integration as part of the intended infrastructure, but DNS configuration is environment-specific and is not required for the application itself when accessed directly by IP address.

---

## Limitations

The current implementation has several known limitations.

### No HTTPS

The application was developed for a local network environment and does not currently provide HTTPS configuration.

### No Login Attempt Limiting

There is no dedicated mechanism to limit repeated failed login attempts.

### Login Information Disclosure

The login flow distinguishes between a non-existent user and an incorrect password.

### No CSRF Protection

Administrative user deletion is performed through a GET request and does not currently include CSRF protection.

### Client-Submitted Scores

Game scores are submitted by the browser.

The server validates the request format and game existence but does not independently reproduce or verify the game result.

Therefore, the current score system should not be considered cheat-resistant.

### External Phaser Dependency

The Clicker game loads Phaser through an external CDN.

As a result, the Clicker game requires browser access to the external resource unless the dependency is changed to a local copy.

The Solitaire implementation contains its own compiled JavaScript bundle.

### No Horizontal Scaling

The application was designed as a single-server project and does not currently provide:

* load balancing;
* horizontal scaling;
* distributed sessions;
* replicated databases.

### No Password Recovery

There is currently no password recovery or reset workflow.

### No Formal REST API

The project uses a small HTTP JSON endpoint for score submission rather than a complete REST API.

---

## Future Improvements

Possible future improvements include:

* additional minigames;
* responsive support for mobile and tablet devices;
* more advanced user statistics;
* multiplayer functionality;
* competitive online features;
* business productivity dashboards;
* password recovery;
* stronger authentication mechanisms;
* CSRF protection;
* server-side score validation;
* formal REST API design;
* migration to a framework such as Laravel;
* containerization with Docker;
* CI/CD pipelines;
* Kubernetes deployment;
* cloud deployment;
* horizontal scalability.

These are potential extensions rather than features currently implemented in the project.

---

## Third-Party Resources and Acknowledgements

### Solitaire Tutorial

The Solitaire implementation included in RelaxCorp is based on:

**Scott Westover — Create Solitaire with Phaser 3 – Full Series**

YouTube:

https://www.youtube.com/watch?v=Lm0cwEFuV6o

The original tutorial is a seven-part Phaser 3 Solitaire development series compiled into a single course video.

The corresponding source project is available through DevShare Academy's GitHub repository:

https://github.com/devshareacademy/phaser-3-solitaire-tutorial

The original DevShare Academy repositories are published under their own licensing terms. The original project repository is marked as MIT licensed.

RelaxCorp does not claim authorship of the original Solitaire implementation.

The RelaxCorp project adds application-specific integration and functionality around the game, including authentication, time control, score persistence, rankings, statistics, and integration with the PHP/MySQL backend.

### Phaser

RelaxCorp uses Phaser 3 as its browser game framework.

Phaser is an open-source HTML5 game framework. The Phaser project is distributed under the MIT License.

Phaser:

https://phaser.io/

GitHub:

https://github.com/phaserjs/phaser

### Card Assets and Audio

The original Solitaire tutorial credits **Kin** for the game's artwork and audio assets.

The assets used by the Solitaire implementation should be retained together with their original attribution and licensing information.

Source:

https://the-wild-kin.itch.io/kin-pixel-playing-cards

Additional audio resources included with the Solitaire implementation should likewise retain their original attribution and licensing information.

---

## Project Status

**Status: Completed**

RelaxCorp was completed as the final project for the Higher Technician in Network Computer Systems Administration (ASIR) during the 2025–2026 academic year.

The project demonstrates the integration of:

* Linux server administration;
* Apache;
* PHP;
* MySQL;
* HTML/CSS/JavaScript;
* Phaser 3;
* authentication;
* session management;
* access control;
* time management;
* database-backed scoring;
* rankings;
* statistics;
* browser-based games.

The project is presented as an academic and technical portfolio project demonstrating the integration of system administration, web development, databases, security, and infrastructure concepts.
