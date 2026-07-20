# PropertyRubix.com — System Architect Report v2.1
### Deep WP Admin + REST API Analysis + Enterprise Plan
**Date:** July 20, 2026 | **Reviewer:** AI System Architect (Antigravity)
**Version:** 2.1 — Updated with WP REST API enumeration + full CPT/taxonomy map
**Scope:** Full live site + WP admin access + REST API deep dive

> **📌 MASTER CONTEXT — ALL DEVELOPERS & AI AGENTS READ THIS FIRST**
> - **GitHub:** https://github.com/abhijeetpandeywork/property-rubix
> - **Live Site:** https://propertyrubix.com
> - **WP Admin:** https://propertyrubix.com/wp-admin/ (2FA via Wordfence)
> - **Conversation ID:** `3b09d949-ad6d-471c-b60e-3efbc3fa69a7`
> - **Local Dev:** `scripts\setup.bat` (Windows) or `bash scripts/setup.sh`

---

## 1. Executive Summary

PropertyRubix is a **multi-country real estate portal** (India, UAE, Canada, USA).
Two systems exist in parallel — the live WordPress site and this PHP MVC codebase (the future).

| Dimension | Score | Notes |
|-----------|-------|-------|
| Enterprise Maturity | 3/10 | MVP, critical bugs live |
| UI/UX | 7/10 | Premium design, functionally limited |
| Architecture | 3/10 | WordPress monolith, no API, no cache |
| Security | 4/10 | 2FA active ✅, XML-RPC still exposed 🔴 |
| SEO | 6/10 | Yoast + GA4 active, missing property schema |
| Performance | 3/10 | 12+ CSS/JS files, no CDN, no caching |
| Content Scale | 8/10 | 100+ projects, multiple developers, multi-city |

---

## 2. Confirmed WordPress Stack

### Core
| Layer | Detail |
|-------|--------|
| WordPress Version | **6.8.6** (confirmed from `<meta generator>`) |
| Theme | Hello Elementor **3.1.1** |
| Child Theme | hello-theme-child-master **2.0.0** |
| GA4 Tracking | `GT-WVRTJL7Q` (via Google Site Kit) |

### Active Plugins (Confirmed from page source assets)

| # | Plugin | Version | Purpose |
|---|--------|---------|---------|
| 1 | Elementor | 4.1.4 | Page builder core |
| 2 | Elementor Pro | 3.21.3 | Theme Builder, Popups |
| 3 | **Dynamic Content for Elementor (DCE)** | 3.0.7 | **DATA LEAK SOURCE** — DCE posts widget |
| 4 | Essential Addons for Elementor | 6.6.11 | Extra widgets |
| 5 | ElementsKit Lite | 3.9.7 | Extra widgets + REST API (`/wp-json/elementskit/v1/`) |
| 6 | Ultimate Elementor (UAEL) | 1.36.30 | Extra widgets |
| 7 | Yoast SEO | 27.4 | SEO meta, schema, sitemaps |
| 8 | Google Site Kit | Active | GA4 integration |
| 9 | Simple Custom CSS and JS | Active | Inline JS/CSS injection |
| 10 | Wordfence | Active | Security, 2FA |
| 11 | UiPress | Active | WP admin UI (`uip-ui-template` CPT) |
| 12 | Ads Manager | Active | `ad` custom post type registered |

---

## 3. Complete Content Data Model (from WP REST API)

The WordPress REST API is **open without authentication** — all content is publicly queryable.

### Custom Post Types (CPTs)

| CPT Slug | Name | Archive | Hierarchical | Taxonomies |
|----------|------|---------|-------------|-----------|
| `project` | Projects | ✅ `/project/` | ✅ Parent/Child | project-amenities, project-label, project-status, project-sub-type, project-type, rera-location-link |
| `developer` | Developers | ✅ `/developer/` | ✅ | none |
| `location` | Location | ✅ `/location/` | ✅ | location-category |
| `lead` | Leads | ❌ | ❌ | none (CRM data in WP) |
| `ad` | Ads | ❌ | ✅ | ads-type |
| `elementor_library` | Templates | ❌ | ❌ | none |
| `elementskit_content` | ElementsKit Items | ❌ | ✅ | none |
| `uip-ui-template` | UI Templates | ❌ | ❌ | none (UiPress admin) |

