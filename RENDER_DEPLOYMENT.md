# 🚀 Deploying Blood Lagbe to Render

This comprehensive guide walks you through deploying **Blood Lagbe** to [Render](https://render.com) using Docker and a Managed PostgreSQL database.

---

## 📋 Prerequisites

1. A [GitHub](https://github.com) account.
2. A [Render](https://render.com) account.
3. Your Blood Lagbe repository pushed to GitHub.

---

## ⚡ Option A: Blueprints Deployment (`render.yaml` - Recommended)

Render supports Infrastructure as Code via Blueprints. The project includes a configured `render.yaml` file in the root directory.

### Step 1: Push Code to GitHub
Ensure all your project files, including `Dockerfile`, `render.yaml`, and `docker/` configuration scripts, are committed and pushed to your GitHub repository:
```bash
git add .
git commit -m "feat: complete Blood Lagbe production platform"
git push origin main
```

### Step 2: Create a Blueprint on Render
1. Log into your **Render Dashboard**.
2. Click **New +** in the top navigation bar and select **Blueprint**.
3. Connect your GitHub account and choose the **BLOOD LAGBE** repository.
4. Render will parse `render.yaml` and display the planned resources:
   - **blood-lagbe** (Web Service, Docker runtime)
   - **blood-lagbe-db** (PostgreSQL Database, Free Tier)
5. Click **Apply**.
6. Render will automatically:
   - Provision the PostgreSQL database.
   - Build the Docker container image (running PHP 8.2-FPM + Nginx + Node build).
   - Inject the database credentials (`DATABASE_URL`).
   - Run the startup script `docker/start.sh` which executes migrations and database seeders.
   - Run health checks on `/health`.

---

## 🛠️ Option B: Manual Dashboard Setup

If you prefer configuring resources manually through the Render web UI:

### Step 1: Create PostgreSQL Database
1. Go to **Dashboard** → **New +** → **PostgreSQL**.
2. Configure settings:
   - **Name**: `blood-lagbe-db`
   - **Database**: `blood_lagbe`
   - **User**: `blood_lagbe_user`
   - **Region**: Choose the region closest to your users (e.g., Singapore or Frankfurt).
   - **Plan**: Free
3. Click **Create Database**.
4. Once provisioned, copy the **Internal Database URL** (e.g., `postgresql://blood_lagbe_user:...@dpg-...:5432/blood_lagbe`).

### Step 2: Create Web Service
1. Go to **Dashboard** → **New +** → **Web Service**.
2. Connect your GitHub repository.
3. Configure the service:
   - **Name**: `blood-lagbe`
   - **Region**: Same as database.
   - **Branch**: `main`
   - **Runtime**: **Docker**
   - **Dockerfile Path**: `./Dockerfile`
   - **Docker Context**: `.`
   - **Plan**: Free

### Step 3: Configure Environment Variables
Under the **Environment Variables** section, add:

| Key | Value | Notes |
|---|---|---|
| `APP_NAME` | `Blood Lagbe` | |
| `APP_ENV` | `production` | Enables production optimizations |
| `APP_DEBUG` | `false` | Security safeguard |
| `APP_KEY` | *(Generate via `php artisan key:generate --show`)* | 32-character base64 string |
| `APP_URL` | `https://your-app-name.onrender.com` | Your assigned Render URL |
| `DB_CONNECTION` | `pgsql` | |
| `DATABASE_URL` | *(Paste Internal Database URL from Step 1)* | Auto-parsed by Laravel |
| `CACHE_DRIVER` | `file` | Or `database` / `redis` |
| `SESSION_DRIVER` | `file` | Or `database` / `cookie` |
| `QUEUE_CONNECTION` | `sync` | |
| `LOG_CHANNEL` | `stderr` | Streams logs directly to Render log viewer |
| `LOG_LEVEL` | `error` | |
| `MAIL_MAILER` | `log` | Switch to `smtp` with Gmail/SendGrid credentials |
| `BCRYPT_ROUNDS` | `12` | Secure password hashing |

### Step 4: Health Check Path
Under **Advanced**:
- **Health Check Path**: `/health`

Click **Create Web Service**.

---

## 🔍 How Deployment Works Internally

1. **Multi-Stage Docker Build**:
   - Uses `php:8.2-fpm-alpine` for minimal image size and fast cold starts.
   - Installs PostgreSQL extensions (`pdo_pgsql`, `pgsql`), `gd`, `zip`, and `opcache`.
   - Compiles Tailwind CSS and Vite production assets during image build (`npm run build`).
   - Optimizes Composer autoloader with `--no-dev --optimize`.

2. **Automated Startup Sequence (`docker/start.sh`)**:
   - Ensures writable permissions for `storage` and `bootstrap/cache`.
   - Runs database migrations safely: `php artisan migrate --force --no-interaction`.
   - Runs initial seeders if the database is newly created: `php artisan db:seed --force`.
   - Runs production caches: `config:cache`, `route:cache`, `view:cache`.
   - Boots `supervisord`, which runs Nginx and PHP-FPM together on port 80.

---

## 🔑 Default Seeded Accounts for Testing

After deployment and initial seeding, you can test the application using:

| Account Type | Email | Password | Role / Access |
|---|---|---|---|
| **Admin** | `admin@bloodlagbe.com` | `password` | Full Admin Panel access (`/admin`) |
| **Donor (Demo)** | `rahim@example.com` | `password` | Verified O+ Donor |
| **User (Demo)** | `user@bloodlagbe.com` | `password` | Regular User Dashboard (`/dashboard`) |

> ⚠️ **Security Warning**: Change the default admin password immediately upon deploying to a live production environment.

---

## 📂 Storage & File Uploads Notice

On Render's Free tier, the local container filesystem is ephemeral (resets on restart). 
For persistent donor avatars or medical verification document uploads in high-scale production:
1. Attach a **Render Persistent Disk** mounted at `/var/www/html/storage/app/public`.
2. Or configure external object storage (AWS S3, Cloudflare R2, or DigitalOcean Spaces) in `config/filesystems.php`.

---

## 🩺 Verifying Deployment & Troubleshooting

- **Health Check**: Access `https://your-domain.onrender.com/health` — it should return:
  ```json
  {"status":"ok","db":"connected"}
  ```
- **Application Logs**: Check the **Logs** tab in your Render Web Service dashboard for live supervisor, Nginx, and Laravel exception output.
- **Run Artisan Commands via Shell**:
  Render provides an in-browser web terminal (**Shell** tab). You can run:
  ```bash
  php artisan migrate:status
  php artisan route:list
  ```
