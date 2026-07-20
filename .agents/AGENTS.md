# PropertyRubix — AI Agent & Developer Context

## 🤖 For AI Agents Reading This

This file is the **single source of truth** for any AI agent or developer working on PropertyRubix.
Read this FIRST before making any code changes.

---

## Project Overview

**PropertyRubix** is a multi-country real estate portal (India, UAE, Canada, USA).
- **Live site:** https://propertyrubix.com (WordPress + Elementor)
- **New system:** This PHP MVC codebase (replacement for WordPress)
- **GitHub:** https://github.com/abhijeetpandeywork/property-rubix

---

## Current Architecture (Two Systems)

### System 1 — Live WordPress Site
- URL: propertyrubix.com
- Stack: WordPress + Elementor Pro + MySQL
- Status: Live in production, has critical bugs
- Admin: /wp-admin/ (restricted — not managed in this repo)

### System 2 — PHP MVC App (This Repo)
- Stack: PHP 8.1+, Custom MVC, MySQL 8, PDO
- Status: Under active development — will replace WordPress
- Entry: `index.php` (front controller)
- Admin: `admin/` directory

---

## Critical Bugs on Live Site (as of July 2026)

1. **Data Leak** — All project pages show "Prestige Jasdan Classic" data (Elementor template bug)
2. **Broken Search** — JS errors prevent search from working
3. **Placeholder Numbers** — Contact page shows +91 99999 99999
4. **WP Admin Exposed** — /wp-admin/ accessible publicly

---

## Local Development Setup

```bash
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix

# Windows:
scripts\setup.bat

# Mac/Linux:
bash scripts/setup.sh
```

This will:
1. Create `.env` from `.env.example`
2. Verify database connection
3. Run all migrations
4. Install Composer dev tools

Then access: http://localhost/property-rubix/

---

## Key Decisions Already Made

| Decision | Detail | When |
|----------|--------|------|
| Use `.env` for config | `config/db.php` loads `.env` automatically | July 2026 |
| CSRF protection enabled | `csrfVerify()` uses `hash_equals()` | July 2026 |
| PHP MVC is primary | Won't extend WordPress — replace it | July 2026 |
| GitFlow branching | main → develop → feature/* | July 2026 |
| DB migrations | Numbered SQL files in `database/migrations/` | July 2026 |
| PHP 8.1/8.2/8.3 support | CI tests all three | July 2026 |
| Branch protection | main + develop require PRs + CI | July 2026 |

---

## Files AI Agents Must Know

| File | Purpose |
|------|---------|
| `docs/ARCHITECT_REPORT.md` | Full system analysis + enterprise plan |
| `docs/DATABASE.md` | DB migration guide |
| `README.md` | Developer onboarding |
| `CONTRIBUTING.md` | Branch strategy + commit conventions |
| `config/db.php` | DB connection + .env loader |
| `database/migrations/` | All DB schema changes |
| `.github/workflows/ci.yml` | CI pipeline |
| `.github/CODEOWNERS` | Who owns what |

---

## Conversation History

All conversations between the lead developer and the AI agent are stored as:
- GitHub commits with detailed commit messages
- `docs/ARCHITECT_REPORT.md` — architectural decisions
- `docs/DATABASE.md` — database decisions

**Conversation ID (July 2026 session):** `3b09d949-ad6d-471c-b60e-3efbc3fa69a7`

---

## Enterprise Roadmap Summary

| Phase | Timeline | Focus |
|-------|----------|-------|
| Phase 0 | Week 1 | Emergency live site fixes |
| Phase 1 | Weeks 2-4 | REST API, search, caching, image optimization |
| Phase 2 | Weeks 5-8 | Lead CRM, advanced search, analytics |
| Phase 3 | Weeks 9-12 | Redis, CDN, performance, security hardening |
| Phase 4 | Months 4-6 | Builder portal, mobile app, marketplace |

Full details: [ARCHITECT_REPORT.md](../docs/ARCHITECT_REPORT.md)

---

## Code Conventions

**PHP:** PSR-12 standard. Run `make lint` before committing.
**Commits:** Conventional commits — `feat(scope): description`
**PRs:** Target `develop` branch, fill out PR template, CI must pass.
**Database changes:** Always via migration files, never manual edits.

---

## Contact / Ownership

- **Lead Developer:** @abhijeetpandeywork (GitHub)
- **Repository:** https://github.com/abhijeetpandeywork/property-rubix
