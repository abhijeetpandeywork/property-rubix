# PropertyRubix.com — System Architect Report
### Full Site Review + Enterprise Implementation Plan
**Date:** July 20, 2026 | **Reviewer:** System Architect (AI Agent — Antigravity)
**Scope:** Live site review (propertyrubix.com) + local codebase analysis + enterprise upgrade plan

> **📌 IMPORTANT FOR ALL DEVELOPERS & AI AGENTS**
> This is the master reference document for PropertyRubix. Read this first before making any changes.
> - **GitHub Repo:** https://github.com/abhijeetpandeywork/property-rubix
> - **Live Site:** https://propertyrubix.com
> - **Conversation Context ID:** `3b09d949-ad6d-471c-b60e-3efbc3fa69a7`
> - **Setup Guide:** See [README.md](../README.md) and [CONTRIBUTING.md](../CONTRIBUTING.md)

---

## Executive Summary

PropertyRubix is a **multi-country real estate listing portal** targeting India, UAE, Canada, and USA markets. Users browse properties and projects by geography (Country → State → City → Locality), by developer, or by search.

**Current State:** The site runs on WordPress + Elementor (production at propertyrubix.com).
A **custom PHP MVC codebase** is being built in this repository as the enterprise replacement.

| Dimension | Score | Notes |
|-----------|-------|-------|
| Enterprise Maturity | 3/10 | MVP stage, critical bugs in production |
| UI/UX | 7/10 | Premium look, functionally limited |
| Architecture | 3/10 | WordPress monolith, no API, no cache |
| Security | 2/10 | WP admin exposed, CSRF was disabled, placeholder creds |
| SEO | 5/10 | Good URL structure, missing schema markup |
| Performance | 3/10 | No CDN, Elementor bloat, no caching |

---

## Critical Bugs Found on Live Site

> [!CAUTION]
> These are live production issues costing leads and credibility RIGHT NOW.

### 🔴 Bug 1: Content Data Leak (P0 — CRITICAL)
**Every project page shows wrong data from "Prestige Jasdan Classic"**

On pages like `/project/luxury-living-in-chembur/` (Godrej Sky Terraces):
- About description = Prestige Jasdan Classic's text
- Lead enquiry form header = "Prestige Jasdan Classic by Prestige Group"
- Developer section = Prestige Group (wrong)
- FAQ = Prestige Jasdan Classic questions

**Root cause:** WordPress Elementor template has a hardcoded post reference in a global widget instead of dynamic current-post binding.

**Fix:** In WordPress → Elementor → Global Widget → update dynamic tag source to "Current Post" instead of fixed post ID.

### 🔴 Bug 2: Placeholder Phone Numbers
On `/contact-us`, both Calling Support and WhatsApp show `+91 99999 - 99999`.
**Fix:** Update in WordPress page editor.

### 🔴 Bug 3: WordPress Admin Publicly Exposed
`/wp-admin/` and `/wp-login.php` are publicly accessible — security risk.
**Fix:** Add to `.htaccess`:
```apache
<Files wp-login.php>
  Order deny,allow
  Deny from all
  Allow from YOUR.OFFICE.IP.ADDRESS
</Files>
```

### 🟡 Bug 4: Search Non-Functional
Homepage search bar throws `TypeError: Failed to fetch` — no results appear.

### 🟡 Bug 5: Demo Data in Production
`Prestige Tranquil - Copy` visible in project listings — test/duplicate data.

---

## Technology Stack (Live Site — Confirmed)

| Layer | Technology | Confirmed By |
|-------|-----------|-------------|
| CMS | WordPress | `/wp-admin/` accessible |
| Page Builder | Elementor Pro | CSS classes `ekit_`, `elementor-*` |
| Frontend JS | jQuery 3.x | Script tags |
| CSS Framework | Bootstrap 5 | Class names |
| Hosting | Unknown (likely cPanel) | No CDN response headers |
| Search | Custom JS | Broken — JS console errors |
| CDN | None | Assets from origin |
| SSL | Active | HTTPS enforced |

## Technology Stack (PHP MVC Codebase — This Repo)

