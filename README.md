# Family Store POS & Inventory System - Laravel Backend

Sistem backend untuk aplikasi Family Store POS & Inventory Management menggunakan Laravel 10 dengan MySQL database.

## 🚀 Fitur Utama

### 1. Authentication & Authorization
- Login/logout dengan Laravel Sanctum
- Role-based access control (SuperAdmin, Admin, Cashier)
- Middleware untuk proteksi route berdasarkan role

### 2. Point of Sale (POS)
- Pencarian produk real-time
- Scan barcode produk
- Keranjang belanja dengan multiple items
- Kalkulasi otomatis (subtotal, diskon, pajak, kembalian)
- Multiple payment methods (Cash, QRIS, Transfer)
- Generate receipt/struk transaksi

### 3. Inventory Management
- CRUD produk dengan kategori
- Upload gambar produk
- Tracking stok real-time
- Stock adjustment (masuk/keluar/penyesuaian)
- Alert stok menipis
- Riwayat pergerakan stok

### 4. Reporting & Analytics
- Laporan penjualan (harian/mingguan/bulanan)
- Laporan inventory dan stok
- Laporan performa kasir
- Dashboard analytics dengan metrics
- Export data ke Excel/PDF

### 5. User Management
- CRUD users dengan role management
- Aktivasi/deaktivasi user
- Change password
- User activity tracking

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (untuk asset compilation)

## 🛠️ Installation

### 1. Clone & Setup Project
```bash
git clone <repository-url> family-store-pos
cd family-store-pos
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=family_store_pos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 4. Storage Link
```bash
php artisan storage:link
```

### 5. Install Frontend Dependencies (Optional)
```bash
npm install
npm run build
```

### 6. Start Development Server
```bash
php artisan serve
```

## 🔐 Default Users

Setelah seeding, Anda dapat login dengan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | super@familystore.com | 123456 |
| Admin | admin@familystore.com | 123456 |
| Kasir | kasir@familystore.com | 123456 |
| Kasir | budi@familystore.com | 123456 |

## 📚 API Documentation

### Authentication
```
POST /api/login
POST /api/logout
GET  /api/me
POST /api/change-password
```

### POS System
```
GET  /api/pos/products/search
GET  /api/pos/products/barcode
POST /api/pos/transaction
GET  /api/pos/transaction/{id}/receipt
GET  /api/pos/transactions/today
```

### Product Management (Admin+)
```
GET    /api/products
POST   /api/products
GET    /api/products/{id}
PUT    /api/products/{id}
DELETE /api/products/{id}
POST   /api/products/{id}/adjust-stock
GET    /api/products/low-stock/list
```

### Reports (Admin+)
```
GET /api/reports/sales
GET /api/reports/inventory
GET /api/reports/cashier
GET /api/dashboard/stats
```

### User Management (SuperAdmin only)
```
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}
POST   /api/users/{id}/toggle-status
```

## 🗄️ Database Schema

### Users
- id, name, email, password, role, is_active, last_login_at

### Categories
- id, name, slug, description, color, is_active

### Products
- id, name, slug, barcode, sku, category_id, buy_price, sell_price, stock, min_stock, unit, image

### Transactions
- id, transaction_code, user_id, customer_name, subtotal, discount_amount, total_amount, payment_method, paid_amount, change_amount, status

### Transaction Items
- id, transaction_id, product_id, product_name, price, quantity, subtotal

### Stock Movements
- id, product_id, user_id, type, quantity, stock_before, stock_after, reference_type, reference_id, notes

## 🔒 Security Features

- API authentication dengan Laravel Sanctum
- Role-based middleware protection
- Input validation pada semua endpoints
- CSRF protection
- SQL injection prevention
- XSS protection

## 📱 Frontend Integration

Backend ini dirancang untuk bekerja dengan frontend React/Vue.js atau mobile app. Semua response menggunakan format JSON dengan struktur:

```json
{
  "success": true,
  "message": "Success message",
  "data": {...}
}
```

## 🚀 Production Deployment

### 1. Server Requirements
- PHP 8.1+
- MySQL 5.7+
- Nginx/Apache
- SSL Certificate

### 2. Environment Configuration
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 3. Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

### 4. Queue & Scheduler (Optional)
```bash
php artisan queue:work
# Add to crontab:
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🔧 Configuration

### POS Settings
Edit `.env` untuk konfigurasi toko:
```env
POS_STORE_NAME="Family Store"
POS_STORE_ADDRESS="Jl. Contoh No. 123, Jakarta"
POS_STORE_PHONE="021-12345678"
POS_CURRENCY=IDR
POS_TAX_RATE=0.10
```

### File Upload
Konfigurasi upload di `config/filesystems.php` untuk gambar produk.

## 📞 Support

Untuk pertanyaan dan dukungan teknis, silakan hubungi tim development.

## 📄 License

This project is licensed under the MIT License.