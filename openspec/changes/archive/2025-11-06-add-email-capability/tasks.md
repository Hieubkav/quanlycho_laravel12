# Tasks for Add Email Capability

## Core Implementation
- [x] Install Resend Laravel package (`composer require resend/resend-laravel`)
- [x] Configure Resend in config/mail.php and .env (API_KEY_RESEND)
- [x] Create AdminPasswordReset mail class
- [x] Create SaleCredentials mail class
- [x] Update UserResource to send password reset email
- [x] Update SaleResource to send credentials email
- [x] Test email sending functionality

## Database & Migrations
- [x] Check and create sale_market pivot table migration if missing
- [x] Run migrations to ensure database schema is complete

## Configuration & Environment
- [x] Add API_KEY_RESEND to .env.example
- [x] Update README with email setup instructions
- [x] Test in staging environment

## Documentation
- [x] Update AGENTS.md if needed
- [x] Document email templates and customization
- [x] Add troubleshooting for email issues

## Quality Assurance
- [x] Write feature tests for email sending
- [x] Test email templates in different email clients
- [x] Validate email queue processing
- [x] Security audit: ensure no sensitive data in emails
