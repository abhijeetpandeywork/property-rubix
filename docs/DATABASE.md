# Database Guide — PropertyRubix

## Migration System

PropertyRubix uses a **numbered SQL migration system** to track database changes across all developers.

### How it works

1. Every database change is written as a `.sql` file in `database/migrations/`
2. Files are named with a numeric prefix: `001_`, `002_`, etc.
3. The migration runner tracks which files have been applied in a `migrations` table
4. When you run `php database/migrate.php`, only **new** files are applied

---

## Commands

```bash
# Run pending migrations (most common)
php database/migrate.php

# See what's applied and what's pending
php database/migrate.php --status

# Reset all tables and re-run everything (⚠ DESTROYS DATA)
php database/migrate.php --fresh

# Drop all tables (⚠ DESTROYS DATA)
php database/migrate.php --reset
```

---

## Adding a Database Change

> [!IMPORTANT]
> Every DB change (new table, new column, index, etc.) MUST go through a migration file.
> Never edit the DB manually on your local and not commit it.

### Step-by-step

```bash
# 1. Find the next migration number
ls database/migrations/

# 2. Copy the template
cp database/migrations/_template.sql database/migrations/003_add_virtual_tour.sql

# 3. Write your SQL
# Example:
# ALTER TABLE `properties`
#   ADD COLUMN `virtual_tour_url` VARCHAR(255) DEFAULT NULL AFTER `video_url`;

# 4. Run it locally to test
php database/migrate.php

# 5. Commit BOTH the migration file AND your code changes together
git add database/migrations/003_add_virtual_tour.sql app/controllers/PropertyController.php
git commit -m "feat(property): add virtual tour URL field"
```

---

## Migration Rules

| Rule | Why |
|------|-----|
| Never edit an already-committed migration | Other devs may have already applied it |
| Always use `IF NOT EXISTS` in `CREATE TABLE` | Safer for fresh installs |
| Use `IGNORE` in `INSERT` for seed data | Prevents errors on re-run |
| One logical change per migration file | Easier to debug and revert |
| Commit migration with the code that uses it | Prevents broken states |

---

## Table Reference

| Table | Purpose |
|-------|---------|
| `properties` | Individual property listings |
| `projects` | Development projects (contain multiple properties) |
| `builders` | Property developers/builders |
| `cities` | City directory |
| `states` | State/Province directory |
| `countries` | Country directory |
| `localities` | Neighborhood/locality within cities |
| `leads` | Enquiry leads from users |
| `submissions` | Contact form submissions |
| `users` | Admin panel users |
| `permissions` | Role-based access control |
| `audit_log` | Admin action audit trail |
| `blog_posts` | Blog articles |
| `blog_categories` | Blog categories |
| `pages` | CMS static pages |
| `settings` | Site-wide key-value settings |
| `branding_settings` | Logo, colors, tagline |
| `testimonials` | Customer testimonials |
| `reviews` | Property/project reviews |
| `newsletters` | Newsletter campaigns |
| `subscribers` | Email subscribers |
| `faqs` | Frequently asked questions |
| `categories` | Shared category table (projects, blog) |
| `project_images` | Gallery images for projects |
| `project_floor_plans` | Floor plan configurations |
| `project_amenities` | Amenity list per project |
| `migrations` | Migration tracking (auto-managed) |
