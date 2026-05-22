# 🚀 FitCore SaaS — Elite Fitness & Analytics Platform

FitCore is a complete fitness tracking and coaching platform built with **Laravel 12**, **Blade**, **Tailwind CSS**, and **Vite**. It includes workout programs, diet planning, trainer booking, progress analytics, blogging, likes, comments, and admin management.

## 🔍 What this project includes

- User authentication and account management
- Workout plan library, workout tracking, and progress logs
- Diet planning and follow/download diet plans
- Trainer booking, availability, and session management
- Blog section with posts, likes, comments, and post creation
- Admin dashboard for workout and blog management
- Export tools for progress, workouts, diet, and invoices
- API endpoints for workout progress, recommendations, achievements, and user stats
- Responsive UI with Light/Dark support and real-time analytics

## ✅ Key features

- **Fitness & workout management**: workout plans, session tracking, progress charts
- **Nutrition support**: diet plan generation, follow diet routines, download diet plans
- **Calorie & nutrition tracking**: calorie logs, meal tracking, and nutrition analytics
- **Trainer marketplace**: session booking, rescheduling, cancellation, available slots
- **Trainer dashboard**: trainer availability, bookings, and session management for trainers
- **Blog features**: browse posts, like posts, comment, create posts (authenticated users)
- **Admin blogging**: create/update/delete blog posts and manage categories
- **Authentication**: login, register, password reset, email verification
- **Local storage support**: blog image uploads using Laravel public storage

## 🧩 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- Git
- One of:
  - SQLite (default)
  - MySQL
  - PostgreSQL

## 🚀 Local setup

Open a terminal in the project root:

```powershell
cd C:\Users\ASUS\Downloads\fitness-tracker-app-main\fitness-tracker-app-main
```

### 1. Install PHP dependencies

```powershell
composer install
```

### 2. Install Node dependencies

```powershell
npm install
```

### 3. Copy environment file

```powershell
copy .env.example .env
```

### 4. Generate application key

```powershell
php artisan key:generate
```

### 5. Create local SQLite database (recommended)

```powershell
type nul > database\database.sqlite
```

### 6. Update `.env` for SQLite

Make sure these values are set in your `.env` file:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

If you prefer MySQL, configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` instead.

### 7. Run migrations and seed demo data

```powershell
php artisan migrate --seed
```

### 8. Create public storage link

```powershell
php artisan storage:link
```

### 9. Start the Vite development server

```powershell
npm run dev
```

### 10. Start the Laravel app server

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Open your browser at:

```text
http://127.0.0.1:8000
```

## 🧪 Useful commands

```powershell
npm run dev           # Run frontend dev server
npm run build         # Build production assets
php artisan serve     # Run Laravel development server
php artisan test      # Run automated tests
php artisan migrate   # Run database migrations
php artisan db:seed   # Run seeders
php artisan storage:link # Create storage symlink for public files
```

## 🎯 Demo accounts

These users are created automatically when seeding the database:

- **Admin**: admin@fitnesspro.com / password
- **Demo user**: demo@fitnesspro.com / password

## 📌 Blog feature for users

Authenticated users can:

- View blog listings at `/blog`
- Open blog posts at `/blog/{slug}`
- Like posts
- Leave comments on posts
- Create a new blog post via `/blog/create`

Admins can manage blog posts and categories from `/admin/blog`.

## ⚠️ Notes

- If you upload blog images, the `php artisan storage:link` command is required to serve them from `storage/app/public`.
- Google OAuth values in `.env` are placeholders and require your own credentials if social login is enabled:

```dotenv
GOOGLE_CLIENT_ID=YOUR_GOOGLE_CLIENT_ID
GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

## 🛠️ Project structure highlights

- `app/Http/Controllers/` — application controllers
- `app/Models/` — database models
- `database/migrations/` — schema definitions
- `database/seeders/` — demo data and sample accounts
- `resources/views/` — Blade templates and page views
- `routes/web.php` — public and authenticated routes
- `package.json` / `vite.config.js` — frontend build tools

## 📦 Dependencies

- Laravel 12
- Laravel Sanctum
- Laravel Socialite
- Tailwind CSS
- Vite
- Axios
- Chart.js

## 📄 License

This project is licensed under the MIT License.
