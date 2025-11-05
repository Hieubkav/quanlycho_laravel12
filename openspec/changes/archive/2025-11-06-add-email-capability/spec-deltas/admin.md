# Admin Specification Delta

## New Requirements

### Requirement: Email Notifications
The system SHALL support sending email notifications for admin and sale management.

#### Scenario: Admin Password Reset Email
- **WHEN** admin requests password reset
- **THEN** system sends email with new password to admin email address

#### Scenario: Sale Credentials Email
- **WHEN** admin generates new password for sale
- **THEN** system sends email with login credentials to sale email address

#### Scenario: Email Service Configuration
- **WHEN** system needs to send emails
- **THEN** uses configured email service (Resend) with API key
