# Stockvell Credit System

A multi-role financial group savings platform (Stokvel-style system) where users can create, join, and manage savings groups with defined financial goals, contributions, and withdrawals.

🔗 Live Demo: http://stockvell.allinone-office.com

---

## 📌 Table of Contents

- Overview
- Key Features
- System Architecture
- User Roles
- Core Modules
- Installation
- Deployment Guide
- Environment Setup
- Database Setup
- Apache Configuration
- Composer
- Security Notes
- Business Rules
- Roadmap / TODO
- Known Issues
- Configuration Notes

---

## 🧠 Overview

Stockvell is a group-based credit and savings platform where:

- Users form financial groups (Stockvell packs)
- Each group has a defined savings goal
- Members contribute periodically (weekly/monthly)
- Leaders manage group operations
- Admin oversees approvals, compliance, and governance

The system supports multi-role workflows, identity verification, and financial group lifecycle management.

---

## ✨ Key Features

- Role-based access control (Admin, Leader, Member)
- Group savings (Stockvell packs)
- Member invitation & join requests
- Leader approval workflow
- Admin governance & moderation
- Contribution tracking system
- Withdrawal scheduling system
- Shareable group links
- Document verification (ID, address proof)
- Multi-language support (English / French ready)

---

## 🏗 System Architecture

### Core Entities

- Users
- Members
- Leaders
- Admins
- Stockvell Packs
- Membership Requests
- Withdrawal Schedule
- Contributions

### Relationships

- One User → Many Packs (as member or leader role)
- One Pack → Many Members
- One Pack → One Active Leader (at a time)
- Admin oversees all packs and users

---

## 👥 User Roles

### 1. Admin
- Approves/rejects packs
- Assigns/revokes leaders
- Manages users and documents
- Controls platform configuration
- Approves pack closure

---

### 2. Leader
- Creates Stockvell packs
- Defines pack rules (goal, amount, frequency, limits)
- Manages members
- Controls withdrawal order logic
- Generates shareable invite links

---

### 3. Member
- Joins available packs
- Contributes funds
- Requests to become leader (with verification)
- Uploads identity documents (if required)
- Views pack progress

---

## ⚙️ Core Modules

### 📦 Stockvell Pack Module
- Create pack (goal, amount, duration, currency)
- Start/end date support
- Member limit enforcement
- Payment frequency configuration
- Withdrawal frequency configuration

---

### 👤 Membership Module
- Join request system
- Admin approval workflow
- Leader approval workflow
- Member status tracking

---

### 🧾 Identity Verification
- Government ID upload
- Address proof upload
- Admin review system

---

### 💰 Contribution System
- Monthly/weekly payments
- Payment tracking per member
- Pack balance tracking

---

### 🔄 Withdrawal System
- First-come-first-serve OR random selection
- Admin override option
- Scheduled withdrawal cycle

---

### 🔗 Sharing System
- Shareable pack links
- WhatsApp / social media sharing
- Invite-based joining flow

---

## 🚀 Installation

### 1. Clone / Upload Project

Upload project to server directory:

```

/var/www/html/stockvell

````

---

### 2. Set Permissions

```bash
sudo chown -R www-data:www-data stockvell
sudo chmod -R 775 stockvell
````

---

### 3. Install Dependencies

```bash
composer install
```

If needed:

```bash
composer update
```

---

## 🌍 Environment Setup

Create `.env` file:

```env
DB_HOST=
DB_NAME=
DB_USER=
DB_PASSWORD=

APP_URL=
MAIL_CONFIG=
SMS_CONFIG=
```

---

## 🗄 Database Setup

1. Create fresh database
2. Run migration script:

```bash
php database-migrations.php
```

3. After migration:

* ❌ DELETE `database-migrations.php`

---

## 🌐 Apache Configuration

Enable `.htaccess` support:

```apache
<Directory /var/www/html>
    AllowOverride All
</Directory>
```

Restart Apache:

```bash
sudo systemctl restart apache2
```

---

## 📦 Composer

```bash
composer install
composer update
composer show
```

---

## 🔐 Security Notes

* Disable error display in production
* Remove debug files:

  * `phpinfo.php`
  * migration scripts
* Validate all uploads (max 2MB)
* Secure `.env` file
* Prevent direct script access

---

## 📌 Business Rules

### Stockvell Creation Rules

* Only Admin or Leader can create a pack
* Member must request leader role with verification
* Pack becomes active only after admin approval

---

### Membership Rules

* Member must accept terms before joining
* Pack cannot exceed member limit
* Join requests require approval

---

### Leader Rules

* Only one active leader per pack
* Leader can be suspended by admin
* Leader controls withdrawal order logic

---

### Withdrawal Rules

* Modes:

  * First come, first served
  * Random selection (optional)
* Admin can override selection

---

## 🛣 Roadmap / TODO

* Add email + SMS notifications on events
* Improve shareable invite system (WhatsApp integration)
* Add pack review screen before creation
* Add balance sheet per pack
* Add scheduling tool (meetings)
* Improve multilingual system (FR/EN)
* Add admin staff roles
* Add analytics dashboard

---

## 🐞 Known Issues

* Signup form resets on validation error (needs localStorage fix)
* Some admin pages return 404 on uploaded document view
* Leader duplication issue in some packs
* Payment frequency sometimes returns 0
* Missing country options (e.g. USA)
* UI inconsistencies in admin tables
* Image upload validation incomplete in some flows

---

## ⚙️ Configuration Notes

* Stockvell categories are defined in:

  ```
  /config/option-list.php
  ```

* Language system:

  ```
  /languages/fr.php
  ```

* Admin seed:

  ```
  database-migrations.php
  ```

---

## 📄 Notes

* Rename “Stockvell” → “Stockvel” is planned
* System supports both English and French translations
* UI must remain responsive across all devices

---

## 👨‍💻 Author

Stockvell Financial System
Internal Group Savings Platform

