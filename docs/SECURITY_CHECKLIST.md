# Security checklist

- [x] SQL injection prevention: PDO prepared statements, emulation disabled.
- [x] Output escaping: `htmlspecialchars` on dynamic HTML.
- [x] CSRF protection: per-session token checked on all POST mutations.
- [x] Passwords: password_hash/password_verify.
- [x] Authentication: protected main page, no public registration.
- [x] Authorization: all template/item queries scoped to logged-in user.
- [x] Session cookie: HttpOnly, SameSite=Lax, Secure when HTTPS configured.
- [x] Session ID regenerated on login.
- [x] No credentials in repository; `.env` gitignored.
- [x] Security headers: CSP, frame deny, nosniff, referrer policy.
- [x] Invalid month and task validation.
- [ ] Production login rate limiting (reverse proxy/WAF or application). **Required before public exposure.**
- [ ] TLS, backups, patch management, secret rotation and monitoring configured on host.
- [ ] Optional: self-host Bootstrap CSS instead of external CDN.
- [ ] Automated integration and security tests.

Never claim the application is fully penetration-tested. Use supported PHP and keep dependencies patched.
