# Stockvel Credit System

![PHP](https://img.shields.io/badge/PHP-7.4-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7-orange)
![Apache](https://img.shields.io/badge/Apache-2.4-red)
![Docker](https://img.shields.io/badge/Docker-Supported-2496ED)
![License](https://img.shields.io/badge/License-MIT-green)

A multi-role community savings and credit platform inspired by the **Stockvel/Stokvel** financial model. Users can create savings groups, invite members, manage contributions, schedule withdrawals, and administer group activities through role-based dashboards.

**Live Demo**

http://stockvell.allinone-office.com

---

# Table of Contents

* Overview
* Tech Stack
* Features
* Project Structure
* Quick Start
* Running with Docker
* Running without Docker
* Environment Variables
* Database Migration
* Apache Configuration
* Security Notes
* Roadmap
* Known Issues

---

# Overview

Stockvel is a collaborative savings platform where multiple members contribute fixed amounts periodically and receive payouts according to predefined rules.

The application supports:

* User registration & authentication
* Leader and admin roles
* Group (Stockvel Pack) management
* Member invitations
* Identity verification
* Contribution tracking
* Withdrawal scheduling
* Administrative approval workflows

---

# Tech Stack

## Backend

* PHP 7.4
* Apache
* PDO
* MySQL 5.7

## Package Management

* Composer

## Environment

* PHP Dotenv

## Containerization

* Docker
* Docker Compose

## Database

* MySQL

---

# Features

### Authentication

* Login
* Signup
* Password recovery

### Role Management

* Admin
* Leader
* Member

### Stockvel Packs

* Create packs
* Join packs
* Share invite links
* Configure contribution rules
* Configure withdrawal rules

### Administration

* User management
* Leader approval
* Document verification
* Pack moderation

### Multi-language

* English
* French (ready)

---

# Project Structure

```
.
├── config/
├── includes/
├── layouts/
├── Models/
├── pages/
├── public/
├── uploads/
├── utils/
├── languages/
├── index.php
├── composer.json
├── Dockerfile
├── docker-compose.yml
└── database-migrations.php
```

---

# Quick Start

Clone the repository

```bash
git clone <repository-url>

cd php-stockvell
```

Create an environment file

```bash
cp .env.example .env
```

Update the database credentials and application settings.

---

# Running with Docker (Recommended)

## Build the containers

```bash
docker compose build
```

## Start the application

```bash
docker compose up -d
```

## View running containers

```bash
docker compose ps
```

The application will be available at

```
http://localhost:8000
```

---

## Install Composer Dependencies

If dependencies are not already installed:

```bash
docker compose exec app composer install
```

or

```bash
docker exec -it php_finance_con composer install
```

---

## Run Database Migration

Open your browser:

```
http://localhost:8000/database-migrations.php
```

or execute inside the container:

```bash
docker exec -it php_finance_con php database-migrations.php
```

After all tables are created successfully:

**Delete or disable**

```
database-migrations.php
```

before deploying to production.

---

## Stop Containers

```bash
docker compose down
```

Remove volumes

```bash
docker compose down -v
```

Remove images

```bash
docker rmi $(docker images -q)
```

---

# Running without Docker

## Requirements

* PHP 7.4+
* Apache
* MySQL 5.7+
* Composer

---

## Install Dependencies

```bash
composer install
```

---

## Configure Apache

Enable mod_rewrite

```bash
sudo a2enmod rewrite
```

Allow .htaccess

```apache
<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>
```

Restart Apache

```bash
sudo systemctl restart apache2
```

---

## Configure Environment

Create

```
.env
```

Example

```env
APP_NAME=Stockvel

MYSQL_HOST=localhost
MYSQL_DATABASE=stockvel_db
MYSQL_USER=root
MYSQL_PASSWORD=password

JWT_SECRET_KEY=secret
JWT_ALGORITHM=HS256
```

---

## Create Database

```sql
CREATE DATABASE stockvel_db;
```

Run

```
http://localhost/database-migrations.php
```

or

```bash
php database-migrations.php
```

After the migration succeeds, delete the migration file.

---

# Environment Variables

| Variable       | Description       |
| -------------- | ----------------- |
| APP_NAME       | Application name  |
| MYSQL_HOST     | MySQL server      |
| MYSQL_DATABASE | Database name     |
| MYSQL_USER     | Database username |
| MYSQL_PASSWORD | Database password |
| JWT_SECRET_KEY | JWT signing key   |
| JWT_ALGORITHM  | JWT algorithm     |

---

# Security Notes

Before deploying to production:

* Delete `database-migrations.php`
* Delete `phpinfo.php`
* Disable `display_errors`
* Protect `.env`
* Validate uploaded files
* Restrict upload size
* Enable HTTPS
* Use strong database passwords

---

# Roadmap

* Email notifications
* SMS integration
* Analytics dashboard
* Pack scheduling
* WhatsApp invitations
* Better multilingual support
* Payment gateway integration
* Contribution history

---

# Known Issues

* Signup validation can reset the form
* Some admin image routes need improvement
* Image validation needs additional checks
* Minor UI inconsistencies on admin pages

---

# Development Notes

This project uses a simple front-controller architecture.

```
index.php
        │
        ▼
Route Detection
        │
        ▼
pages/*.php
        │
        ▼
Models / includes / layouts
```

Apache rewrites every request to `index.php`, where routing is handled manually.

---

# License

This project is provided for educational and demonstration purposes.

---

# Author

**Md Samsuzzoha Shayon**

Full Stack Web Developer

Node.js • PHP • Python • React • Docker • Linux
