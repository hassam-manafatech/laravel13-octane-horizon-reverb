# Laravel 13 + React (Inertia)

Local testing application. Served by Octane (FrankenPHP), queues run through Horizon (Redis), real-time broadcasting via Reverb.

## Requirements

- PHP 8.4
- Redis running locally (`redis-cli ping` → `PONG`)
- Node/npm

## First-time setup

```bash
composer setup
```

## Running the app

```bash
php artisan dev # new command

composer dev
```

This starts, in one terminal (via `php artisan dev`):

- **octane** — `php artisan octane:start` (FrankenPHP), serves the app at `http://localhost:8000`
- **horizon** — `php artisan horizon`, processes queued jobs via Redis
- **reverb** — `php artisan reverb:start`, WebSocket server at `http://localhost:8080` for broadcasting
- **logs** — `php artisan pail`
- **vite** — frontend dev server

Horizon dashboard: `http://localhost:8000/horizon`

### Running processes individually

```bash
php artisan octane:start   # app server (FrankenPHP)
php artisan horizon        # queue worker + dashboard
php artisan reverb:start   # WebSocket server
```

Reload Octane after backend code changes (it boots the app once and keeps it in memory):

```bash
php artisan octane:reload
```

## Notes

- Queue connection and cache store are both set to `redis` in `.env`.
- Horizon's `local` environment config in `config/horizon.php` caps workers at 3 processes.
- Broadcast connection is `reverb` in `.env`. Broadcast events queue on the `default` connection/queue, so Horizon's existing supervisor processes them — no extra queue config needed.