> [!CAUTION]
> The `lead` CPT is **publicly accessible via REST API** at `/wp-json/wp/v2/lead`.
> This could expose customer enquiry data without authentication. **Restrict immediately.**

### Project Taxonomies (Rich Filtering System)

| Taxonomy | Purpose |
|----------|---------|
| `project-amenities` | Swimming Pool, Gym, Clubhouse, etc. |
| `project-label` | New Launch, Under Construction, Ready to Move |
| `project-status` | Status badge (New Launch, etc.) |
| `project-sub-type` | Apartment, Villa, Plot, Commercial |
| `project-type` | Residential, Commercial, Mixed |
| `rera-location-link` | RERA registration location mapping |

### Content Volume (from REST API)

| CPT | Count (from page 1 of 100) |
|-----|---------------------------|
| `project` | **100+ published** (paginated, 100 returned in first call) |
| `developer` | Multiple (Godrej, DLF, Brigade, Kolte Patil, M3M, Sobha, Hiranandani, Oberoi, Prestige, etc.) |
| `location` | Hierarchical — Countries → States → Cities → Localities |
| `lead` | Unknown count (CRM data — do NOT expose publicly) |

### Sample Projects Inventory (from REST API)
Top 20 from API response (100+ total):
1. Prestige Tranquil – Copy *(duplicate — delete!)*
2. 24K Espada
3. Kolte Patil 24K Sereno
4. Kolte Patil Atmos / Aros / Canvas / Centria / Equa / Green Olive / Ivy Estate
5. Three Sixty (Worli)
6. Elysian / Forestville / Oberoi Garden City / Sky City (Oberoi Realty)
7. DLF Premium Riverside / Parc Estate / The Camellias / Golf Links / The Valley Gardens
8. SOBHA Conserve / Insignia / Galera / Neopolis / Crystal Meadows / Infinia
9. Brigade Citadel / Gateway / Komarla Heights / Calista / Oak Tree / Hill View
10. Godrej Prime / Central / Greens / Summit / Oasis / Aqua / Air / Icon
11. M3M Capitalwalk / The Line / Route 65 / Jewel / Paragon 57 / Crown / Altitude
12. Hiranandani Estate / Leona / Gardens / Regent Hill
13. One Hiranandani Park (Hampton, Eagleton, Willowcrest, Cloverdale)
14. ABA Cleo Gold

---

## 4. Critical Bug — Root Cause (Confirmed)

### 🔴 Bug 1: Prestige Jasdan Classic Data Leak

**Source identified:** Post 1343, "contact popup property page" — Elementor Popup Template

**Plugin responsible:** Dynamic Content for Elementor (DCE) v3.0.7

**How it works:**
- Elementor Pro popup (post 1343) is set to appear on **all Project CPT pages**
- Inside the popup, DCE widgets are used to display "project name" and "developer name"
- The DCE widget's **Source** is set to a **specific post ID** (Prestige Jasdan Classic's ID) instead of **"Current Post"** / contextual binding
- Result: Every project page popup shows Prestige Jasdan Classic's data

**The Fix:**
1. WP Admin → Elementor → Find post 1343 → Edit with Elementor
2. Click on the heading widget showing "Prestige Jasdan Classic"
3. Left panel → Content → Dynamic Tags / Source → change from "Manual/Specific Post" to **"Current Post"**
4. Repeat for developer name and any other DCE widgets
5. Click **Update**

> [!IMPORTANT]
> The DCE widget showing project data in a popup form is specifically the "Post Title" or "Post Custom Field" widget with a **hardcoded `post_id` parameter**. Changing it to `current_post` will fix every single project page instantly.

### 🔴 Bug 2: Lead CPT Exposed via REST API

The `lead` CPT is registered with `show_in_rest: true`, meaning:
```
https://propertyrubix.com/wp-json/wp/v2/lead
```
...may expose all customer enquiry data publicly.

**Fix:** Add to `functions.php` in child theme:
```php
// Remove lead CPT from REST API
add_filter('register_post_type_args', function($args, $post_type) {
    if ($post_type === 'lead') {
        $args['show_in_rest'] = false;
    }
    return $args;
}, 10, 2);
```

### 🔴 Bug 3: Contact Page Placeholder Numbers
`/contact-us` shows `+91 99999 - 99999` for both Phone and WhatsApp.

