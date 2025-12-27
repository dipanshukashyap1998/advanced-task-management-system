# Advanced Task Management System

A comprehensive Laravel-based task management system with RESTful APIs, real-time notifications, and background job processing.

## Features

- User authentication with Laravel Sanctum
- Task CRUD operations with filtering
- Multiple user assignment to tasks
- Real-time updates via WebSockets
- Email and database notifications for due tasks
- Background job processing for notifications
- Caching for performance optimization
- Soft deletes for data integrity

## Tech Stack

- Laravel 10+
- PHP 8.1+
- MySQL
- Laravel Sanctum
- Laravel Broadcasting (Pusher/WebSockets)
- Queues (Database/Redis)
- Tailwind CSS (for UI)

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure database
4. Run migrations: `php artisan migrate`
5. Seed database: `php artisan db:seed`
6. Generate app key: `php artisan key:generate`
7. Configure broadcasting and queue drivers in `.env`
8. Run the application: `php artisan serve`

## API Documentation

### Authentication

#### Register

```
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Login

```
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

#### Logout

```
POST /api/logout
Authorization: Bearer {token}
```

### Users API

#### Get all users

```
GET /api/users
Authorization: Bearer {token}
```

#### Get user by ID

```
GET /api/users/{id}
Authorization: Bearer {token}
```

#### Create user

```
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Update user

```
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Jane Smith"
}
```

#### Delete user

```
DELETE /api/users/{id}
Authorization: Bearer {token}
```

### Tasks API

#### Get all tasks (with filters)

```
GET /api/tasks?status=pending&priority=high&due_date=2025-12-31
Authorization: Bearer {token}
```

#### Get task by ID

```
GET /api/tasks/{id}
Authorization: Bearer {token}
```

#### Create task

```
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Complete project",
    "description": "Finish the Laravel project",
    "status": "pending",
    "priority": "high",
    "due_date": "2025-12-31"
}
```

#### Update task

```
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "in_progress"
}
```

#### Delete task

```
DELETE /api/tasks/{id}
Authorization: Bearer {token}
```

#### Assign users to task

```
POST /api/tasks/{id}/assign
Authorization: Bearer {token}
Content-Type: application/json

{
    "user_ids": [1, 2, 3]
}
```

#### Get task assignees

```
GET /api/tasks/{id}/assignees
Authorization: Bearer {token}
```

#### Remove assignee

```
DELETE /api/tasks/{id}/assignees/{user_id}
Authorization: Bearer {token}
```

## Real-time Events

The application broadcasts the following events on the `tasks` private channel:

- `task.created`
- `task.updated`
- `task.deleted`
- `task.assigned`

Subscribe to the channel using Laravel Echo or similar.

## Background Jobs

- `ProcessDueTasks`: Runs hourly to send notifications for tasks due within 24 hours
- Command: `php artisan tasks:check-due`

## Queue Configuration

Configure your queue driver in `.env`:

```
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=redis
```

Run queue worker: `php artisan queue:work`

## Broadcasting Configuration

Configure broadcasting in `.env`:

```
BROADCAST_DRIVER=pusher
# or
BROADCAST_DRIVER=laravel-websockets
```

## Caching

The application uses caching for:

- User lists (1 hour)
- Task lists (1 hour with request-based keys)

## Testing

Run tests: `php artisan test`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
