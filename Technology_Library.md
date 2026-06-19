# Teknologi, Library, dan Arsitektur

[README](README.md) | [Planning](PLANNING.md) | [Fitur](Featured.md) | [Role](Role.md) | [Teknologi & Library](Technology_Library.md)

## 5. Arsitektur Aplikasi

## 5.1 Layer Aplikasi

```text
routes/
  web.php
  auth.php

app/
  Models/
  Livewire/
  Services/
  Actions/
  DTO/
  Policies/
  Observers/
  Jobs/
  Notifications/
  Exports/
  Imports/
  Support/

resources/
  views/
  css/
  js/

database/
  migrations/
  seeders/
  factories/

tests/
  Feature/
  Unit/
```

## 5.2 Pola Komponen Livewire

Gunakan struktur modular:

```text
app/Livewire/FoodItems/
  Index.php
  Create.php
  Edit.php
  Show.php

app/Livewire/Menus/
  Index.php
  Builder.php
  Show.php
  NutritionAnalysis.php

app/Livewire/PurchaseOrders/
  Index.php
  Create.php
  Show.php
  Approval.php
```

## 5.3 Service Layer Wajib

```text
App\Services\Nutrition\NutritionCalculatorService
App\Services\Menu\MenuCostingService
App\Services\Purchase\RequirementGeneratorService
App\Services\Purchase\PurchaseOrderService
App\Services\Inventory\StockMovementService
App\Services\Distribution\DistributionScheduleService
App\Services\Distribution\TripTrackingService
App\Services\Finance\JournalService
App\Services\Schedule\WorkPeriodService
App\Services\QrCode\QrCodeService
```

---

---

## 6. Rancangan Database Awal

## 6.1 Master dan Auth

- users
- roles
- permissions
- model_has_roles
- model_has_permissions
- units
- user_units
- activity_log

## 6.2 Bahan dan Gizi

- food_categories
- food_items
- nutrient_components
- food_item_nutrients
- processed_foods
- processed_food_ingredients

## 6.3 Menu

- menus
- menu_items
- menu_portions
- menu_nutrition_summaries
- menu_versions

## 6.4 Jadwal

- work_periods
- work_days
- work_shifts
- work_positions
- work_assignments
- daily_menus

## 6.5 Purchase dan Supplier

- suppliers
- supplier_items
- material_requirements
- purchase_orders
- purchase_order_items
- goods_receipts
- goods_receipt_items

## 6.6 Inventory

- inventory_items
- inventory_batches
- stock_movements
- stock_opnames
- assets
- asset_maintenance_logs

## 6.7 Personil

- personnel
- personnel_positions
- attendances
- attendance_summaries

## 6.8 Penerima

- recipients
- recipient_contacts
- recipient_documents
- distribution_schedules

## 6.9 Distribusi

- distribution_trips
- distribution_trip_stops
- distribution_trip_personnel
- distribution_manifests
- distribution_manifest_items
- distribution_tracking_points
- delivery_proofs
- delivery_issues

## 6.10 Keuangan

- accounts
- journal_entries
- journal_entry_lines
- cash_transactions
- financial_periods

## 6.11 Emergency

- emergency_contacts
- emergency_announcements

---

---

## 7. Library dan Package yang Direkomendasikan

## 7.1 Backend Laravel

```bash
composer require livewire/livewire
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require spatie/laravel-medialibrary
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
composer require simplesoftwareio/simple-qrcode
composer require intervention/image-laravel
composer require predis/predis
composer require laravel/horizon
composer require laravel/pulse
composer require laravel/octane
composer require laravel/telescope --dev
composer require pestphp/pest --dev
```

## 7.2 Frontend

```bash
npm install tailwindcss @tailwindcss/vite
npm install alpinejs
npm install apexcharts
npm install leaflet
npm install @vitejs/plugin-vue --save-dev
```

Catatan: `@vitejs/plugin-vue` hanya dipasang bila ada kebutuhan komponen Vue kecil. Untuk stack Livewire murni, tidak wajib.

## 7.3 Opsional

```bash
composer require laravel/reverb
composer require pusher/pusher-php-server
composer require wire-elements/modal
composer require filament/support
composer require laravel/sanctum
composer require darkaonline/l5-swagger --dev
```

### Kapan dipakai?

- `laravel/reverb`: bila butuh notifikasi realtime.
- `laravel/sanctum`: bila nanti dibuat mobile app/API.
- `l5-swagger`: bila API akan didokumentasikan.
- `filament/support`: bila butuh beberapa utilitas, bukan full admin panel.
- `wire-elements/modal`: modal Livewire lebih rapi.