### 🟡 Bug 4: Duplicate Project Listing
`Prestige Tranquil – Copy` (post ID 7304) is published. Should be deleted or set to draft.

### 🟡 Bug 5: Homepage Search Broken
`TypeError: Failed to fetch` — search API endpoint missing or CORS blocked.

---

## 5. Security Assessment

| Risk | Status | Action |
|------|--------|--------|
| WP Admin 2FA | ✅ Active (Wordfence) | Good |
| XML-RPC | 🔴 Active | Disable via .htaccess |
| REST API Auth | 🔴 Unauthenticated | Restrict lead CPT |
| ElementsKit REST | ⚠️ Active | Audit `/wp-json/elementskit/v1/` scope |
| WP Admin URL | ⚠️ Public | Restrict to office IP |
| SSL | ✅ Active | — |

---

## 6. Performance Analysis

**Assets loaded per page (project page):**
- CSS files: 12+ separate files (Elementor, Elementor Pro, DCE, EAEL, UAEL, ElementsKit × 2, Hello Elementor × 3, Child Theme, WP core)
- JS files: jQuery 3.7.1, jQuery Migrate, Swiper, Owl Carousel, custom JS
- External: Google Tag Manager, fonts (Radikal — custom, self-hosted)
- Total estimated page weight: **3-5MB** uncompressed

**Core Web Vitals prediction:** Failing (too many render-blocking resources)

**Immediate fixes:**
1. Install WP Rocket or LiteSpeed Cache — reduces files to 2-3 via concatenation
2. Add Cloudflare (free) — CDN + basic caching
3. Lazy-load images — huge improvement on project pages with 20+ images

---

## 7. WordPress Site Map

```
propertyrubix.com/
├── /in/                              ← Country landing (India)
├── /us/                              ← Country landing (USA)
├── /location/                        ← CPT: location archive
│   ├── /asia-pacific/india/          ← State level
│   │   ├── /maharashtra/             ← City level  
│   │   │   ├── /mumbai/              ← Locality level
│   │   │   │   └── /chembur/         ← Projects in locality
│   │   ├── /karnataka/               
│   │   │   └── /bengaluru/           
├── /project/                         ← CPT: project archive (100+ items)
│   └── /{project-slug}/             ← Single project (DCE template)
├── /developer/                       ← CPT: developer archive
│   └── /{developer-slug}/           ← Single developer + projects
├── /blog/                            ← Standard WP posts
│   └── /{post-slug}/
├── /about-us                         
├── /contact-us                       ← 🔴 Placeholder phone numbers
├── /advertise-with-us                
├── /privacy-policy                   
├── /terms-conditions/                
└── /wp-admin/                        ← ⚠️ Publicly accessible (2FA protects)
```

---

## 8. Immediate Action Plan

### Today (P0)
| # | Task | How |
|---|------|-----|
| 1 | Fix DCE popup data leak | Elementor → post 1343 → change DCE source to Current Post |
| 2 | Restrict lead CPT REST access | Add `show_in_rest = false` to functions.php |
| 3 | Fix contact page numbers | WP Admin → Pages → Contact Us → Edit with Elementor |

### This Week (P1)
| # | Task | How |
|---|------|-----|
| 4 | Disable XML-RPC | `.htaccess` rule |
| 5 | Delete Prestige Tranquil – Copy | WP Admin → Projects → Trash post 7304 |
| 6 | Fix homepage search | Debug JS → likely needs AJAX endpoint or REST API search |
| 7 | Add Cloudflare | Free plan → proxy A records |
| 8 | Install WP Rocket | Page caching + JS/CSS concatenation |

### This Month (P2)
| # | Task |
|---|------|
| 9 | Add `RealEstateListing` schema.org markup per project |
| 10 | Fix meta descriptions (unique per project page) |
| 11 | Build REST API in PHP MVC codebase |
| 12 | Implement working search in PHP MVC |

---

## 9. Enterprise Architecture Roadmap

### Phase 0 — WordPress Emergency Fixes (Week 1)
- [ ] Fix Elementor DCE popup data leak (post 1343)
- [ ] Hide lead CPT from public REST API
- [ ] Fix contact page phone numbers
- [ ] Delete duplicate project listing
- [ ] Disable xmlrpc.php
- [ ] Add Cloudflare CDN (free)
- [ ] Install caching plugin

