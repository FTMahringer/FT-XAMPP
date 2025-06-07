
# FT-XAMPP

This is a lightweight dashboard project running on PHP, Apache, MySQL, and Redis using Docker.  
It serves as a local overview or entry point for various projects located in the `htdocs` directory.  
The dashboard supports automatic link detection, `.htaccess` file checking, and a light/dark theme switch.

## 🧰 Requirements

- Docker (I used V 28.1.1)
- Docker Compose (I used V 2.36-desktop.1)

To find out what version you run, just type `docker -v` and `docker compose version`

## ⚙️ Installation

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
- `redisinsight:latest`



## 🧪 Healthchecks

The Apache container includes a simple HTTP healthcheck for service monitoring.  
Redis and MariaDB are defined as dependencies via `depends_on`.


# 🗂️ Creating Projects

Projects should be created inside the `htdocs` folder, just like with classic XAMPP setups.

- If a project folder contains a `public` folder, the dashboard automatically uses it as the entry point.
- The dashboard checks if a `.htaccess` file exists inside the project root (or `public` folder).  
  If it doesn’t exist, a message and a button to add one will be shown.
- Clicking the button opens a small popup where you can **edit** the `.htaccess` file before saving it into the project.

The **base `.htaccess`** template is located inside the `htdocs` folder. All new projects will use that as the starting point for `.htaccess` generation and customization.


## 🛠️ Common Issues / Tips

If you want to run PHP or Linux commands, make sure you're inside the Apache container.
You can list all running containers and access the Apache container using the following commands:

```bash
### List all running containers
`docker ps`

### Replace <apache-container-name> with the actual container name (e.g., ftxampp_apache)
`docker exec -it <apache-container-name> bash`
```

If you have a public folder, the dashboard will choose that as the entrypoint, wheter you have a index.php in the root folder or not.

---

## 📜 License

MIT License – free for personal and commercial use.

