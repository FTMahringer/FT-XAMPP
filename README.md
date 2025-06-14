# FT-XAMPP

This is a lightweight dashboard project running on PHP, Apache, MySQL, and Redis using Docker.  
It serves as a local overview or entry point for various projects located in the `htdocs` directory.  
The dashboard supports automatic link detection, `.htaccess` file checking, and a light/dark theme switch.

## 🧰 Requirements

- Docker (I used V 28.1.1)  
- Docker Compose (I used V 2.36-desktop.1)

To find out what version you run, just type:
```bash
docker -v
docker compose version
```

## ⚙️ Installation

> 🪟 **Windows / Linux (x86)**  
> Use the default `docker-compose.yml` and `Dockerfile`.

```bash
# 1. Clone the repository
git clone https://github.com/FTMahringer/FT-XAMPP.git
cd FT-XAMPP

# 2. Copy the environment file
cp .env .env.local

# 3. Optionally adjust credentials in .env.local

# 4. Start the containers
docker-compose up -d
```

> 🍏 **macOS (Apple Silicon or Intel)**  
> Use the Mac-specific Dockerfile and Compose file for best compatibility:

```bash
# Start with macOS-optimized Docker setup
docker-compose -f docker-compose-mac.yml up --build -d
```

📂 Open in your browser: [http://localhost:8080](http://localhost:8080)

---

## 🔧 Configuration

| File                  | Description                                                   |
|-----------------------|---------------------------------------------------------------|
| `.env`                | Default environment variables for database and services       |
| `php.ini`             | PHP configuration (e.g., memory limit, extensions)            |
| `.htaccess`           | Apache rewrite rules and security configuration               |
| `index.php`           | Main entry point of the dashboard                             |
| `style.css`           | Styling with support for light and dark themes                |
| `docker-compose.yml`  | Default setup for Windows/Linux x86                           |
| `docker-compose-mac.yml` | macOS setup with native ARM support for Apple Silicon       |
| `Dockerfile`          | Apache+PHP image for Linux/Windows                            |
| `Dockerfile.mac`      | macOS-optimized build for native ARM64 support                |

---

## 📁 Folder Structure

```
.
├── docker-compose.yml
├── docker-compose-mac.yml
├── Dockerfile
├── Dockerfile.mac
├── .env
├── php.ini
└── htdocs/
    ├── .htaccess
    ├── index.php
    └── style.css
```

---

## 📦 Used Images

- `php:8.3-apache`  
- `mariadb:latest`  
- `phpmyadmin/phpmyadmin`  
- `redis:latest`
- `redisinsight:latest`

---

## 🧪 Healthchecks

The Apache container includes a simple HTTP healthcheck for service monitoring.  
Redis and MariaDB are defined as dependencies via `depends_on`.

---

# 🗂️ Creating Projects

Projects should be created inside the `htdocs` folder, just like with classic XAMPP setups.

- If a project folder contains a `public` folder, the dashboard automatically uses it as the entry point.
- The dashboard checks if a `.htaccess` file exists inside the project root (or `public` folder).  
  If it doesn’t exist, a message and a button to add one will be shown.
- Clicking the button opens a small popup where you can **edit** the `.htaccess` file before saving it into the project.

The **base `.htaccess`** template is located inside the `htdocs` folder. All new projects will use that as the starting point for `.htaccess` generation and customization.

---

## 🛠️ Common Issues / Tips

To run PHP or Linux commands inside your Apache container:

```bash
# List all running containers
docker ps

# Replace <apache-container-name> with the actual container name (e.g., ftxampp_apache)
docker exec -it <apache-container-name> bash
```

If there is a `public` folder, the dashboard will choose it as the entry point, even if `index.php` exists in the root.

---

## 📜 License

MIT License – free for personal and commercial use.
