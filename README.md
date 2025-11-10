# Ghana National Bibliography - Library Management System (GHLA)

A comprehensive Laravel 12 backend API for managing Ghana's national bibliography and library stock data with role-based access control.

## Features

- **JWT Authentication** using Laravel Sanctum
- **Role-Based Access Control** (RBAC) with three user roles:
  - Super Administrator: Full system control and user management
  - Classifications Manager: Cataloguing and class number assignment
  - Entry Manager: Stock entry creation and maintenance
- **Stock Management** with classification support and GNB flagging
- **Classification System** for Dewey/custom class numbers
- **Indexed Articles Management** with publication tracking
- **User Management** endpoints for administrators
- **Standardized JSON Responses** with proper HTTP status codes
- **SQLite Database** for development (easily switchable to MySQL/PostgreSQL)

## Technology Stack

- **Framework**: Laravel 12
- **PHP Version**: 8.2+
- **Authentication**: Laravel Sanctum (JWT tokens)
- **Database**: SQLite (dev) / MySQL/PostgreSQL (production)
- **Architecture**: MVC + Service/Repository pattern

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- SQLite (included) or MySQL/PostgreSQL for production

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ghla-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Run the development server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

The API will be available at `http://localhost:8000`

## Default Test Users

After running `php artisan db:seed`, the following test users are created:

| Email | Password | Role |
|-------|----------|------|
| admin@ghla.com | password123 | super_admin |
| manager@ghla.com | password123 | classifications_manager |
| entry@ghla.com | password123 | entry_manager |

## API Endpoints

### Authentication

```
POST   /api/auth/signin    - User login (returns JWT token)
POST   /api/auth/signout   - User logout
```

### User Management (Super Admin only)

```
GET    /api/users          - List all users
POST   /api/users          - Create new user
GET    /api/users/{id}     - Get user details
PUT    /api/users/{id}     - Update user
DELETE /api/users/{id}     - Delete user
```

### Stock Management (Entry Manager & Super Admin)

```
GET    /api/stock          - List all stocks
POST   /api/stock          - Create new stock
GET    /api/stock/{id}     - Get stock details
PUT    /api/stock/{id}     - Update stock
DELETE /api/stock/{id}     - Delete stock
```

### Classification Management (Classifications Manager & Super Admin)

```
GET    /api/classifications       - List all classifications
POST   /api/classifications       - Create new classification
GET    /api/classifications/{id}  - Get classification details
PUT    /api/classifications/{id}  - Update classification
DELETE /api/classifications/{id}  - Delete classification
```

### Indexed Articles Management (Entry Manager & Super Admin)

```
GET    /api/articles       - List all articles
POST   /api/articles       - Create new article
GET    /api/articles/{id}  - Get article details
PUT    /api/articles/{id}  - Update article
DELETE /api/articles/{id}  - Delete article
```

## API Response Format

All API responses follow this standardized JSON structure:

### Success Response
```json
{
  "status": "success",
  "message": "Operation completed successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description",
  "data": null
}
```

## Authentication

All protected routes require a Bearer token in the Authorization header:

```
Authorization: Bearer {your-token-here}
```

To obtain a token, send a POST request to `/api/auth/signin`:

```json
{
  "email": "admin@ghla.com",
  "password": "password123"
}
```

The response will include the JWT token:

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "1|..."
  }
}
```

## Database Schema

### Users Table
- id, name, email, password, role, timestamps

### Stocks Table
- id, title, author, isbn, classification_id (FK), is_gnb_stock (boolean), timestamps

### Books Table
- id, title, author, publication_date, isbn, stock_id (FK), timestamps

### Indexed Articles Table
- id, title, author, publication, is_gnb_stock (boolean), timestamps

### Classifications Table
- id, class_number (unique), description, timestamps

### Reports Table
- id, type, filters (JSON), generated_by (user_id FK), file_path, timestamps

## Role Permissions

| Route | Super Admin | Classifications Manager | Entry Manager |
|-------|-------------|------------------------|---------------|
| User Management | ✓ | ✗ | ✗ |
| Stock Management | ✓ | ✗ | ✓ |
| Articles Management | ✓ | ✗ | ✓ |
| Classifications Management | ✓ | ✓ | ✗ |

## Development

### Run Migrations
```bash
php artisan migrate
```

### Rollback Migrations
```bash
php artisan migrate:rollback
```

### Refresh Database (drop all tables and re-migrate)
```bash
php artisan migrate:fresh --seed
```

### Create New Migration
```bash
php artisan make:migration create_table_name
```

### Create New Controller
```bash
php artisan make:controller Api/ControllerName --api
```

### Create New Model
```bash
php artisan make:model ModelName
```

## Production Deployment

For production deployment:

1. Update `.env` file:
   ```
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=mysql  # or postgresql
   DB_HOST=your-db-host
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-username
   DB_PASSWORD=your-db-password
   FILESYSTEM_DISK=public
   ```

2. Run optimization commands:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan optimize
   ```

3. Set up proper file permissions:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

## Security Features

- Password hashing using bcrypt
- CSRF protection
- SQL injection protection via Eloquent ORM
- XSS protection through input validation
- Rate limiting on authentication routes
- Role-based middleware protection

## File Storage

The application uses Laravel's Storage facade for managing:
- Book covers
- Generated reports (PDF)
- GNB publication files

Files are stored in `storage/app/public` and can be accessed via symbolic link.

## API Testing

You can test the API using:
- Postman
- Insomnia
- cURL
- HTTPie

Example cURL request:
```bash
curl -X POST http://localhost:8000/api/auth/signin \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@ghla.com","password":"password123"}'
```

## Support & Documentation

For more information about Laravel features, visit:
- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)

## License

This project is proprietary software for the Ghana National Bibliography Library Management System.
