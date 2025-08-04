# Liberum - Forum Laravel

Forum diskusi modern dengan fitur upload gambar dan real-time interaction.

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

**Jalankan server**

```bash
php artisan serve
```

**Jalankan queue (terminal terpisah)**

```bash
php artisan queue:work
```
