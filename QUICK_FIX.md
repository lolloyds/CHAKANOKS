# 🚨 QUICK FIX: Database Doesn't Exist!

## The Problem:
The error log shows: **"Unknown database 'chakanoks_db'"**

The database `chakanoks_db` doesn't exist in MySQL, so migrations and login won't work.

## Solution - Choose ONE method:

### Method 1: Using phpMyAdmin (Easiest)
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Paste this SQL:
   ```sql
   CREATE DATABASE IF NOT EXISTS `chakanoks_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
4. Click "Go"
5. Then run migrations again: `php spark migrate`
6. Then run seeders: `php spark db:seed DatabaseSeeder`

### Method 2: Using MySQL Command Line
1. Open Command Prompt or PowerShell
2. Navigate to MySQL bin directory (usually `C:\xampp\mysql\bin`)
3. Run:
   ```bash
   mysql.exe -u root -p
   ```
   (Press Enter if password is empty)
4. Run this SQL:
   ```sql
   CREATE DATABASE IF NOT EXISTS `chakanoks_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```
5. Then run migrations: `php spark migrate`
6. Then run seeders: `php spark db:seed DatabaseSeeder`

### Method 3: Using the SQL File
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Copy contents of `create_database.sql` and paste
4. Click "Go"
5. Then run: `php spark migrate` and `php spark db:seed DatabaseSeeder`

## After Creating Database:

1. **Run Migrations** (creates tables):
   ```
   php spark migrate
   ```

2. **Run Seeders** (creates users):
   ```
   php spark db:seed DatabaseSeeder
   ```

3. **Try Login**:
   - Username: `sysadmin`
   - Password: `password`
   - URL: `http://localhost/CHAKANOKS/login`

## Verify Database Exists:
- Open phpMyAdmin
- You should see `chakanoks_db` in the left sidebar
- Click on it and verify these tables exist:
  - `branches`
  - `users`
  - `items`
  - `branch_stock`
  - `stock_movements`
  - `deliveries`
  - `delivery_items`

## Why This Happened:
The migrations ran but couldn't create tables because the database itself didn't exist. MySQL migrations in CodeIgniter assume the database already exists - they only create tables inside the database.

