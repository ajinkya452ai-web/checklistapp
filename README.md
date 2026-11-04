# Monthly Checklist

A private, mobile-friendly monthly checklist with recurring template tasks, one-off monthly tasks, and independent historical snapshots.

## Stack

PHP 7.4-compatible source (deploy with a supported PHP version, preferably PHP 8.3+), MySQL 8+, Bootstrap 5, HTML/CSS. No Node build step.

## Features

- New month initialized on first visit, with all tasks unchecked.
- Snapshot of enabled default tasks; template edits affect **only months not yet initialized**.
- Per-month extras, check/uncheck, progress bar, month navigation.
- Single or multiple private user accounts, each isolated by user ID.
- No public signup; accounts provisioned via CLI.

## Quick start

1. Create a MySQL database and restricted DB user. Import `database/schema.sql`.
2. Copy `.env.example` to `.env`; set DB credentials, timezone and `SESSION_SECURE=true` for HTTPS.
3. Run `php scripts/create_user.php yourusername` from a private terminal.
4. Configure your web server document root to **`public/`**.
5. Use HTTPS and open `/login.php`.

**Never put the repository root on the web.** See `docs/DEPLOYMENT.md` and `docs/SECURITY_CHECKLIST.md`.

## Notes

This starter uses a pinned Bootstrap CDN URL with a restrictive CSP allowlist. For offline use or stronger supply-chain control, host the Bootstrap CSS locally and remove the CDN CSP allowance. `public/assets/style.css` is local.

## Future improvements

Drag-and-drop template ordering, password reset via secure admin workflow, export CSV, optional monthly summary, automated tests, login rate limiting, and optional reminders.
