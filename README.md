# Gas Auction Platform

> 🇬🇧 English | [🇷🇺 Русский](README.ru.md)

A web-based B2B auction platform for gas trading. Companies register, get approved by an administrator, participate in live auctions, and submit price offers. The highest bid wins.

## Features

- Public auction listing — browse open auctions with current prices and deadlines
- Company registration — register with company name, legal form, and contact details; account is activated after admin approval
- Bidding — authenticated users submit price offers; each bid must exceed the current price by at least the configured step percentage
- User dashboard — view submitted bids and their outcomes; edit profile
- Admin panel
  - Create, edit, and manage auctions (set status, start price, end time, price step)
  - Manually close any auction; winner is assigned automatically based on highest bid
  - Review all bids and download contract details for winners
  - Approve or block user accounts
- Automatic auction closing — a scheduled task closes expired open auctions every minute and assigns win/lose statuses

## Tech Stack

| Layer      | Technology                         |
|------------|------------------------------------|
| Backend    | PHP 8.2+, Laravel 11               |
| Frontend   | Blade templates, Bootstrap 5       |
| Database   | MySQL (production), SQLite (tests) |
| Build tool | Vite                               |

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8+ (or any PDO-compatible database)

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd Arbitration

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment config and configure your database
cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Generate application key
php artisan key:generate

# Run migrations and seed initial data
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

The application will be available at `http://127.0.0.1:8000/`.

Default seed credentials:

| Role  | Email             | Password |
|-------|-------------------|----------|
| Admin | admin@example.com | password |
| User  | user@example.com  | password |

## Environment Variables

For production deployments, set these environment variables instead of using defaults:

| Variable        | Description                              | Default              |
|-----------------|------------------------------------------|----------------------|
| `APP_KEY`       | Application encryption key               | _(generated)_        |
| `APP_ENV`       | Environment (`local`/`production`)       | `local`              |
| `APP_DEBUG`     | Enable debug mode (`true`/`false`)       | `true`               |
| `APP_URL`       | Application base URL                     | `http://localhost`   |
| `DB_CONNECTION` | Database driver                          | `mysql`              |
| `DB_DATABASE`   | Database name                            | —                    |
| `DB_USERNAME`   | Database username                        | —                    |
| `DB_PASSWORD`   | Database password                        | —                    |

To enable automatic auction closing in production, add a cron job:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Running Tests

```bash
php artisan test
```

Tests use an in-memory SQLite database and do not affect your development data.

## Project Structure

```
app/
├── Enums/            # AuctionStatus, BidStatus
├── Http/
│   ├── Controllers/  # Web controllers
│   └── Middleware/   # AdminMiddleware
├── Models/           # Auction, Bid, User, LegalForm
database/
├── factories/        # Model factories for testing
├── migrations/       # Database schema
└── seeders/          # Initial data (legal forms, demo accounts)
resources/views/
├── admin/            # Admin panel templates
├── auth/             # Login and registration
├── home/             # User dashboard
└── layouts/          # Base layouts
routes/
├── web.php           # HTTP routes
└── console.php       # Scheduled tasks
tests/
├── Feature/          # HTTP / controller tests
└── Unit/             # Model unit tests
```

## License

[MIT](LICENSE)
