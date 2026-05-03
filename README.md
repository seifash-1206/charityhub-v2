# 🚀 CharityHub v2

CharityHub is a modern web platform built with Laravel that allows users to explore fundraising campaigns and donate, while providing admins with full control over campaign management and financial tracking.

---

## 🧠 Project Overview

CharityHub is designed to simulate a real-world charity system with:

- 🔐 Secure authentication system
- 👑 Role-based access (Admin / User)
- 📊 Campaign management
- 💰 Donation tracking (coming next)
- 🎯 Clean modern UI (glassmorphism design)

---

## ⚙️ Tech Stack

- Laravel 13
- PHP 8.4
- MySQL (XAMPP)
- Tailwind CSS
- Blade Templates

---

## 🔑 Features

### 👤 Users
- Register & Login
- View campaigns
- Donate to campaigns *(in progress)*

### 👑 Admins
- Secure login with admin verification key
- Create campaigns
- Edit campaigns
- Delete campaigns
- Monitor system data

---

## 🔐 Security Features

- Password hashing using Laravel
- Role-based authorization (Policies)
- Admin verification layer
- CSRF protection

---

## 📂 Installation

```bash
git clone https://github.com/seifash-1206/charityhub-v2.git
cd charityhub-v2
composer install
npm install
cp .env.example .env
php artisan key:generate
