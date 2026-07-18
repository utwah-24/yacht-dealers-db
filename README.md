<div align="center">

# ⛵ DSM Yacht Dealers

### Yacht &amp; Catamaran Fleet Management Platform

*Manage your fleet, routes, bookings, and guests — all from one elegant dashboard.*

<br/>

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)](LICENSE)

</div>

---

## 🌊 Overview

**DSM Yacht Dealers** is a web platform for running a yacht and catamaran charter business. It gives operators a single dashboard to manage their fleet, publish sailing routes and destinations, take bookings, register guests, and track charter summaries — backed by a clean REST API and interactive documentation.

Whether it's a **half-day Dar es Salaam tour** or a **live-aboard Zanzibar adventure**, every catamaran, route, and reservation is organized in one place.

---

## ✨ Features

| | Feature | Description |
| :---: | :--- | :--- |
| ⛵ | **Fleet Management** | Add and manage catamarans with names, services, descriptions, and photo galleries. |
| 🗺️ | **Routes & Destinations** | Define departures with multiple destinations — one catamaran can serve many routes. |
| 💰 | **Bookings & Pricing** | Charter types (Half day, Full day, Live Onboard) across Dar tours and Zanzibar, priced by duration. |
| 👥 | **Guest Registration** | Capture guest details and personal requests per booking. |
| 🧾 | **Charter Summaries** | Track a clear summary for every trip. |
| 📸 | **Photo Uploads** | Attach and serve catamaran photos via storage links. |
| 📊 | **Admin Dashboard** | A modern, responsive dashboard to oversee the whole operation. |
| 🔌 | **REST API + Swagger** | Versioned `v1` API with interactive OpenAPI documentation. |

---

## 🧭 Data Model

```
Catamaran ──┬── Routes        (departure → many destinations)
            ├── Photos        (gallery / storage links)
            └── Bookings ──┬── Extras
                           ├── Guest
                           ├── Personal Requests ── Others
                           └── Summary
```

---

## 🛠️ Tech Stack

- **Backend:** Laravel 13 (PHP 8.4+)
- **Frontend:** Blade, Tailwind CSS 4, Vite 8
- **Database:** MySQL
- **API Docs:** L5-Swagger (OpenAPI)
- **Deployment:** GitHub Actions → cPanel (FTP)

---

## 🚀 Getting Started

### Prerequisites

- PHP **8.4+**
- Composer
- Node.js 20+ & npm
- MySQL

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/utwah-24/yacht-dealers-db.git
cd yacht-dealers-db

# 2. Install dependencies
composer install
npm install

# 3. Set up your environment
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env, then migrate
php artisan migrate

# 5. Build assets and start the dev server
npm run dev
php artisan serve
```

Visit **http://localhost:8000** and you'll land on the dashboard.

---

## 📡 API

The REST API is versioned under the `/api/v1` prefix.

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/v1/catamarans` | List all catamarans |
| `POST` | `/api/v1/catamarans` | Create a catamaran |
| `POST` | `/api/v1/catamarans/{id}/routes` | Add a route to a catamaran |
| `POST` | `/api/v1/catamarans/{id}/photos` | Upload a catamaran photo |
| `GET` | `/api/v1/bookings` | List all bookings |
| `POST` | `/api/v1/bookings/{id}/guest` | Register a guest for a booking |
| `POST` | `/api/v1/bookings/{id}/summary` | Add a charter summary |

> 📖 Full interactive documentation is available via **Swagger UI** at `/api/documentation` once the app is running.

---

## 🖥️ Dashboard Pages

| Page | Route | Purpose |
| :--- | :--- | :--- |
| Dashboard | `/dashboard` | Overview of the whole operation |
| Catamarans | `/catamarans` | Manage the fleet |
| Routes | `/routes` | Manage departures & destinations |
| Bookings | `/bookings` | View and manage charters |
| Guests | `/guests` | Guest records |
| Summaries | `/summaries` | Charter summaries |
| Settings | `/settings` | Application settings |

---

## 🌐 Deployment

This project auto-deploys to **cPanel** on every push to `main` via GitHub Actions.

1. Code is pushed to `main`
2. GitHub Actions builds PHP + frontend assets
3. Files are uploaded to the server over FTP

> ⚠️ Database migrations are **not** run automatically. After deploying schema changes, run on the server:
>
> ```bash
> php artisan migrate --force
> ```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

<div align="center">

<br/>

**Built with ⛵ for the open water.**

</div>
