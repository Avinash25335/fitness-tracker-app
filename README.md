# 🚀 FitCore SaaS — Elite Fitness & Analytics Platform

FitCore is a high-performance, theme-adaptive fitness tracking application built with **Laravel 12**. It features advanced workout engineering, autonomous diet planning, real-time analytics, and a professional trainer marketplace.

![Dashboard Preview](https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1200&auto=format&fit=crop)

## ✨ Key Features

- **Adaptive Theme Engine**: Seamlessly switch between Light and Dark modes with persistent user preferences.
- **Elite Analytics**: Real-time performance intelligence, PR tracking, and AI-driven coaching insights.
- **Training Hub**: Comprehensive library of beginner to elite workout programs with interactive session tracking.
- **Nutrition Hub**: Autonomous diet engineering using Mifflin-St Jeor and TDEE calculation.
- **Trainer Marketplace**: Real-time booking and scheduling system for professional coaching.

---

## 🛠️ Installation Guide

Follow these steps to get your local development environment running:

### 1. Clone the Repository
```bash
git clone https://github.com/Avinash25335/fitness-tracker-app.git
cd fitness-tracker-app
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run dev
```

### 3. Environment Configuration
Copy the example environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup (CRITICAL)
Configure your database settings in the `.env` file, then run the migrations and **seeders** to populate the training programs and demo data:
```bash
php artisan migrate --seed
```
> [!IMPORTANT]
> **You MUST run the seeders** (`--seed`) to see the workout plans, diet strategies, and blog content. Without seeding, the dashboard will appear empty.

### 5. Launch the Server
```bash
php artisan serve
```

---

## 🎯 Seeding Data

The following demo accounts are created during seeding:

- **Admin Account**: `admin@fitnesspro.com` / `password`
- **Demo User**: `demo@fitnesspro.com` / `password`

---

## 🧪 Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Database**: MySQL / PostgreSQL / SQLite
- **Charts**: Chart.js 4.0
- **Animations**: Framer Motion inspired CSS animations

---

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