### Phase 1 — PHP MVC Foundation (Weeks 2-4)
- [ ] REST API layer (`/api/v1/projects`, `/api/v1/search`, `/api/v1/leads`)
- [ ] MySQL FULLTEXT search
- [ ] Image optimization (WebP, thumbnails)
- [ ] File-based caching
- [ ] Error handling + logging

### Phase 2 — Feature Completeness (Weeks 5-8)
- [ ] Advanced search filters (price, BHK, type, status, RERA)
- [ ] Google Maps integration
- [ ] Lead CRM (dedup, assignment, WhatsApp notification, workflow)
- [ ] Analytics (GA4 events, lead source UTM tracking)
- [ ] TinyMCE rich editor
- [ ] Bulk CSV import

### Phase 3 — Performance & Scale (Weeks 9-12)
- [ ] Redis caching
- [ ] AWS S3 + CloudFront for media
- [ ] DB read replica
- [ ] 2FA for custom PHP admin
- [ ] Sentry error monitoring
- [ ] WAF via Cloudflare

### Phase 4 — Enterprise Features (Months 4-6)
- [ ] Builder self-service portal
- [ ] Paid listing tiers + Razorpay
- [ ] Mobile app (React Native via Phase 1 API)
- [ ] AI property recommendations
- [ ] WhatsApp chatbot for leads

---

## 10. Target Enterprise Architecture

```
                      Cloudflare (CDN + WAF + DDoS)
                               │
             ┌─────────────────┼──────────────────┐
             │                 │                  │
       Frontend PHP       Admin Panel         REST API
       (MVC + Alpine)     (This repo)         /api/v1/*
             │                 │                  │
             └─────────────────┼──────────────────┘
                               │
                  PropertyRubix PHP 8.2 MVC App
                    (This repo — replacing WP)
                               │
          ┌─────────────────────┼────────────────────┐
          │                    │                    │
     MySQL 8              Redis 7              AWS S3
   Primary +             Cache +            Uploads +
   Replica            Sessions            CloudFront
```

---

## 11. Developer Quick Reference

### PHP MVC Setup (This Repo)
```bash
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix
scripts\setup.bat        # Windows
bash scripts/setup.sh    # Mac/Linux
# Runs: creates .env → verifies DB → runs migrations → installs dev tools
```

### Key Files
| File | Purpose |
|------|---------|
| `index.php` | Front controller |
| `config/db.php` | DB + .env loader |
| `app/core/Router.php` | URL routing |
| `app/helpers/auth.php` | Session + RBAC |
| `app/helpers/csrf.php` | CSRF (enabled) |
| `database/migrate.php` | Migration CLI |
| `database/migrations/` | Versioned SQL |
| `.github/workflows/ci.yml` | CI: PHP 8.1+8.2+8.3 |
| `.agents/AGENTS.md` | AI agent context |
| `docs/ARCHITECT_REPORT.md` | This document |

### Branch + Commit Rules
```bash
# Start feature
git checkout develop && git pull
git checkout -b feature/your-feature-name

# Commit format
feat(search): add autocomplete endpoint
fix(auth): resolve session expiry
security(db): add missing prepared statement

# PR target: develop (not main)
```

### Database Changes
```bash
cp database/migrations/_template.sql database/migrations/003_name.sql
php database/migrate.php    # test locally
git add database/migrations/003_name.sql
git commit -m "feat(db): describe the change"
```

---

## 12. Context & Conversation Log

| When | What | Where Stored |
|------|------|-------------|
| July 20, 2026 | Full site review (55+ screenshots) | Conversation `3b09d949`, artifact directory |
| July 20, 2026 | WP Admin access + REST API audit | This document |
| July 20, 2026 | VCS + CI/CD pipeline setup | Git commits on `main`/`develop` |
| July 20, 2026 | DB migration system created | `database/migrate.php`, `migrations/` |
| July 20, 2026 | Critical security fixes (CSRF, upload, creds) | Git commit `7ccf739` |

**All AI agent context:** [.agents/AGENTS.md](../.agents/AGENTS.md)
**All DB decisions:** [docs/DATABASE.md](DATABASE.md)

---

*Report v2.1 — July 20, 2026*
*Antigravity AI System Architect*
*Screenshots: 55+ saved in knowledge base*
*REST API data: Verified from `/wp-json/wp/v2/` endpoints*
