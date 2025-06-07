
# FT-XAMPP

This is a lightweight dashboard project running on PHP, Apache, MySQL, and Redis using Docker.  
It serves as a local overview or entry point for various projects located in the `htdocs` directory.  
The dashboard supports automatic link detection, `.htaccess` file checking, and a light/dark theme switch.

## 🧰 Requirements

- Docker (>= 20.10)  
- Docker Compose (>= 1.29)

## ⚙️ Installation

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/dashboard.git
cd dashboard

# 2. Copy the environment file
cp .env .env.local

# 3. Optionally adjust credentials in .env.local

# 4. Start the containers
docker-compose up -d
```

📂 Open in your browser: [http://localhost:8080](http://localhost:8080)

## 🔧 Configuration

| File           | Description                                                   |
|----------------|---------------------------------------------------------------|
| `.env`         | Default environment variables for database and services       |
| `php.ini`      | PHP configuration (e.g., memory limit, extensions)            |
| `.htaccess`    | Apache rewrite rules and security configuration               |
| `index.php`    | Main entry point of the dashboard                             |
| `style.css`    | Styling with support for light and dark themes                |

## 📁 Folder Structure

```
.
├── docker-compose.yml
├── Dockerfile
├── .env
├── php.ini
└── htdocs/
    ├── .htaccess
    ├── index.php
    └── style.css
```

## 📦 Used Images

- `php:8.3-apache`  
- `mariadb:latest`  
- `phpmyadmin/phpmyadmin`  
- `redis:latest`

## 🧪 Healthchecks

The Apache container includes a simple HTTP healthcheck for service monitoring.  
Redis and MariaDB are defined as dependencies via `depends_on`.

## 📜 License

MIT License – free for personal and commercial use.

---

**🔍 Tags:**  
`php`, `apache`, `docker`, `mysql`, `redis`, `dashboard`, `htaccess`, `local-dev`, `frontend`, `darkmode`

**📄 Short Description:**  
Dockerized PHP dashboard with automatic project linking, light/dark mode support, and `.htaccess` management.
