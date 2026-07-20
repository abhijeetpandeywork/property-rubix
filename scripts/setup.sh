#!/bin/bash
# ============================================================
# PropertyRubix — Mac/Linux Quick Setup Script
# Run this once when you first clone the project
# Usage: bash scripts/setup.sh
# ============================================================

set -e
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; CYAN='\033[0;36m'; NC='\033[0m'; BOLD='\033[1m'

separator() { echo -e "${CYAN}$(printf '%.0s─' {1..50})${NC}"; }
ok()  { echo -e "  ${GREEN}✓ $1${NC}"; }
err() { echo -e "  ${RED}✗ $1${NC}"; exit 1; }
warn(){ echo -e "  ${YELLOW}⚠ $1${NC}"; }
info(){ echo -e "  ${CYAN}→ $1${NC}"; }

separator
echo -e "${BOLD}  PropertyRubix — Developer Setup (Mac/Linux)${NC}"
separator
echo ""

# ── Step 1: Check PHP ───────────────────────────────────────────────────────
info "Checking PHP..."
if ! command -v php &> /dev/null; then
    err "PHP not found. Install PHP 8.1+ via your package manager."
fi
PHP_VER=$(php -r "echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;")
if (( $(echo "$PHP_VER < 8.1" | bc -l) )); then
    err "PHP 8.1+ required. Found: $PHP_VER"
fi
ok "PHP $PHP_VER found"

# ── Step 2: Create .env ─────────────────────────────────────────────────────
echo ""
info "Setting up environment..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    ok "Created .env from .env.example"
    echo ""
    warn "Edit .env with your local database credentials, then press ENTER to continue."
    echo "  Opening .env now..."
    # Try to open in editor
    if command -v code &>/dev/null; then
        code .env
    elif command -v nano &>/dev/null; then
        nano .env
    elif command -v vim &>/dev/null; then
        vim .env
    else
        cat .env
        echo ""
        warn "Edit .env manually, then press ENTER"
    fi
    read -r -p "  Press ENTER when .env is configured... "
else
    ok ".env already exists"
fi

# ── Step 3: Test DB connection ───────────────────────────────────────────────
echo ""
info "Verifying database connection..."
php -r "
    define('CLI_ONLY', true);
    require 'config/db.php';
    try { db(); echo '  Connected successfully' . PHP_EOL; }
    catch (Throwable \$e) { echo 'ERROR: ' . \$e->getMessage() . PHP_EOL; exit(1); }
" || err "Database connection failed. Fix your .env DB_ settings."
ok "Database connection verified"

# ── Step 4: Run migrations ───────────────────────────────────────────────────
echo ""
info "Running database migrations..."
php database/migrate.php || err "Migration failed. Check output above."

# ── Step 5: Composer ─────────────────────────────────────────────────────────
echo ""
info "Checking Composer..."
if command -v composer &>/dev/null; then
    composer install --dev --no-interaction --no-progress
    ok "Composer packages installed"
else
    warn "Composer not found. Dev tools unavailable."
    warn "Install from: https://getcomposer.org/"
fi

# ── Step 6: Uploads directory ────────────────────────────────────────────────
echo ""
info "Setting up uploads directory..."
mkdir -p uploads && chmod 755 uploads
ok "uploads/ ready"

# ── Done ────────────────────────────────────────────────────────────────────
echo ""
separator
echo -e "${GREEN}${BOLD}  ✓ Setup Complete!${NC}"
separator
echo ""
echo "  Site:   http://localhost/property-rubix/"
echo "  Admin:  http://localhost/property-rubix/admin/"
echo ""
echo -e "${YELLOW}  Default login (change immediately!)${NC}"
echo "  Email:    admin@propertyrubix.com"
echo "  Password: admin123"
echo ""
