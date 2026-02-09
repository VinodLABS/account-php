# Mini Website Builder (PHP + MySQL)

A small CMS-like builder with:
- Admin authentication (session based)
- Page CRUD with soft delete
- TinyMCE section editor
- Dynamic frontend rendering from DB

## Setup
1. Create DB/tables:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Seed default admin:
   ```bash
   php seed_admin.php
   ```
3. Run server:
   ```bash
   php -S 0.0.0.0:8000
   ```
4. Open:
   - Login: `http://localhost:8000/login.php`
   - Admin pages: `http://localhost:8000/admin.php`
   - Frontend page by slug: `http://localhost:8000/index.php?slug=home`

Default admin credentials after seeding:
- Email: `admin@example.com`
- Password: `admin123`

## Features implemented
- `admins` table with hashed passwords via `password_hash` / `password_verify`
- Admin-only pages using session checks
- Create/edit pages with title, slug, meta title/description, draft/published status
- Soft delete pages with `deleted_at`
- Add/edit/delete multiple page sections with TinyMCE
- Frontend renders only published + non-deleted pages and section content
