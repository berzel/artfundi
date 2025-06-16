# Installation Guide

## Prerequisites

- Ensure you have **Docker** installed.
- Ensure you have **Node.js (v20 or higher)** installed.

## Setup

Run the following commands in your terminal

```bash
git clone git@github.com:berzel/artfundi.git
cd artfundi
cd api
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
cd ../frontend
yarn install
yarn run dev
```

## Access the Application
Open your browser and navigate to: http://localhost:5173/

## Default Admin Credentials

- **Email:** `admin@test.com`
- **Password:** `password`