# Sar7ne

Laravel 12 application with Livewire v3 + Volt, Tailwind v4.

## Local Development (no Sail)

Run the app natively without Docker.

### Prerequisites
- PHP 8.4.12
- MySQL running locally with a database named `sar7ne`
- Redis optional (set `QUEUE_CONNECTION=sync` if not used)
- Node.js and npm for Vite/Tailwind

### First-time setup
1. Copy env and adjust as needed:
   - `cp .env.example .env`
   - Set `DB_HOST=127.0.0.1`, `DB_DATABASE=sar7ne`, `DB_USERNAME=your_user`, `DB_PASSWORD=your_password`
   - Optionally set `QUEUE_CONNECTION=sync` and update any `VITE_REVERB_*` vars for local usage
2. Install dependencies:
   - `composer install`
   - `npm install`
3. Migrate the database:
   - `php artisan migrate`
4. Run locally:
   - `npm run dev` (or `npm run build`)
   - `php artisan serve`

### Useful commands
- Tests: `php artisan test`
- Pint: `vendor/bin/pint`
- Tinker: `php artisan tinker`

## Features
Core features documented here. The previous Timeline feature has been removed.

## Formatting & Testing
- Format: `vendor/bin/pint`
 

## Notes
- Laravel Sail and Docker config have been removed. Use local services.
