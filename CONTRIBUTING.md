# PropertyRubix — Contributing Guide

## 🌿 Branch Strategy (GitFlow)

```
main        ← production-ready code only (protected, requires PR)
staging     ← pre-production testing
develop     ← integration branch for features
feature/*   ← new features  (branch from develop)
fix/*       ← bug fixes      (branch from develop or main)
hotfix/*    ← emergency prod fixes (branch from main, merge to main + develop)
release/*   ← release preparation (branch from develop)
```

### Branch naming
```bash
feature/add-property-search
feature/JIRA-123-user-auth
fix/broken-image-upload
hotfix/security-xss-patch
release/v1.2.0
```

## ✍️ Commit Message Convention (Conventional Commits)

Format: `<type>(<scope>): <short description>`

| Type | When to use |
|------|-------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation only |
| `style` | Formatting (no logic change) |
| `refactor` | Code refactor |
| `test` | Adding tests |
| `chore` | Build, CI, deps updates |
| `security` | Security patches |
| `perf` | Performance improvement |

### Examples
```bash
feat(search): add locality-based property filter
fix(auth): resolve session not persisting after login
security(db): use prepared statements in property query
docs(readme): update local setup instructions
chore(ci): add CodeQL analysis to pipeline
```

## 🔄 Workflow

1. **Create a branch** from `develop` (or `main` for hotfixes)
2. **Make changes** with conventional commits
3. **Push branch** and open a **Pull Request** to `develop`
4. **Pass CI checks** — lint, tests, security scan must pass
5. **Code review** — minimum 1 approval required
6. **Merge** using "Squash and merge" for features, "Merge commit" for releases
7. **Tag releases** as `v1.2.3` on `main` to trigger production deploy

## 🚀 Local Setup

```bash
# 1. Clone
git clone https://github.com/abhijeetpandeywork/property-rubix.git
cd property-rubix

# 2. Environment
cp .env.example .env
# Edit .env with your local DB credentials

# 3. Database
# Start XAMPP, create DB: property_rubix
# Import: database/schema.sql
# Import: database/seed.sql (optional)

# 4. Run
# Access via: http://localhost/property-rubix/
```

## 📋 Code Standards

- **PHP**: PSR-12 coding standard
- **SQL**: Always use PDO prepared statements
- **Security**: Sanitize all user inputs with `e()` helper
- **No debug code**: Remove all `var_dump`, `print_r` before committing

## 🔒 Security

- Never commit `.env` files
- Never hardcode credentials
- Report security issues privately to: abhijeetpandeywork@github.com
