# Architecture

`checklist_templates` is the editable per-user blueprint. `monthly_checklists` is a unique (user, YYYY-MM) record with `initialized_at`; `monthly_checklist_items` stores immutable task titles copied at initialization plus month-specific extras. A transaction with a parent-row lock guarantees one initialization, even for months whose template has no tasks. Status updates never modify templates.

Server-rendered PHP forms avoid exposing a write API. All mutations require POST, authenticated session and CSRF token.
