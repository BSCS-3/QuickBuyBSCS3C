# BSCS3C QuickBuy E-Commerce Webapp Group Project

## Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL
- Enabled PDO MySQL Extension for Database
- Docker Desktop (optional)


### Enable PDO MySQL Extension for Database Migrations

1. Find the correct php.ini file:
   - Open a terminal and run "php --ini"
   - There should be a "Loaded Configuration File" there with the directory of your "php.ini" (Example: "C:\php-8.3.0\php.ini")
   - Follow that directory to get to your php.ini file (sometimes display as just "php")

2. Edit the php.ini in Notepad:
   - Open File Explorer
   - Go to `C:\php` folder
   - Right-click `php.ini`
   - Select "Open with" → Notepad

3. Enable PDO MySQL:
   - Press Ctrl + F
   - Search for "pdo_mysql"
   - Find this line: `;extension=pdo_mysql`
   - Remove the semicolon (;) so it looks like: "extension=pdo_mysql"
   - Save the file

Congrats! You have now enable PDO_MySql Extension!!
  

## Setup Instructions

### Option 1: Manual Setup

1. **Clone the repository**
   - in your "xampp\htdocs" folder, clone or extract the project

3. **Install dependencies**
   - In a terminal within "QuickBuyBSCS3C\api", enter:
    ```bash
   composer install
     ```
   
4. **Run migrations**
   - within "QuickBuyBSCS3C\api", enter:
   ```bash
   vendor/bin/phinx migrate
   ```
 
   
5. **Run seeders**
   - within "QuickBuyBSCS3C\api", enter:
   ```bash
   vendor/bin/phinx seed:run
   ```


   
### Option 2: Using Docker (Para sa mga pasikat)

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


## Default Users

After running seeders, the following test users will be available:

| Username | Email             | Password    | Role  |
|----------|------------------|-------------|-------|
| admin    | admin@example.com| admin123    | admin |
| john_doe | john@example.com | password123 | user  |

