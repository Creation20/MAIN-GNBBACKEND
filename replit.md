# Overview

The Ghana National Bibliography - Library Management System (GHLA) is a comprehensive Laravel 12 backend API designed to manage Ghana's national bibliography and library stock data. The system implements role-based access control with three distinct user roles (Super Administrator, Classifications Manager, and Entry Manager), each with specific permissions for managing library cataloguing, stock entries, and system administration.

The application provides RESTful API endpoints for authentication, user management, stock management, classification systems, and indexed articles tracking, all secured with JWT-based authentication via Laravel Sanctum.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Framework and Core Architecture

**Laravel 12 (PHP 8.2+)**: The application is built on Laravel 12, leveraging the latest framework features and PHP 8.2+ capabilities. The architecture follows the MVC pattern enhanced with Service/Repository layers for improved maintainability and separation of concerns.

**Rationale**: Laravel 12 provides modern PHP features, excellent ORM support, built-in authentication mechanisms, and a robust ecosystem. The Service/Repository pattern adds an abstraction layer between controllers and data access, making the codebase more testable and maintainable.

## Authentication and Authorization

**Laravel Sanctum for JWT Authentication**: The system uses Laravel Sanctum to issue and validate JWT tokens for API authentication. This provides stateless authentication suitable for API-driven applications.

**Role-Based Access Control (RBAC)**: Three user roles are implemented:
- **Super Administrator**: Full system control and user management capabilities
- **Classifications Manager**: Handles cataloguing and class number assignment
- **Entry Manager**: Manages stock entry creation and maintenance

**Middleware Protection**: All API routes are protected using `auth:sanctum` middleware, with additional role-based middleware for granular access control.

**Rationale**: Sanctum is Laravel's first-party authentication solution for APIs, providing a simpler alternative to OAuth while maintaining security. RBAC ensures proper separation of duties and access control based on user responsibilities.

## Database Architecture

**SQLite for Development**: The application uses SQLite as the development database, which requires no additional server setup and is included with PHP.

**Production Database Options**: The architecture supports easy migration to MySQL or PostgreSQL for production environments through Laravel's database abstraction layer.

**Eloquent ORM**: All database interactions use Laravel's Eloquent ORM, providing type-safe, expressive query building and relationship management.

**Migrations and Seeders**: Database schema is version-controlled through Laravel migrations, with seeders for initial data population.

**Rationale**: SQLite simplifies development setup while the database-agnostic architecture allows seamless transition to production-grade databases. Eloquent provides a clean API for database operations with built-in security features against SQL injection.

## API Design

**RESTful JSON API**: All endpoints follow REST principles and return standardized JSON responses with appropriate HTTP status codes.

**Standardized Response Format**: Consistent response structure across all endpoints for predictable client integration.

**Request Validation**: Laravel FormRequest classes handle input validation, ensuring data integrity before processing.

**Rationale**: RESTful design ensures predictable API behavior and ease of integration. Standardized responses simplify client-side error handling and data processing.

## Key Functional Domains

**User Management**: Complete user lifecycle management including creation, listing, and role assignment (Super Admin only).

**Stock Management**: Comprehensive library stock tracking with classification support and GNB (Ghana National Bibliography) flagging capabilities.

**Classification System**: Support for Dewey Decimal and custom class number systems for cataloguing.

**Indexed Articles Management**: Tracking of published articles with publication metadata.

**Rationale**: These domains represent the core business requirements for managing a national bibliography system, from cataloguing to stock management to publication tracking.

## Frontend Integration

**CORS Configuration**: Uses `fruitcake/php-cors` package to enable Cross-Origin Resource Sharing, allowing React frontend access to the API.

**API-Only Backend**: The application is designed as a pure API backend with no server-side rendering, optimized for consumption by single-page applications.

**Rationale**: Separating frontend and backend allows independent scaling, technology choices, and development workflows. CORS configuration ensures secure cross-origin requests from the React frontend.

## Development Tooling

**Vite Build System**: Frontend asset compilation using Vite with Tailwind CSS support, though the primary focus is on the API backend.

**Composer Scripts**: Custom scripts for setup, development server, and testing automation.

**Laravel Pail**: Log monitoring tool for development debugging.

**Laravel Pint**: Code formatting tool to maintain consistent code style.

**Rationale**: Modern tooling improves developer experience and code quality. Vite provides fast builds, while Pail and Pint streamline debugging and code maintenance.

## Testing Infrastructure

**PHPUnit**: Primary testing framework for unit and feature tests.

**Mockery**: Mocking library for test isolation.

**Rationale**: Comprehensive testing infrastructure ensures code reliability and facilitates refactoring with confidence.

# External Dependencies

## Core Framework Dependencies

- **laravel/framework (^12.0)**: Core Laravel framework providing MVC architecture, ORM, routing, and middleware
- **laravel/sanctum (^4.2)**: JWT-based API authentication system
- **laravel/tinker (^2.10.1)**: REPL for interacting with the application

## HTTP and API Dependencies

- **guzzlehttp/guzzle (^7.x)**: HTTP client for making external API requests
- **guzzlehttp/psr7**: PSR-7 HTTP message implementation
- **guzzlehttp/promises**: Promise implementation for async operations
- **fruitcake/php-cors**: CORS middleware for cross-origin requests from React frontend

## Database and Data Management

- **doctrine/inflector**: String manipulation for singular/plural forms
- **doctrine/lexer**: Lexical analysis for parsing
- **SQLite**: Default development database (included with PHP)
- **MySQL/PostgreSQL**: Production database options (optional)

## Utilities and Support Libraries

- **nesbot/carbon**: Date/time manipulation library
- **ramsey/uuid**: UUID generation for unique identifiers
- **brick/math**: Arbitrary-precision arithmetic
- **symfony/** packages: Various Symfony components used by Laravel (console, http-foundation, error-handler, etc.)
- **monolog/monolog**: Logging library for application logs
- **league/flysystem**: Filesystem abstraction layer
- **league/commonmark**: Markdown parsing
- **egulias/email-validator**: Email address validation

## Development Dependencies

- **fakerphp/faker**: Fake data generation for testing and seeding
- **phpunit/phpunit (^11.5.3)**: Testing framework
- **mockery/mockery**: Mocking framework for tests
- **laravel/pail (^1.2.2)**: Log viewing tool for development
- **laravel/pint (^1.24)**: PHP code style fixer
- **laravel/sail (^1.41)**: Docker development environment (optional)
- **nunomaduro/collision (^8.6)**: Error handler for console applications

## Frontend Build Tools

- **vite (^7.0.7)**: Frontend build tool and dev server
- **@tailwindcss/vite (^4.0.0)**: Tailwind CSS integration for Vite
- **laravel-vite-plugin (^2.0.0)**: Laravel integration for Vite
- **axios (^1.11.0)**: HTTP client for frontend-backend communication
- **concurrently (^9.0.1)**: Running multiple npm scripts simultaneously

## Third-Party Services

The application is designed to be self-contained with no mandatory external API integrations. However, the architecture supports future integration with:
- External library cataloguing systems
- ISBN/bibliographic data providers
- Cloud storage services (via Flysystem)
- Email services (via Laravel's mail configuration)