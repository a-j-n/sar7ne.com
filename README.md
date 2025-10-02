# Sar7ne

Laravel 12 application with Livewire v3 + Volt, Tailwind v4, and a Timeline feature for geo-tagged posts.

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

## Timeline Feature
- Create posts with text and images; location required (via geolocation or map picker).
- Real-time updates via Laravel Reverb; new posts prepend instantly.
- Thumbnails generated asynchronously by a queued job.

## Formatting & Testing
- Format: `vendor/bin/pint`
- Targeted tests: `php artisan test tests/Feature/Timeline/TimelineFeatureTest.php`

## Notes
- Laravel Sail and Docker config have been removed. Use local services.
