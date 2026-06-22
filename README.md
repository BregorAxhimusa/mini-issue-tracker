# Mini Issue Tracker

A small issue tracker built with Laravel and Blade. You can create projects, log issues against them, tag and assign issues, and discuss them in comments. The UI is server-rendered with Tailwind CSS, and the interactive parts (tags, assignees, comments, and issue search) use plain `fetch` requests with no JavaScript framework.

## Requirements

- PHP 8.3 or newer
- Composer
- MySQL 8.0 or newer
- Node.js 20 or newer (with npm)

## Setup

1. Install PHP dependencies:

   ```
   composer install
   ```

2. Copy the environment file and generate an app key:

   ```
   cp .env.example .env
   php artisan key:generate
   ```

3. Create a MySQL database named `issue_tracker` (or whatever you prefer) and set the connection details in `.env`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=issue_tracker
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Run the migrations and seed the demo data:

   ```
   php artisan migrate:fresh --seed
   ```

5. Install the front-end dependencies and build the assets:

   ```
   npm install
   npm run build
   ```

   During development you can run `npm run dev` instead to get hot reloading.

6. Start the application:

   ```
   php artisan serve
   ```

   The app is then available at http://127.0.0.1:8000 and the home page redirects to the projects list.

## Demo accounts

The seeder creates two project owners. The password for every seeded user is `password`.

| Email | Password |
| --- | --- |
| alice@example.com | password |
| bob@example.com | password |

You can also register a new account from the login page.

## Features

- Projects: create, view, edit, and delete, each with a name, description, start date, and deadline.
- Issues: create, view, edit, and delete, with a status (open, in progress, closed), a priority (low, medium, high), and an optional due date.
- Issue filters: filter the issue list by status, priority, and tag.
- Tags: attach and detach tags on an issue without a page reload.
- Comments: paginated comments loaded on demand, with new comments added inline and validation errors shown on the form.

### Bonuses implemented

- Authentication with Laravel Breeze (login and registration).
- Authorization: only a project's owner can edit or delete it, enforced with a policy.
- Multiple assignees (members) per issue, managed with the same inline tag-style interface.
- Debounced text search over issue titles and descriptions that combines with the other filters.

## Notes

- Status and priority are stored as plain strings; the allowed values are enforced by request validation rather than at the database level.
- All the AJAX endpoints return JSON (or an HTML fragment for the issue search) and expect the CSRF token, which is read from the page's meta tag.
