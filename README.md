# DNSly — Simple DNS Management Platform

DNSly is a showcase/MVP web application inspired by Free DNS management platforms (like FreeDNS.afraid.org). 

**Important Notice:** This is a simulation project built for a university class showcase. It does **not** actually create or manage real DNS records on any nameservers. It only simulates the interface and data management of a real SaaS platform.

## Features

- **Modern SaaS UI**: Clean, responsive, and minimal design using Tailwind CSS.
- **Authentication**: Secure user registration, login, and session management.
- **Domain Management**: Simulate adding and managing custom domains.
- **DNS Records**: Full CRUD operations for A, AAAA, CNAME, MX, TXT, and NS records.
- **Activity Logs**: Real-time tracking of account actions.
- **Settings**: Customizable theme (Light/Dark/System) and security settings.
- **Admin Panel**: Overview dashboard for platform statistics and recent user activities.

## Tech Stack

- **Backend**: PHP 8.3+ (Custom MVC architecture)
- **Database**: MySQL / MariaDB (via PDO)
- **Frontend**: HTML5, CSS3, JavaScript, Tailwind CSS (via CDN)
- **Routing**: Custom lightweight PHP Router

## Local Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ishantia/DNSly.git
   cd DNSly
   ```

2. **Configure Environment**
   Copy `.env.example` to `.env` and update the database credentials.
   ```bash
   cp .env.example .env
   ```

3. **Database Setup**
   Create a MySQL database matching your `.env` config (default: `dnsly`).
   Then run the migration script:
   ```bash
   php database/migrate.php
   ```

4. **Seed the Database (Optional but recommended for demo)**
   ```bash
   php database/seeders/DatabaseSeeder.php
   ```

5. **Start the Development Server**
   Use PHP's built-in web server:
   ```bash
   php -S localhost:8000 -t public
   ```
   Open `http://localhost:8000` in your browser.

## Demo Credentials

If you seeded the database, you can use the following credentials to test the platform:

- **User Account**: `demo@dnsly.local` / `password123`
- **Admin Account**: `admin@dnsly.local` / `password123`

## Screenshots

*(Screenshots placeholder - add images of your dashboard, domain page, and record management here)*

## Project Limitations

- **Simulated Infrastructure**: The system does not communicate with BIND, PowerDNS, or any external API.
- **No Email Verification**: Account creation is instant for demonstration purposes.
- **Basic Routing**: The custom router is simplified and intended only for this MVP structure.