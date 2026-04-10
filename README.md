# Azurecly

Azurecly is an internal news and sentiment monitoring dashboard built on Laravel and Tailwind CSS.  
It helps teams track media coverage, classify sentiments, and manage escalation workflows across different roles (Admin, Humas, Media).

~~

## Features

- **Role-based dashboards**
  - **Admin**: Global overview with interactive map, sentiment statistics, popular categories, and high-priority negative news.
  - **Humas**: Operational dashboard focused on ticket (news) input, sentiment breakdown, and top actors/tags/regions.
  - **Media**: Consumption-focused dashboard that surfaces positive news by default, with a clean card layout for quick reading.

- **News / Ticket management**
  - Create, edit, view, and delete news tickets.
  - Fields include title, description, sentiment, priority, region, location, published date, and attachments.
  - Escalation flow via a dedicated escalation endpoint for high-priority or sensitive items.

- **Sentiment & priority indicators**
  - Visual chips for **positive / neutral / negative** sentiment.
  - Priority badges for **high / medium / low**, with color and icon mapping to urgency.
  - Compact card layout optimized for scanning and triage.

- **Geospatial view (Admin)**
  - Map of recent tickets using Leaflet.
  - Colored markers based on sentiment.
  - Popups showing title, location, sentiment, and priority.

- **Authentication & profile**
  - Laravel Breeze authentication scaffolding (login, registration, password reset, email verification).
  - User profile page (name, email, password).
  - Simple `role` field on the `users` table (`admin`, `humas`, `media`) for access control.

~~

## Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Blade, Tailwind CSS, Vite, Lucide icons
- **Database**: MySQL / MariaDB
- **Auth**: Laravel Breeze
- **Maps**: Leaflet (OpenStreetMap tiles)

~~

## Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & npm
- A MySQL/MariaDB database (or other configured driver)

### Installation

```bash
git clone <your-repo-url> azurecly
cd azurecly

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install
```

Copy the environment file and configure it:

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and app settings:

```env
APP_NAME=Azurecly
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=azurecly
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

You may want to seed at least one admin user by updating the `users` table and setting the `role` field to `admin`.

~~

## Running the Application (Local)

### Backend (Laravel)

```bash
php artisan serve
```

By default this runs at `http://127.0.0.1:8000`.

### Frontend / Vite Dev Server

```bash
npm run dev
```

This will start the Vite dev server for asset bundling and hot module replacement.

For a combined local dev experience, the project also includes a small Node-based helper script (`start.js`) that runs both Laravel and Vite dev servers together via `npm run start`.

~~

## Roles & Access Control

The application uses a simple `role` column on the `users` table:

- `admin`
  - Access to the Admin dashboard (map, global stats, high-priority negative news).
  - Can view all news tickets and manage escalations.
  - Has access to all routes guarded by the `role` middleware.

- `humas`
  - Access to the Humas dashboard (sentiment cards, latest news list, top actor/tag/region).
  - Can create and manage tickets (news input).
  - Cannot see Admin-only controls such as the map endpoint.

- `media`
  - Access to the Media dashboard (focus on positive news).
  - Cannot create or edit tickets.
  - By default sees only **positive** news; negative news requires admin approval before being surfaced.

Role-based route protection is implemented via a custom `RoleMiddleware`, registered under the `role` alias and applied to relevant route groups.

~~

## Dashboards

### Admin Dashboard

- Sentiment and priority statistics (total news, today’s news, positive/negative counts, high priority).
- Interactive map showing recent tickets, colored by sentiment.
- Popular categories with simple bar-style progress.
- List of high-priority negative news with location and recency details.

### Humas Dashboard

- Summary cards for total, positive, negative, and neutral news.
- List of latest tickets with:
  - Thumbnail image
  - Title and short description
  - Sentiment, priority, region, and published date as chips/badges
- Sidebar showing:
  - Top actors
  - Top tags
  - Top regions

### Media Dashboard

- Clean list of tickets optimized for reading, using the same card layout as Humas.
- Defaults to **positive** sentiment tickets.
- Sidebar with top actors/tags/regions for quick context.
- Optional filters in the header (e.g., sentiment, region) can be extended as needed.

~~

## Navigation & UX

- Top navigation bar shows menu items based on user role:
  - **Admin & Humas**: Dashboard, News, Contacts
  - **Media**: Dashboard only, plus profile and logout
- Active route highlighting works across:
  - `/dashboard` (role-based redirect)
  - `/dashboard/admin`
  - `/dashboard/humas`
  - `/dashboard/media`

~~

## Deployment

Azurecly is designed to be deployable on modern PHP hosting or container platforms. A typical deployment flow:

1. Build frontend assets:

   ```bash
   npm run build
   ```

2. Run database migrations on the server:

   ```bash
   php artisan migrate --force
   ```

3. Ensure `APP_ENV`, `APP_KEY`, `APP_URL`, and `APP_DEBUG` are correctly set in the server environment.

4. Configure cache optimizations (optional but recommended):

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

If you deploy using Docker or platforms such as Render, you can adapt the provided Dockerfile and build commands accordingly.

~~

## Testing

You can run the test suite with:

```bash
php artisan test
```

You can add feature and unit tests to cover critical workflows such as ticket creation, role-based access, and dashboard queries.

~~

## Contributing

Pull requests and suggestions are welcome.  
If you’d like to contribute:

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/your-feature-name`.
3. Commit your changes: `git commit -m "Add some feature"`.
4. Push the branch: `git push origin feature/your-feature-name`.
5. Open a Pull Request.

~~

## License

This project is open source and available under the MIT License.