| Layer | Technology |
|-------|-----------|
| Language | PHP 8.1+ |
| Architecture | Custom MVC (Router + Controller + View) |
| Database | MySQL 8 with PDO |
| Auth | Session-based + RBAC (role/permissions table) |
| Security | CSRF tokens, prepared statements, bcrypt passwords |
| Admin | Full custom admin panel |
| Deployment | GitHub Actions CI/CD |

---

## Site Structure Map

```
propertyrubix.com/
├── /in/                                    ← Country homepage (India)
├── /location/                              ← All locations
│   └── /asia-pacific/india/               ← Country
│       └── /maharashtra/                  ← State
│           └── /mumbai/                   ← City
│               └── /chembur/              ← Locality
│                   └── [project cards]    ← Projects list
├── /project/{slug}/                        ← Project detail
│   └── Gallery, Config, Amenities, FAQ,
│       EMI Calc, Lead Form, Developer
├── /developer/                             ← All builders
│   └── /{builder-slug}/                   ← Builder profile + projects
├── /blog/                                  ← Articles
│   └── /{article-slug}/                   ← Article detail
├── /about-us                              ← Static
├── /contact-us                            ← Lead form + contact
├── /advertise-with-us                     ← Advertiser info
├── /privacy-policy                        ← Legal
├── /terms-conditions/                     ← Legal
└── /wp-admin/                             ← 🔴 SECURITY RISK — exposed
```

---

## Phased Implementation Roadmap

### 🔴 Phase 0 — Emergency Fixes (Week 1)
*Fix live production issues immediately*

- [ ] Fix Elementor data leak — correct dynamic tag binding on all project templates
- [ ] Replace placeholder phone numbers on Contact Us page
- [ ] Restrict `/wp-admin/` access to office IP via `.htaccess`
- [ ] Disable WordPress XML-RPC (`xmlrpc.php`)
- [ ] Install WP security plugin (Wordfence or Sucuri)
- [ ] Remove duplicate `Prestige Tranquil - Copy` listing
- [ ] Fix homepage search JS errors

### 🟡 Phase 1 — Foundation (Weeks 2-4)
*Stabilize PHP MVC codebase as primary platform*

**Backend:**
- [ ] REST API layer — `/api/v1/projects`, `/api/v1/properties`, `/api/v1/search`
- [ ] Full-text search — MySQL FULLTEXT indexes + proper search endpoint
- [ ] File cache — cache expensive queries (project listings, location pages)
- [ ] Image optimization — WebP conversion + thumbnail generation on upload
- [ ] Rate limiting — protect `/ajax/*` from spam
- [ ] Error handling — proper 404/500 pages; log to file in production

**Database:**
- [ ] Add FULLTEXT index on `projects(name, description, address)`
- [ ] Add `search_logs` table (track popular queries)
- [ ] Add `buyer_accounts` table (user registration)
- [ ] Add `lead_activities` table (lead timeline/CRM notes)
- [ ] Add `media` table (centralized media library)
- [ ] Add `price_history` table (track price changes)

### 🟢 Phase 2 — Feature Completeness (Weeks 5-8)

**Search & Discovery:**
- [ ] Autocomplete search with AJAX + debounce
- [ ] Advanced filters (price, BHK, type, possession, RERA)
- [ ] Google Maps integration with project pins
- [ ] Saved searches (requires buyer accounts)
- [ ] Recently viewed (cookie-based)

**Lead Management:**
- [ ] Lead deduplication (same phone+project = one lead)
- [ ] Lead assignment to team members by city
- [ ] WhatsApp notification on new lead
- [ ] Email templates for lead alerts
- [ ] Lead status workflow (New → Contacted → Site Visit → Closed)

**Content:**
- [ ] Rich text editor (TinyMCE) for descriptions
- [ ] Bulk CSV import for properties/projects
- [ ] Centralized image manager with crop/resize
- [ ] SEO fields per page (meta title, description, OG image)
- [ ] Revision history for content changes

**Analytics:**
- [ ] Google Analytics 4 integration
- [ ] Internal analytics (pageviews, popular properties, search queries)
- [ ] UTM parameter tracking in leads

