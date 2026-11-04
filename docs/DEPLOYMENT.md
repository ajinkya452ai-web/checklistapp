# Deployment guide

## Preflight

- [ ] Use supported PHP 8.3+ with PDO MySQL, mbstring and OpenSSL enabled.
- [ ] MySQL 8+ available; create a least-privilege DB account (SELECT, INSERT, UPDATE, DELETE only after schema import).
- [ ] Import `database/schema.sql` using a migration/admin account.
- [ ] Copy `.env.example` to `.env` **outside `public/`**, fill secrets, restrict permissions (e.g. chmod 600).
- [ ] Configure Nginx/Apache document root to the project's `public/` directory; deny dotfiles and disable directory listing.
- [ ] Enable TLS with a valid certificate and HTTP-to-HTTPS redirect; set `SESSION_SECURE=true`.
- [ ] Create the initial account via CLI `php scripts/create_user.php USERNAME`.
- [ ] Configure server logs, error monitoring, DB backups and restore test.
- [ ] Set secure session storage and proper permissions for the PHP worker.
- [ ] Review `docs/SECURITY_CHECKLIST.md`.

## Smoke test

- [ ] Unauthenticated `/index.php` redirects to login.
- [ ] Wrong credentials do not authenticate.
- [ ] Login, create template tasks, open current month and check an item.
- [ ] Navigate to another month: tasks start unchecked. Return: prior month state persists.
- [ ] Add an extra: appears only in that month.
- [ ] Disable/rename a template item: previously opened months remain unchanged.
- [ ] Verify `/config/bootstrap.php`, `/.env`, `/database/schema.sql` are not served.
- [ ] Verify CSRF-invalid POST is rejected.
- [ ] Test from a mobile browser.

## Rollback

Keep a tagged release and encrypted DB backup. Roll back code and schema changes together if necessary; test restoration in staging first. Never roll back by deleting the live DB.

## Note on monthly initialization

The month is created on first visit rather than by cron. The previous month's history is preserved. The server timezone controls what counts as the current month.
