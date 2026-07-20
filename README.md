# PropertyRubix 🏠

> **Enterprise PHP Real Estate Portal** — MVC architecture, multi-role admin panel, property & project listings, location drill-down, blog, lead management.

[![CI](https://github.com/abhijeetpandeywork/property-rubix/actions/workflows/ci.yml/badge.svg)](https://github.com/abhijeetpandeywork/property-rubix/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/license-Proprietary-red)](LICENSE)

---

## ⚡ Quick Start (New Developer)

### Windows
```bat
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix
scripts\setup.bat
```

### Mac / Linux
```bash
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix
bash scripts/setup.sh
```

> **What setup does:** Creates `.env`, verifies DB, runs all migrations, installs dev tools.

---

## 🛠️ Prerequisites

| Tool | Version | Download |
|------|---------|---------|
| PHP | 8.1+ | [php.net](https://php.net) / XAMPP |
| MySQL | 8.0+ | [mysql.com](https://mysql.com) / XAMPP |
| Git | Any | [git-scm.com](https://git-scm.com) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org) *(optional, for dev tools)* |

---

## 📁 Project Structure

```
property-rubix/
├── .github/
│   ├── workflows/          # CI/CD pipelines
│   │   ├── ci.yml          # Lint, test, security on every PR
│   │   ├── deploy.yml      # Production deploy on tag
│   │   ├── pr-check.yml    # PR validation automation
│   │   └── security.yml    # Weekly security audit
│   ├── ISSUE_TEMPLATE/     # Bug & feature request templates
│   ├── CODEOWNERS          # Code review requirements
│   └── PULL_REQUEST_TEMPLATE.md
├── app/
│   ├── controllers/        # Request handlers (one per resource)
│   ├── core/               # Framework core (Router, Controller, View)
│   ├── helpers/            # Shared utility functions
│   └── views/              # PHP templates
│       ├── layouts/        # Base layouts (main.php)
│       ├── property/       # Property views
│       ├── project/        # Project views
│       ├── blog/           # Blog views
│       └── partials/       # Reusable view fragments
├── admin/                  # Admin panel (separate entry point)
│   ├── includes/           # Shared admin layout components
│   ├── properties/         # Property CRUD
│   ├── projects/           # Project CRUD
│   ├── leads/              # Lead management
│   └── ...
├── assets/
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript
├── config/
│   └── db.php              # DB connection + env loader
├── database/
│   ├── migrate.php         # Migration runner CLI
│   ├── migrations/         # Versioned SQL migration files
│   ├── schema.sql          # Full schema reference
│   └── seed.sql            # Sample data
├── scripts/
│   ├── setup.bat           # Windows quick setup
│   └── setup.sh            # Mac/Linux quick setup
├── uploads/                # User-uploaded files (gitignored)
├── .env.example            # Environment template
├── .editorconfig           # Editor formatting config
├── .gitattributes          # Git line ending config
├── .gitignore
├── composer.json           # PHP dev dependencies
├── Makefile                # Developer command shortcuts
├── index.php               # Front controller (all requests go here)
└── .htaccess               # URL rewriting
```

---

## 🗄️ Database Setup

### First time (automated)
The setup scripts handle this automatically via the migration system.

### Manual setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE property_rubix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php database/migrate.php

# Check status
php database/migrate.php --status
```

### Adding a new database change
```bash
# 1. Copy the template
cp database/migrations/_template.sql database/migrations/003_your_change_name.sql

# 2. Write your SQL in the file

# 3. Test it locally
php database/migrate.php

# 4. Commit the migration file with your code changes
git add database/migrations/003_your_change_name.sql
git commit -m "feat(db): add virtual_tour_url to properties"
```

> ⚠️ **Never edit existing migration files after they're committed** — create a new one instead.

---

## 🔧 Common Commands

| Command | Description |
|---------|------------|
| `make setup` | First-time setup |
| `make migrate` | Run pending migrations |
| `make migrate-status` | See what's applied |
| `make lint` | Check code style |
| `make lint-fix` | Auto-fix code style |
| `make syntax` | PHP syntax check |
| `make test` | Run tests |
| `make clean` | Clear logs |
| `php database/migrate.php --fresh` | Reset DB (⚠ destroys all data!) |

---

## 🌿 Branch Strategy

```
main        ← Production (protected, CI required, 1 review required)
  staging   ← Pre-production testing
    develop ← Integration branch (PR target for all features)
      feature/xxx   ← New features
      fix/xxx       ← Bug fixes
      hotfix/xxx    ← Emergency patches (from main)
```

### Start working on a feature
```bash
git checkout develop
git pull origin develop
git checkout -b feature/your-feature-name

# ... do work, commit often ...

git push -u origin feature/your-feature-name
# Open Pull Request to: develop
```

### Commit message format
```
feat(property): add virtual tour URL field
fix(auth): resolve session expiry on login
security(upload): validate MIME type before saving
docs(readme): update local setup instructions
chore(ci): add PHP 8.3 to test matrix
```

---

## 🌐 How Routing Works

All HTTP requests go through `index.php` via `.htaccess`.

```
Request: GET /property/my-property-slug
         ↓
.htaccess → rewrites to index.php
         ↓
index.php → Router::dispatch()
         ↓
Matches route: /property/{slug}
         ↓
PropertyController::detail(['slug' => 'my-property-slug'])
         ↓
$this->view('property/detail', $data)
         ↓
app/views/property/detail.php + app/views/layouts/main.php
```

---

## 🔒 Security Notes

- **CSRF**: All admin forms include a CSRF token (verified server-side)
- **SQL**: All queries use PDO prepared statements
- **Uploads**: MIME type verified, random filename, stored outside web root path
- **Auth**: Session-based with `session_regenerate_id()` on login
- **Passwords**: `password_hash()` with bcrypt, `password_verify()` for checks
- **XSS**: All output uses the `e()` helper: `echo e($userInput)`

---

## 👥 Team

| Role | GitHub | Responsibilities |
|------|--------|-----------------|
| Lead Developer | [@abhijeetpandeywork](https://github.com/abhijeetpandeywork) | Architecture, DB, Config |

---

## 📝 License

Proprietary — All rights reserved. Not for public distribution.
