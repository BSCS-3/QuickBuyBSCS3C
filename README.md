# Project Name

Brief description of your project here.

## Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL
- Docker Desktop (if using Docker)

## Setup Instructions

### Option 1: Using Docker (Recommended)

1. **Install Docker Desktop**
   - Download and install from [Docker's website](https://www.docker.com/products/docker-desktop/)
   - Ensure Docker Desktop is running

2. **Clone the repository**
   ```bash
   git clone [your-repository-url]
   cd [project-directory]
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` file with your database credentials

4. **Start Docker containers**
   ```bash
   docker-compose up -d
   ```

5. **Run migrations and seeders**
   ```bash
   # Access the container's shell
   docker-compose exec app bash

   # Run migrations
   vendor/bin/phinx migrate

   # Run seeders
   vendor/bin/phinx seed:run
   ```

### Option 2: Manual Setup

1. **Clone the repository**
   ```bash
   git clone [your-repository-url]
   cd [project-directory]
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` file with your database credentials

4. **Run migrations**
   ```bash
   vendor/bin/phinx migrate
   ```

5. **Run seeders**
   ```bash
   vendor/bin/phinx seed:run
   ```

## Default Users

After running seeders, the following test users will be available:

| Username | Email             | Password    | Role  |
|----------|------------------|-------------|-------|
| admin    | admin@example.com| admin123    | admin |
| john_doe | john@example.com | password123 | user  |

## Database Migrations

### View Migration Status
