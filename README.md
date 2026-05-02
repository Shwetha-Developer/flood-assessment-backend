# Flood Assessment App — Backend

Laravel REST API for the Madison County Flood Damage Assessment application.

## Tech Stack
- Laravel 13
- MySQL 8
- Laravel Sanctum (Authentication)
- PHP 8.4

## Setup Instructions

### Requirements
- PHP 8.2+
- Composer
- MySQL
- XAMPP or Laravel Herd

### Installation

1. Clone the repository
```bash
   git clone https://github.com/YOUR_USERNAME/flood-assessment-app.git
   cd flood-assessment-backend
```

2. Install dependencies
```bash
   composer install
```

3. Copy environment file
```bash
   cp .env.example .env
   php artisan key:generate
```

4. Configure `.env`
```env
   DB_DATABASE=flood_assessment
   DB_USERNAME=root
   DB_PASSWORD=
   FRONTEND_URL=http://localhost:3000
   SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

5. Create database and run migrations
```bash
   php artisan migrate
   php artisan db:seed --class=UserSeeder
```

6. Start the server
```bash
   php artisan serve
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/login | Login |
| POST | /api/logout | Logout |
| GET | /api/me | Current user |
| GET | /api/assessments | Get all assessments |
| POST | /api/assessments | Create assessment |
| POST | /api/assessments/batch-sync | Batch sync offline data |
| POST | /api/assessments/{id}/photos | Upload photos |
| GET | /api/export/csv | Export CSV |

## Default Users

| Role | Email | Password |
|------|-------|----------|
| Supervisor | supervisor@ceres.com | password123 |
| Assessor | assessor@ceres.com | password123 |

## Assumptions

1. Assessors use modern smartphones with Chrome browser
2. Login requires internet — app works fully offline after login
3. Photos stored as Base64 and saved to local storage
4. Duplicate prevention using UUID local_id
5. Supervisor can see all assessments, assessors see only their own
