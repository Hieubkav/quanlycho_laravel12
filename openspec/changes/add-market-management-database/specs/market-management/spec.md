## ADDED Requirements

### Requirement: User Management Database
The system SHALL store admin users with role, phone, active status, and order for admin panel access.

#### Scenario: Admin User Creation
- **WHEN** admin user is created
- **THEN** user record contains name, email, password, phone, role="admin", active=true, order

### Requirement: Sale Management Database
The system SHALL store sales personnel with contact info, address, and active status for market surveys.

#### Scenario: Sale User Storage
- **WHEN** sale is registered
- **THEN** sale record contains name, phone, email, password, address, active=true, order

### Requirement: Market Database
The system SHALL store market locations with name, address, and active status.

#### Scenario: Market Registration
- **WHEN** market is added
- **THEN** market record contains name, address, notes, active=true, order

### Requirement: Product Catalog Database
The system SHALL store products with unit, default flag, and active status for surveys.

#### Scenario: Product Creation
- **WHEN** product is created
- **THEN** product record contains name, unit_id, is_default, notes, active=true, order

### Requirement: Unit Database
The system SHALL store measurement units like kg, con, quả for products.

#### Scenario: Unit Definition
- **WHEN** unit is defined
- **THEN** unit record contains name, active=true, order

### Requirement: Survey Database
The system SHALL store survey sessions with market, sale, date, and items.

#### Scenario: Survey Creation
- **WHEN** survey is conducted
- **THEN** survey record contains market_id, sale_id, survey_day, notes, active=true, order

### Requirement: Survey Item Database
The system SHALL store survey prices for each product in a survey.

#### Scenario: Price Recording
- **WHEN** price is recorded
- **THEN** survey_item record contains survey_id, product_id, price, notes, active=true, order

### Requirement: Report Database
The system SHALL store aggregated reports with date range, summary, and items.

#### Scenario: Report Generation
- **WHEN** report is generated
- **THEN** report record contains from_day, to_day, generated_at, created_by_admin_id, summary_rows, included_survey_ids, active=true, order

### Requirement: Report Item Database
The system SHALL store report details linking surveys, products, and prices.

#### Scenario: Report Item Creation
- **WHEN** report item is added
- **THEN** report_item record contains report_id, survey_id, product_id, price, notes, active=true, order

### Requirement: Settings Database
The system SHALL store global settings with brand name, logo, and favicon.

#### Scenario: Setting Storage
- **WHEN** global setting is saved
- **THEN** setting record contains key="global", brand_name, logo_url, favicon_url, active=true, order

### Requirement: Database Relationships
The system SHALL maintain proper foreign key relationships between all entities.

#### Scenario: Foreign Key Integrity
- **WHEN** related records are queried
- **THEN** joins work correctly through foreign keys

### Requirement: Default Seed Data
The system SHALL provide initial data for units, products, and settings.

#### Scenario: Fresh Installation
- **WHEN** database is seeded
- **THEN** units and products are populated with defaults
- **AND** admin user and global settings are created
