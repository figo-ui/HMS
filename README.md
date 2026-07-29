# Hospital Management System

Hospital Management System (HMS) is a Laravel-based application intended to support hospital operations such as prescriptions, pharmacy reporting, user records, and payment receipt handling.

> **Repository recovery note:** The runtime shell could not clone `https://github.com/willpaa/HosptalManagementSys` directly because outbound GitHub requests are blocked by the environment proxy. The files currently in this workspace are the subset that could be recovered through browser-accessible GitHub views. See [`SOURCE_REPOSITORY.md`](SOURCE_REPOSITORY.md) for details.

## Current recovered contents

The recovered project currently includes:

- Laravel application metadata and dependency configuration in `composer.json`.
- Frontend build configuration in `package.json` and `vite.config.js`.
- Environment example values in `.env.example`.
- Web route definitions in `routes/web.php` for:
  - prescription printing,
  - user printing,
  - pharmacy report export and print views,
  - payment receipt views, downloads, streams, and bulk printing,
  - receipt API endpoints.
- Standard repository/editor configuration files.

The upstream repository appeared to contain additional Laravel directories such as `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `storage`, and `tests`; those files are not present in this recovered subset.

## Requirements

The recovered Composer and NPM manifests indicate the project expects:

- PHP `^8.2`
- Composer
- Node.js and npm
- MySQL or a compatible database server
- Laravel `^12.0`
- Filament `5.0`

## Getting started

Because this is an incomplete recovery, the commands below may require the missing upstream files before the application can run successfully.

1. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install JavaScript dependencies:

   ```bash
   npm install
   ```

4. Generate the Laravel app key:

   ```bash
   php artisan key:generate
   ```

5. Configure your database in `.env`:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hosptalms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations when the missing database files are available:

   ```bash
   php artisan migrate
   ```

7. Start the local development services:

   ```bash
   composer run dev
   ```

## Useful commands

```bash
composer validate
php artisan test
npm run dev
npm run build
```

## Project recovery plan

See [`NEXT_STEPS.md`](NEXT_STEPS.md) for the recommended restoration, validation, and development sequence before continuing feature work.

## Known limitations

- This workspace is not a complete clone of the upstream GitHub repository.
- Controller classes referenced by `routes/web.php` are not present in the recovered subset.
- Laravel framework files under `bootstrap`, `config`, `database`, `public`, `resources`, `storage`, and `tests` still need to be recovered from upstream before the application can be run end-to-end.
- Dependency installation may fail in this environment if network access remains blocked.

## Source repository

Original requested repository:

```text
https://github.com/willpaa/HosptalManagementSys
```
