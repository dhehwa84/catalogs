# CatalogueHub Zimbabwe

Centralized Laravel catalogue platform for Zimbabwean shops, branches, promotions, operating hours, and public catalogue browsing.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- SQLite for the default local setup, or MySQL/MariaDB if you update `.env`

## One-Command Fresh Setup

From this `cataloguehub-zimbabwe` directory, run:

```bash
composer setup:fresh
```

That command will:

- Install PHP dependencies from `composer.lock`
- Copy `.env.example` to `.env` if needed
- Create `database/database.sqlite` for the default SQLite setup
- Generate the app key
- Create the storage symlink
- Run fresh migrations and seeders
- Install npm dependencies
- Build frontend assets

If the new machine needs dependency resolution instead of the lock file, run:

```bash
composer setup:fresh-update
```

Use this only when `composer install` cannot work for that environment, because it updates `composer.lock`.

## Start The App

```bash
composer serve
```

This starts Laravel with larger upload limits for catalogue PDFs and images:

- `upload_max_filesize=64M`
- `post_max_size=72M`
- `memory_limit=256M`

Then open:

```text
http://127.0.0.1:8000
```

## Demo Accounts

```text
Admin: admin@cataloguehub.co.zw / password
Shop owner: owner@cataloguehub.co.zw / password
```

## Useful Commands

```bash
php artisan test
npm run build
./vendor/bin/pint
```

## Database Notes

The default `.env.example` uses SQLite. For MySQL or MariaDB, update `.env` before running the setup command:

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cataloguehub
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
composer setup:fresh
```

`setup:fresh` runs `migrate:fresh --seed`, so it is intended for a new or disposable local database.
