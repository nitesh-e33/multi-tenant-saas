# Laravel Multi-Company (Multi-Tenant) Minimal Backend

This project implements a minimal Laravel backend where a user can create, manage, and switch between multiple companies. All subsequent actions are scoped to the current active company.

## Features
- User registration/login/logout (Laravel Sanctum tokens for API).
- Create/List/Update/Delete companies (name, address, industry).
- Switch active company (stored on `users.active_company_id`).
- Middleware `EnsureActiveCompany` sets the current company on each request.
- Data isolation: users can only manage their own companies.

## Quick setup
1. Clone repo.
2. `composer install`
3. Copy `.env.example` to `.env` and set DB credentials (MySQL).
4. `php artisan key:generate`
5. `php artisan migrate`
6. Install Breeze (for web auth):
   - `composer require laravel/breeze --dev`
   - `php artisan breeze:install` (choose Blade)
   - `npm install && npm run dev` (if you want front-end assets)
7. If using API tokens: `composer require laravel/sanctum` and publish migrations per Sanctum docs, then `php artisan migrate`.
8. Start server: `php artisan serve` and `npm run dev`

## API Endpoints
- `POST /api/register` — Register (name, email, password, password_confirmation)
- `POST /api/login` — Login (email, password) => returns `token`
- `POST /api/logout` — Logout (requires `Authorization: Bearer <token>`)
- `GET /api/companies` — List companies (auth)
- `POST /api/companies` — Create company (auth)
- `GET /api/companies/{id}` — Get company detail (auth)
- `PUT /api/companies/{id}` — Update company (auth)
- `DELETE /api/companies/{id}` — Delete company (auth)
- `POST /api/companies/switch` — Switch active (company_id) (auth)

## Multi-Tenant Logic
- Active company stored on `users.active_company_id`.
- Middleware `EnsureActiveCompany` loads and verifies access.
- Future models (invoices/projects) should have `company_id` and be filtered by `Auth::user()->active_company_id`.

## Notes
- Soft deletes enabled for `companies`.
- All controller methods validate ownership before any write/delete.
- Proper validation is done using FormRequest classes.

