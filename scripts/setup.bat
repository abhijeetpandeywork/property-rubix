@echo off
REM ============================================================
REM PropertyRubix — Windows Quick Setup Script
REM Run this once when you first clone the project
REM Usage: scripts\setup.bat
REM ============================================================

setlocal enabledelayedexpansion
title PropertyRubix — Developer Setup

echo.
echo  ===========================================
echo   PropertyRubix ^- Developer Setup (Windows)
echo  ===========================================
echo.

REM ── Step 1: Check PHP ───────────────────────────────────────────────────────
echo [1/5] Checking PHP...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo   ERROR: PHP not found in PATH.
    echo   Add XAMPP\php to your system PATH and re-run.
    echo   Example: C:\xampp\php
    pause
    exit /b 1
)
for /f "tokens=2" %%v in ('php -v 2^>nul ^| findstr /r "^PHP "') do (
    echo   OK: PHP %%v found
)

REM ── Step 2: Create .env ─────────────────────────────────────────────────────
echo.
echo [2/5] Setting up environment...
if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo   CREATED: .env from .env.example
    echo.
    echo   *** IMPORTANT: Open .env and set your database credentials! ***
    echo   Press any key after editing .env to continue...
    notepad .env
    pause >nul
) else (
    echo   OK: .env already exists
)

REM ── Step 3: Verify .env has DB settings ─────────────────────────────────────
echo.
echo [3/5] Verifying database connection...
php -r "
    define('CLI_ONLY', true);
    require 'config/db.php';
    try {
        db();
        echo '  OK: Database connection successful' . PHP_EOL;
    } catch (Throwable \$e) {
        echo '  ERROR: ' . \$e->getMessage() . PHP_EOL;
        echo '  Check your .env DB_ settings.' . PHP_EOL;
        exit(1);
    }
" 2>&1
if %errorlevel% neq 0 (
    echo.
    echo   Fix your .env database settings and re-run this script.
    pause
    exit /b 1
)

REM ── Step 4: Run migrations ───────────────────────────────────────────────────
echo.
echo [4/5] Running database migrations...
php database/migrate.php
if %errorlevel% neq 0 (
    echo   ERROR: Migration failed. Check output above.
    pause
    exit /b 1
)

REM ── Step 5: Install Composer (optional) ─────────────────────────────────────
echo.
echo [5/5] Checking Composer...
composer -V >nul 2>&1
if %errorlevel% neq 0 (
    echo   INFO: Composer not found. Dev tools (phpcs, phpunit) won't be available.
    echo   Install from: https://getcomposer.org/
) else (
    echo   Installing dev dependencies...
    composer install --dev --no-interaction
    echo   OK: Composer packages installed
)

REM ── Done ────────────────────────────────────────────────────────────────────
echo.
echo  ===========================================
echo   Setup Complete!
echo  ===========================================
echo.
echo   Access the site at: http://localhost/property-rubix/
echo   Admin panel:        http://localhost/property-rubix/admin/
echo.
echo   Default admin login (change immediately!):
echo   Email:    admin@propertyrubix.com
echo   Password: admin123
echo.
pause
endlocal