## 7.4 Laravel Octane untuk Production

Laravel Octane digunakan untuk meningkatkan throughput aplikasi dengan menjalankan Laravel pada application server berperforma tinggi dan menjaga aplikasi tetap berada di memory antar-request.

Instalasi awal:

```bash
composer require laravel/octane
php artisan octane:install --server=frankenphp
```

Application server yang didukung:

- FrankenPHP.
- Open Swoole atau Swoole.
- RoadRunner.

Contoh konfigurasi production pada dokumen ini menggunakan FrankenPHP. Karena worker Octane bersifat long-lived, kode aplikasi tidak boleh menyimpan state request atau data user pada singleton, static property, atau global state yang dapat terbawa ke request berikutnya.

---

---

## 8. Setup Awal Project

## 8.1 Buat Project

```bash
composer create-project laravel/laravel sids
cd sids
```

## 8.2 Install Livewire

```bash
composer require livewire/livewire
```

## 8.3 Install Tailwind CSS

```bash
npm install tailwindcss @tailwindcss/vite
npm install
npm run dev
```

## 8.4 Install Package Utama

```bash
composer require spatie/laravel-permission spatie/laravel-activitylog spatie/laravel-medialibrary
composer require maatwebsite/excel barryvdh/laravel-dompdf simplesoftwareio/simple-qrcode intervention/image-laravel
```

## 8.5 Publish Config

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
```

## 8.6 Migration

```bash
php artisan migrate
```

## 8.7 Seeder Awal

Seeder yang wajib dibuat:

```text
RolePermissionSeeder
UnitSeeder
FoodCategorySeeder
NutrientComponentSeeder
AccountSeeder
EmergencyContactSeeder
WorkPositionSeeder
```

---

---

## 14. Security Plan

- Role-based permission.
- Policy per model penting.
- Audit log.
- CSRF protection.
- Rate limiting login.
- Password hashing default Laravel.
- Validasi file upload.
- Batas ukuran file.
- Sanitasi input.
- Backup database.
- Environment variable aman.
- Tidak menyimpan credential di repository.
- HTTPS wajib di production.
- Session secure cookie di production.
- Queue worker dipantau.
- Log error tidak menampilkan data sensitif.

---

---

## 15. Deployment Plan

## 15.1 Server Requirement

- PHP 8.3+
- Composer 2+
- Node.js LTS
- MySQL 8+ / MariaDB 10.6+ / PostgreSQL 15+
- Redis
- Nginx
- Supervisor
- Salah satu application server Octane: FrankenPHP, Open Swoole/Swoole, atau RoadRunner
- SSL certificate
- Cron enabled

## 15.2 Production Command

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
php artisan queue:restart
php artisan octane:reload
```

Perintah `octane:reload` dijalankan setelah deployment untuk memuat kode terbaru secara graceful pada worker yang sedang aktif.

## 15.3 Supervisor

Queue worker:

```text
php artisan queue:work --sleep=3 --tries=3 --timeout=120
```

Octane server menggunakan FrankenPHP:

```ini
[program:octane]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan octane:start --server=frankenphp --host=127.0.0.1 --port=8000
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/octane.log
stopwaitsecs=3600
```

Sesuaikan `/path/to/project` dan user proses dengan konfigurasi server production. Nginx meneruskan request ke Octane pada `127.0.0.1:8000`, sedangkan Supervisor menjaga proses Octane tetap berjalan.

Scheduler:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

---

## 18. Struktur Branch Git

```text
main
develop
feature/auth-role
feature/food-items
feature/menu-nutrition
feature/schedule
feature/requirements
feature/purchase-order
feature/inventory
feature/distribution
feature/personnel
feature/finance
feature/dashboard-report
hotfix/*
release/*
```

---

---

## 19. Coding Standard

- Gunakan Laravel Pint.
- Gunakan Pest untuk testing.
- Nama class jelas dan tidak disingkat berlebihan.
- Business logic tidak ditaruh di Blade.
- Livewire component tidak boleh menjadi “keranjang sampah logic”.
- Gunakan service untuk kalkulasi.
- Gunakan policy untuk akses.
- Gunakan enum untuk status.
- Gunakan migration yang reversible.
- Gunakan soft delete untuk data penting.
- Gunakan database transaction untuk proses multi-step.

---