### 🔵 Phase 3 — Performance & Scale (Weeks 9-12)

- [ ] Redis caching for project listings (TTL: 30 min)
- [ ] Cloudflare CDN + WAF setup
- [ ] Image CDN with WebP + responsive srcsets
- [ ] Database read replica for heavy reads
- [ ] Query optimization (EXPLAIN all slow queries)
- [ ] PHP OPcache tuning
- [ ] 2FA for admin panel (TOTP)
- [ ] Penetration testing (quarterly)
- [ ] Sentry error monitoring
- [ ] UptimeRobot health checks

### 🟣 Phase 4 — Enterprise Features (Months 4-6)

- [ ] Developer/builder portal — builders manage their own listings
- [ ] API access with API keys for third-party integrations
- [ ] Mobile app (React Native using Phase 1 API)
- [ ] Similar properties ML recommendations
- [ ] Chatbot lead qualification (WhatsApp integration)
- [ ] Paid listing tiers (Free/Standard/Featured/Premium)
- [ ] Razorpay/Stripe payment gateway
- [ ] White-label sub-domains for builders

---

## Target Enterprise Architecture

```
                    Cloudflare (CDN + WAF + DDoS)
                            │
          ┌─────────────────┼─────────────────┐
          │                 │                  │
    Frontend (PHP      Admin Panel        REST API
    MVC + Alpine.js)   (Current)          /api/v1/*
          │                 │                  │
          └─────────────────┼──────────────────┘
                            │
              PropertyRubix PHP 8.2 MVC App
                    (This Repository)
                            │
          ┌─────────────────┼──────────────────┐
          │                 │                  │
     MySQL 8           Redis Cache        AWS S3
    Primary +           Sessions +        Uploads +
    Replica            Listings           CloudFront
```

---

## SEO Opportunities

Current URL structure is excellent for SEO (`/location/country/state/city/locality/`).
What's missing:

| Element | Action |
|---------|--------|
| Schema.org markup | Add `RealEstateListing`, `Organization`, `BreadcrumbList` |
| Open Graph images | Per-property OG images for social sharing |
| Meta descriptions | Unique per page (currently generic) |
| H1 consistency | One H1 per page |
| Image alt texts | All property images need descriptive alts |
| Internal linking | Popular cities in footer for link equity |
| XML Sitemap | Verify `/sitemap.xml` is correct and submitted |
| Google Search Console | Verify and monitor coverage |
| Core Web Vitals | Critical — Elementor is killing scores |

---

## Developer Reference

### PHP MVC Quick Start
```bash
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix
scripts\setup.bat        # Windows (creates .env, runs migrations)
bash scripts/setup.sh    # Mac/Linux
```

### Key Architecture Files
```
index.php                    ← Front controller (routing starts here)
config/db.php                ← Loads .env, DB connection, helpers
app/core/Router.php          ← URL matching and dispatch
app/core/Controller.php      ← Base class for all controllers
app/helpers/auth.php         ← Login, RBAC, audit logging
app/helpers/csrf.php         ← CSRF token generation + verification
database/migrate.php         ← DB migration CLI tool
database/migrations/         ← Versioned SQL files (run in order)
.github/workflows/ci.yml     ← CI: PHP 8.1/8.2/8.3 lint+test
.github/CODEOWNERS           ← Who reviews what
```

### Adding a New Feature
1. Create migration if DB changes: `cp database/migrations/_template.sql database/migrations/00X_name.sql`
2. Add route in `index.php`
3. Create `app/controllers/YourController.php` extending `Controller`
4. Create view in `app/views/your/view.php`
5. Create PR to `develop` with conventional commit title

### Adding a Database Migration
```bash
cp database/migrations/_template.sql database/migrations/003_my_change.sql
# Write SQL in the new file
php database/migrate.php
git add database/migrations/003_my_change.sql
git commit -m "feat(db): describe the change"
```

---

*Generated by AI System Architect — Antigravity*
*All screenshots saved in knowledge base: conversation `3b09d949-ad6d-471c-b60e-3efbc3fa69a7`*
