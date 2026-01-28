# Laravel RBAC Task Planner & Calendar Event Management

This is a **Role-Based Access Control (RBAC) Task Planner & Calendar Event Management system** built with **Laravel 12** and **Vue 3 (Inertia.js)**. It allows **Admin** and **Employee** users to manage tasks and events with role-based permissions.

---

## Features

### User Roles

- **Admin**
    - Full access to tasks & events
    - Assign tasks to employees
    - Create, update, delete any event
- **Employee**
    - Can view assigned tasks and events
    - Can update limited fields of tasks/events (status, description)
    - Cannot assign tasks

---

### Task Module

- **Fields**: title, description, assigned user, status, priority, due date
- **Validation**
    - Admin: all fields required/validated
    - Employee: only status and description editable
- **Controller**: CRUD operations via `TaskController`
- **Vue Views**:
    - Task List (`Index`)
    - Task Details (`Show`)
    - Task Edit (`Edit`)
- Dynamic badges for status & priority
- Role-based action buttons (Edit, Delete, View)

---

### Event Module

- **Fields**: name, date, start_time, end_time, description, related task (optional), created_by
- **Validation**: via `EventRequest` and manual controller validation
- **Controller**: `EventController`
    - Quick create (`quickStore`)
    - Update (`update`, `updateEventDate` for calendar)
- Employee can update only permitted fields
- Calendar integration with **FullCalendar**
    - Daily/Weekly/Monthly views
    - Drag & drop to change date/time
    - Role-based visibility
        - Admin sees all events
        - Employee sees only assigned events or task-related events
- Optional task assignment dropdown when creating/updating event

---

### Dashboard

- Welcome message with user name
- Displays role (Admin/Employee)
- Task statistics: total, completed, pending, in-progress
- Event statistics: total events
- Role-based access message

---

### Authentication

- Login page supports autofill demo credentials for Admin and Employee
- Props passed from controller: `admin`, `employee`
- Demo credentials: email & password fields

---

### Implementation Notes

- **RBAC**: Policies and Gates
    - TaskPolicy, EventPolicy
    - Gates for `manage-all-tasks`, `create-event`
- **Time Handling**: DB stored as `H:i:s`, form input `HH:mm`, validation handled
- **Frontend**:
    - Vue 3 + Inertia.js
    - TailwindCSS
    - Reusable components: TextInput, InputError, InputLabel, PrimaryButton
- **Backend**: Laravel 12
    - Controllers: TaskController, EventController
    - Calendar-related event logic currently in EventController

---

### Deployment Notes

- Run migrations and seeders before use
- Set `.env` for production
- Ensure `storage` and `bootstrap/cache` have correct permissions
- Install Composer dependencies on server
- Optional: can separate CalendarController for cleaner code

---

### Git Commit Suggestion

```bash
git add .
git commit -m "Complete RBAC Task Planner & Calendar Event Management"
git push origin main
```
