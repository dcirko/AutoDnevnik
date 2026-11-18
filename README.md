# AutoDnevnik

AutoDnevnik is a vehicle ownership web app developed as an individual project for an advanced web services design course. It is designed to track petrol, diesel, and electric vehicles, service history, fuel and charging, mileage, expenses, and reminders. The planned scope also includes automotive articles, an editorial CMS, user administration, and an optional VIN lookup with manual entry as a fallback.

## Project status

The initial MySQL schema, ER diagram, and synthetic demo data are available. The Laravel backend includes a database health endpoint and a migration that adopts the original schema. The Angular frontend is a starter application. Authentication, public articles, the CMS, and owner workflows are planned. The seminar is a separate deliverable. Course approval of the framework is listed as pending in the project notes.

## Requirements

- Docker Desktop with Linux containers and Docker Compose for the backend and database
- Node.js 24 LTS (24.15.0 or later) and npm for the frontend
- The containers provide PHP 8.4, Laravel 13, Apache 2.4, Composer 2, and MySQL 8.4
- The frontend uses Angular 22 and TypeScript. No global Angular CLI installation is needed.

## Backend setup on Windows

Run the Compose commands from the repository root. Copy the sample environment only if you have no existing local configuration:

```powershell
if (-not (Test-Path -LiteralPath .env)) { Copy-Item .env.example .env }
```

Set distinct local values for DB_PASSWORD and MYSQL_ROOT_PASSWORD in the root .env. Start Docker Desktop, then run:

```powershell
docker compose config --quiet
docker compose up -d --build
docker compose ps
docker compose exec web composer install
if (-not (Test-Path -LiteralPath backend/.env)) {
    Copy-Item backend/.env.example backend/.env
    docker compose exec web php artisan key:generate --no-interaction
}
docker compose exec web composer db:install
```

If backend/.env already exists with an empty APP_KEY, run the same key generation command once. Keep both environment files private. The database installer runs the Laravel migrations. It installs the initial SQL schema on an empty database or adopts a matching existing schema after checking its version, checksum, and tables. It rejects incomplete or unexpected schemas. The initial schema has no destructive rollback. Preserve existing data by avoiding migrate:fresh and migrate:refresh on a populated development database.

Optionally run `docker compose exec web composer db:seed` for synthetic demo data. The seeder runs only in the local environment, skips an existing user dataset, and writes generated accounts to the ignored file `backend/storage/demo-accounts.json`. The login flow is planned for a later phase.

Open http://localhost:8080 for the API description and http://localhost:8080/api/v1/health for the database check. A healthy database returns `{"data":{"status":"ok"}}`. An unavailable or uninitialized database returns HTTP 503. Apache serves `backend/public/`. For a local MySQL client such as DBeaver, use host 127.0.0.1, port 3307, and the database name and application credentials from the root .env. Containers use db:3306.

## Frontend setup

```powershell
cd frontend
npm ci
npm start
```

Open http://localhost:4200. Run `npm run build` in the frontend directory to write a production build to `frontend/dist/autodnevnik/browser/`. The development API proxy, Apache hosting for the production build, and same-origin Laravel sessions with CSRF protection are planned for integration.

## Data model

A user can own multiple vehicles. Each vehicle can have service entries, energy entries, additional expenses, and reminders. Articles have an author, a category, and up to one image. The database records fuel in liters, electricity in kWh, and costs in euros. Service and energy costs are recorded without duplication in additional expenses. Electric vehicle efficiency estimates are outside the initial scope. The proposed external VIN source is [NHTSA vPIC](https://vpic.nhtsa.dot.gov/api/), whose coverage is limited outside the US market.

See the [SQL schema](backend/database/schema.sql), [migrations](backend/database/migrations/), and [ER diagram](docs/er-diagram.svg).

## Development checks

Run the backend commands from the repository root after installing the schema:

```powershell
docker compose exec web composer lint
docker compose exec web composer format:check
docker compose exec web composer test
```

From the frontend directory:

```powershell
npm run format:check
npm run build
```

Backend formatting follows PSR-12 through PHP CS Fixer. Frontend formatting uses Prettier. The backend tests cover JSON response contracts, database behavior, and HTTP checks. Temporary database test records are rolled back. GitHub Actions runs the checks on pushes and pull requests with an isolated MySQL instance. The frontend build currently provides compilation coverage.

Use `docker compose stop` to stop the services while preserving the database volume. `docker compose down` also removes containers and the network. Keep the volume if you need the data. MySQL initialization passwords apply when the data volume is first created.

## Project documentation

- [Project and topic proposal](docs/PROJECT.md)
- [Requirements and submission](docs/REQUIREMENTS.md)
- [Architecture and data model](docs/ARCHITECTURE.md)
- [Development workflow](docs/workflow.md)
- [Phases and current tasks](docs/TASKS.md)
- [Decisions](docs/DECISIONS.md)
- [Original Croatian README with detailed setup notes](https://github.com/dcirko/AutoDnevnik/blob/5b2d780ca4ff470e9aae2bfd5f163c379c031204/README.md)

OpenAI Codex assisted with requirements analysis, planning, documentation, environment setup, initial implementation and tests, and the move to Laravel. The author reviews the work and is responsible for understanding and verifying the submitted project.
