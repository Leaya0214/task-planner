# RBAC Task & Event Planner

## Overview

This project is a **Role-Based Access Control (RBAC) Task Planner & Calendar Event Management System** built with **Laravel 12 + Vue 3 + Inertia.js**.  
It allows Admin and Employee users to manage tasks and events with role-based permissions.

---

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Vue 3 + Inertia.js + TailwindCSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Version Control:** Git

---

## Features

### 1. RBAC (Role-Based Access Control)

- **Admin**
    - Create, update, delete any tasks & events.
    - Assign tasks to employees.
    - View all calendar events.
- **Employee**
    - View & update only their assigned tasks & events.
    - Cannot assign tasks/events to others.

### 2. Task Management

- CRUD functionality:
    - **Title**
    - **Description**
    - **Assigned user (Employee)**
    - **Status:** Pending / In Progress / Completed
    - **Priority:** Low / Medium / High
    - **Due date & time**
- Employees can update only **status & description**.
- Admin can update all fields.
- Validation & error handling implemented via **TaskRequest**.

### 3. Event Management & Calendar

- Event fields:
    - **Name**
    - **Description**
    - **Date**
    - **Start & End time**
    - **Related Task (optional)**
- Calendar view: Daily / Weekly / Monthly.
- Role-based visibility:
    - Admin sees all events.
    - Employee sees only assigned or permitted events.
- Drag & Drop for updating event time.
- Validation via **EventRequest**.
- Tasks linked to events optionally.

### 4. Dashboard

- Displays summary:
    - Total tasks & completed tasks.
    - Total events.
    - Role-specific info.

### 5. Authentication

- Laravel Breeze for login/register.
- Demo credentials displayed on login page.

---

## Project Flow

1. **Login**
    - Admin or Employee credentials.
2. **Dashboard**
    - Overview of tasks & events based on role.
3. **Tasks**
    - Admin: Create, assign, update, delete tasks.
    - Employee: Update assigned tasks.
4. **Events & Calendar**
    - Admin: Create, update, assign events.
    - Employee: View/update own events.
5. **Validation & Permissions**
    - Requests validated via **FormRequest** classes.
    - Actions authorized using **Gates & Policies**.

---

git clone <repo-url>
cd task-planner-app

````

2. Install dependencies:
```bash
composer install
npm install
````

3. Configure environment:

    ```bash
    cp .env.example .env
    ```

    - Update database credentials in `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=localhost
        DB_PORT=3306
        DB_DATABASE=task_planner
        DB_USERNAME=root
        DB_PASSWORD=
        ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Run migrations:

    ```bash
    php artisan migrate
    ```

6. Seed database (optional):
    ```bash
    php artisan db:seed
    ```
7. Start development server:

## Demo Credentials

### Admin User

- **Email:** [EMAIL_ADDRESS]`
- **Password:** password

### Employee User

- **Email:** [EMAIL_ADDRESS]`
- **Password:** password

---

## Project Structure

```
task-planner-app/
├── app/
│   ├── Http/Controllers/  # Controllers
│   ├── Models/              # Models
│   ├── Policies/            # Authorization Policies
│   └── Http/Requests/       # Form Requests
│   ├── Services/            # Services
├── bootstrap/
├── config/
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── public/
├── resources/
│   ├── js/
│   │   ├── Components/      # Vue components
│   │   ├── Layouts/         # Layout components
│   │   └── Pages/           # Page components
│   └── css/
├── routes/
├── tests/
├── storage/
└── vendor/
```

---

## Key Files

### Controllers

- `app/Http/Controllers/TaskController.php` - Task management
- `app/Http/Controllers/EventController.php` - Event management & calendar
- `app/Http/Controllers/DashboardController.php` - Dashboard

### Policies

- `app/Policies/TaskPolicy.php` - Task authorization
- `app/Policies/EventPolicy.php` - Event authorization

### Requests

- `app/Http/Requests/TaskRequest.php` - Task validation
- `app/Http/Requests/EventRequest.php` - Event validation

### Vue Components

- `resources/js/Pages/Task/Index.vue` - Task list
- `resources/js/Pages/Task/Show.vue` - Task details
- `resources/js/Pages/Task/Edit.vue` - Task edit
- `resources/js/Pages/Event/Calendar.vue` - Calendar view
- `resources/js/Pages/Dashboard.vue` - Dashboard

---

## Deployment Notes

1. Run migrations: `php artisan migrate`
2. Compile assets: `npm run build`
3. Ensure storage & bootstrap/cache have correct permissions
4. Optional: Separate CalendarController for cleaner code

---

## Git Commit Suggestion

```bash
git add .
git commit -m "Complete RBAC Task Planner & Calendar Event Management"
git push origin main
```
