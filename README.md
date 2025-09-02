## Clone the Project
git clone https://github.com/sbohara234/oms-api.git

## Install Dependencies
## PHP Dependencies
    `composer install`
    `npm install`
## Environment Configuration
    
    Copy .env.example to .env
## Generate Application Key

`php artisan key:generate`
## Run Database Migrations & Seeders

`php artisan migrate --seed`


##  Design Choices
# RESTful Design:
 Clean, resource-oriented endpoints following REST conventions
#   Muulti-tenancy: 
Implemented via tenant_id header and database scoping

# Validation:
 Comprehensive input validation for all endpoints

## Database Design
Multi-tenancy: All tables include tenant_id for data isolation

# Indexes
 Added for common query patterns (tenant_id + status, tenant_id + customer_id, etc.)

 # Relationships:
 Proper foreign key constraints for data integrity

# Decimal Precision:
 Appropriate precision for monetary values

# Design Patterns
Strategy Pattern: For B2B/B2C pricing logic, allowing easy extension

Service Pattern: Business logic separated from controllers

Factory Pattern: For creating appropriate pricing strategies

Observer Pattern: Laravel's notification system for event handling

# Pricing Implementation
Strategy Pattern: Allows different pricing algorithms without modifying order logic

Extensible: Easy to add new pricing strategies (volume discounts, campaign pricing, etc.)

Testable: Each strategy can be tested independently


# Notification System
Laravel Notifications: Built-in system with queue support

Multiple Channels: Support for email, database, and future channels

Extensible: Easy to add new notification types and channels

# Documentation: 
AI-assisted for OpenAPI specification structure

Review: All AI-generated code was thoroughly reviewed and modified for correctness

Testing: Manual testing of all AI-suggested implementations

# Authentication: 
Passport is used (OAuth2 Authentication with Bearer Tokens)

Performance: Eager loading used

Scalability: Current design works for medium scale; may need sharding for very large scale

Next Steps
Implement  rate limiting, reverse tax policy,other discount and shipping params


Add more notification channels (SMS, webhooks)

Implement advanced discount system

Add order export functionality

Implement inventory management integration
