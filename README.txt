FeedbackFlow v3

New features:
- Email alert support (uses PHP mail())
- Analytics page
- Domain verification for widget submissions

Setup:
1. Create/import database using sql/schema.sql
2. Update includes/config.php
3. Set BASE_URL to your domain or VPS URL
4. Set ENABLE_EMAIL_ALERTS to true after your server mail() works
5. Open /register.php and create an account
6. Create a project with allowed domain and optional alert email
7. Install the widget snippet on that domain

Important:
- Widget submission now checks HTTP_ORIGIN / HTTP_REFERER host against the allowed domain.
- Manual tests without origin/referer are allowed.
