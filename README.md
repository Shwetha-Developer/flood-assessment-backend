# Flood Assessment App — Backend API

REST API backend for the Madison County Flood Damage Assessment application, 
built for Ceres to digitize the flood damage assessment process for chicken farms.

## Tech Stack
- Laravel 13
- PHP 8.4
- MySQL 8
- Laravel Sanctum (Token Authentication)

## Requirements
- PHP 8.2+
- Composer
- MySQL
- XAMPP

## Installation & Setup

### Step 1 — Clone Repository
```bash
git clone https://github.com/Shwetha-Developer/flood-assessment-backend.git
cd flood-assessment-backend
```

### Step 2 — Install Dependencies
```bash
composer install
```

### Step 3 — Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4 — Configure `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flood_assessment
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
FRONTEND_URL=http://localhost:3000
```

### Step 5 — Create Database
Open phpMyAdmin and run:
```sql
CREATE DATABASE flood_assessment;
```

### Step 6 — Run Migrations
```bash
php artisan migrate
```

### Step 7 — Seed Default Users
```bash
php artisan db:seed --class=UserSeeder
```

### Step 8 — Start Server
```bash
php artisan serve
```
API runs at: `http://127.0.0.1:8000`

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Supervisor | supervisor@ceres.com | password123 |
| Assessor | assessor@ceres.com | password123 |

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | /api/login | No | Login |
| POST | /api/logout | Yes | Logout |
| GET | /api/me | Yes | Current user |
| GET | /api/assessments | Yes | Get assessments |
| POST | /api/assessments | Yes | Create assessment |
| POST | /api/assessments/batch-sync | Yes | Sync offline data |
| POST | /api/assessments/{id}/photos | Yes | Upload photo |
| GET | /api/photos/{id} | Yes | Get photo |
| GET | /api/export/csv | Yes | Export CSV |

## Assumptions Made

1. Assessors use Chrome browser on smartphones
2. Login requires internet — app works offline after login
3. One photo per assessment for reliability
4. Photos stored as Base64 in MySQL database
5. Supervisors see all assessments, assessors see only their own
6. Token never expires so assessors stay logged in all day
7. Data is specific to Madison County, NC

## Architecture & Design Decisions

### REST API Only
Laravel acts as a pure JSON REST API.
No Blade templates — React handles all UI.

### Duplicate Prevention
Every assessment has a UUID (local_id) generated offline.
Laravel checks local_id before inserting to prevent duplicates
when sync runs multiple times.

### Batch Sync
Single endpoint accepts array of assessments.
Reduces API calls when assessor has multiple offline records.

### Photo Storage
Photos compressed in React then stored as Base64 in MySQL.
Avoids file system complexity and permission issues.

### Role Based Access
- Assessor → sees only their own records
- Supervisor → sees all records + can export CSV
