# ============================================================
# PropertyRubix — Makefile
# Standard developer commands — works on Mac/Linux.
# Windows users: use scripts/setup.bat or WSL.
# ============================================================

.PHONY: help setup install migrate migrate-status migrate-fresh lint lint-fix test clean push

# Default target
help:
	@echo ""
	@echo "  PropertyRubix — Developer Commands"
	@echo "  ==================================="
	@echo ""
	@echo "  Setup:"
	@echo "    make setup          First-time setup (env + migrate)"
	@echo "    make install        Install Composer dev dependencies"
	@echo ""
	@echo "  Database:"
	@echo "    make migrate        Run pending DB migrations"
	@echo "    make migrate-status Show migration status"
	@echo "    make migrate-fresh  Drop all + re-run migrations (CAREFUL!)"
	@echo ""
	@echo "  Code Quality:"
	@echo "    make lint           Check PHP code style (PSR-12)"
	@echo "    make lint-fix       Auto-fix code style issues"
	@echo "    make syntax         PHP syntax check all files"
	@echo "    make test           Run PHPUnit test suite"
	@echo ""
	@echo "  Utilities:"
	@echo "    make clean          Remove logs and temp files"
	@echo "    make push           Git add + commit + push (prompts for message)"
	@echo ""

# ── Setup ─────────────────────────────────────────────────────────────────────
setup:
	@echo "→ Setting up PropertyRubix development environment..."
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "✓ Created .env from .env.example"; \
		echo "  ⚠  Edit .env with your local DB credentials before continuing!"; \
	else \
		echo "✓ .env already exists"; \
	fi
	@$(MAKE) install
	@$(MAKE) migrate
	@echo ""
	@echo "✓ Setup complete! Open http://localhost/property-rubix/ in your browser."

install:
	@echo "→ Installing Composer dependencies..."
	@if command -v composer >/dev/null 2>&1; then \
		composer install --dev; \
		echo "✓ Composer packages installed"; \
	else \
		echo "⚠ Composer not found. Install from https://getcomposer.org/"; \
	fi

# ── Database ──────────────────────────────────────────────────────────────────
migrate:
	@echo "→ Running database migrations..."
	@php database/migrate.php

migrate-status:
	@php database/migrate.php --status

migrate-fresh:
	@echo "⚠ WARNING: This will destroy all data!"
	@php database/migrate.php --fresh

# ── Code Quality ──────────────────────────────────────────────────────────────
lint:
	@echo "→ Checking PHP code style..."
	@if [ -f vendor/bin/phpcs ]; then \
		vendor/bin/phpcs --standard=phpcs.xml; \
	else \
		echo "⚠ phpcs not installed. Run: make install"; \
	fi

lint-fix:
	@echo "→ Auto-fixing code style..."
	@if [ -f vendor/bin/phpcbf ]; then \
		vendor/bin/phpcbf --standard=phpcs.xml; \
	else \
		echo "⚠ phpcbf not installed. Run: make install"; \
	fi

syntax:
	@echo "→ Checking PHP syntax..."
	@find . -name "*.php" \
		-not -path "*/vendor/*" \
		-not -path "*/.git/*" \
		| xargs -I {} php -l {} | grep -v "No syntax errors"
	@echo "✓ Syntax check complete"

test:
	@echo "→ Running PHPUnit tests..."
	@if [ -f vendor/bin/phpunit ]; then \
		vendor/bin/phpunit; \
	elif [ -f phpunit.xml ] || [ -f phpunit.xml.dist ]; then \
		phpunit; \
	else \
		echo "ℹ No PHPUnit tests found yet. Add tests/ directory."; \
	fi

# ── Utilities ─────────────────────────────────────────────────────────────────
clean:
	@echo "→ Cleaning up..."
	@find . -name "*.log" -not -path "*/.git/*" -delete 2>/dev/null || true
	@find . -name "php_errorlog" -delete 2>/dev/null || true
	@echo "✓ Clean complete"

push:
	@read -p "Commit message: " msg; \
	git add -A; \
	git commit -m "$$msg"; \
	git push
