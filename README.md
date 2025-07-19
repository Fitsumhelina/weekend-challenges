# Kitat Management - CMS

>Wee-2 challenge

A Laravel-based application for managing kitats.

## Requirements

- PHP >= 8.1
- Composer
- MySQL or PostgreSQL
- Node.js & npm
- Git

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/tetercreatives/kitat-management.git
cd kitat-management
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update `.env` with your database and other credentials.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. (Optional) Seed Database

```bash
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
npm run dev
```

### 8. Start the Development Server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.


