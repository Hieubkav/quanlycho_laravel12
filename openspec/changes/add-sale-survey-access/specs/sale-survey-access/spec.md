## ADDED Requirements
### Requirement: Sale Login Page
The system SHALL provide a dedicated login page at /khaosat for sales personnel using Volt + Flux UI.

#### Scenario: Sale Login Form Display
- **WHEN** sale accesses /khaosat
- **THEN** login form is displayed with email/phone and password fields

#### Scenario: Sale Login Success
- **WHEN** sale enters valid credentials and active status
- **THEN** sale is authenticated and redirected to dashboard

#### Scenario: Sale Login Validation
- **WHEN** required fields are missing or invalid
- **THEN** appropriate error messages are shown

### Requirement: Sale Dashboard
The system SHALL provide a simple dashboard at /khaosat after login with basic instructions.

#### Scenario: Dashboard Access
- **WHEN** authenticated sale accesses /khaosat
- **THEN** dashboard displays sale name, assigned markets, and navigation to survey creation

### Requirement: Survey Creation
The system SHALL allow sale to select survey date and create survey for assigned market.

#### Scenario: Survey Date Selection
- **WHEN** sale selects a date
- **THEN** survey is created with sale_id, market_id, survey_day

### Requirement: Price Entry Form
The system SHALL display list of 40-50 products for price entry in responsive form.

#### Scenario: Product List Display
- **WHEN** survey is created
- **THEN** products are listed with input fields for prices

#### Scenario: Price Submission
- **WHEN** sale submits prices
- **THEN** survey_items are saved with product_id, price, notes

### Requirement: Sale Authentication Security
The system SHALL enforce authentication without self-password reset for sales.

#### Scenario: No Password Reset
- **WHEN** sale forgets password
- **THEN** sale must contact admin for new credentials

#### Scenario: Active Status Check
- **WHEN** inactive sale attempts login
- **THEN** access is denied with appropriate message

### Requirement: Sale Custom Authentication
The system SHALL use separate authentication guard for sales (not admin auth) with email/phone login.

#### Scenario: Email or Phone Login
- **WHEN** sale logs in with email or phone
- **THEN** system authenticates using provided credential

### Requirement: Responsive UI for Mobile
The system SHALL provide responsive UI optimized for mobile devices in market surveys.

#### Scenario: Mobile-Friendly Form
- **WHEN** sale accesses from mobile
- **THEN** price entry form adjusts layout for touch input

### Requirement: Survey Market Assignment
The system SHALL display assigned markets for sale to choose during survey creation.

#### Scenario: Market Selection
- **WHEN** sale creates survey
- **THEN** assigned markets are listed for selection

### Requirement: Product List Management
The system SHALL display 40-50 default products for price entry with proper ordering.

#### Scenario: Default Product Display
- **WHEN** survey form loads
- **THEN** products are shown in logical order with units

### Requirement: Form Validation Rules
The system SHALL validate all required fields and enforce active sale status.

#### Scenario: Required Field Validation
- **WHEN** form is submitted with missing data
- **THEN** validation errors are displayed for each field

#### Scenario: Inactive Sale Block
- **WHEN** inactive sale tries to submit
- **THEN** submission is blocked with error message

### Requirement: Post-Login Redirect
The system SHALL redirect authenticated sales to dashboard after successful login.

#### Scenario: Successful Login Redirect
- **WHEN** sale logs in successfully
- **THEN** redirected to /khaosat dashboard
