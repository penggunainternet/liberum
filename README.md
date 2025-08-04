# Liberum - Forum Laravel

Forum diskusi modern dengan fitur upload gambar dan real-time interaction.

## Persyaratan

-   PHP >= 7.4
-   Composer
-   Node.js & NPM
-   MySQL
-   Docker (opsional, untuk MailHog)

## Instalasi

**Clone repo**

```bash
git clone https://github.com/penggunainternet/liberum.git
```

**Install Laravel**

```bash
composer install
```

**Copy file .env.example lalu ubah menjadi .env buka lalu ubah**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=liberum
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi MailHog untuk testing email lokal
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@liberum.test"
MAIL_FROM_NAME="Liberum"
```

**Buka terminal lalu jalankan perintah ini untuk melakukan key generate**

```bash
php artisan key:generate
```

**Import database**

```bash
php artisan migrate:fresh --seed
```

**Storage link untuk upload gambar**

```bash
php artisan storage:link
```

**Install assets**

```bash
npm install
npm run dev
```

**Setup MailHog untuk testing email (opsional)**

```bash
# Jalankan MailHog dengan Docker Compose (recommended)
docker-compose up -d mailhog

# Atau jalankan langsung dengan Docker
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog

# Akses web interface: http://localhost:8025
```

**Jalankan server**

```bash
php artisan serve
```

**Jalankan queue (terminal terpisah)**

```bash
php artisan queue:work
```

## Testing Email

Dengan konfigurasi MailHog, semua email dilihat di:

-   **Web Interface:** http://localhost:8025
-   Email tidak akan benar-benar terkirim, hanya untuk testing lokal
