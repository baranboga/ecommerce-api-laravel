# E-Ticaret API

PHP ve PostgreSQL kullanılarak geliştirilmiş RESTful JSON API projesi.

## Teknik Gereksinimler

-   **Backend:** PHP 8.2+
-   **Framework:** Laravel 12
-   **Veritabanı:** PostgreSQL 13+
-   **Authentication:** JWT (tymon/jwt-auth)
-   **API Dokümantasyonu:** Swagger/OpenAPI (l5-swagger)
-   **Containerization:** Docker & Docker Compose

## Özellikler

### Kimlik Doğrulama ve Yetkilendirme

-   ✅ JWT (JSON Web Token) tabanlı authentication
-   ✅ Kullanıcı kaydı ve girişi
-   ✅ Profil görüntüleme ve güncelleme
-   ✅ Rol tabanlı yetkilendirme (Admin/User)
-   ✅ AdminMiddleware ile admin yetkisi kontrolü

### Güvenlik

-   ✅ Rate Limiting (Login/Register: 5 dakikada 1 istek, API: Dakikada 60 istek)
-   ✅ Şifreler bcrypt ile hash'lenir
-   ✅ SQL injection koruması (Eloquent ORM)
-   ✅ XSS koruması
-   ✅ Input validation ve sanitization

### Ürün Yönetimi

-   ✅ Ürün listeleme (filtreleme ve sayfalama)
-   ✅ Ürün detay görüntüleme
-   ✅ Ürün arama (isim bazlı)
-   ✅ Fiyat aralığı filtresi
-   ✅ Kategori bazlı filtreleme
-   ✅ Admin: Ürün oluşturma, güncelleme ve silme

### Kategori Yönetimi

-   ✅ Kategori listeleme
-   ✅ Admin: Kategori oluşturma, güncelleme ve silme

### Sepet Yönetimi

-   ✅ Sepeti görüntüleme
-   ✅ Sepete ürün ekleme
-   ✅ Sepet ürün miktarı güncelleme
-   ✅ Sepetten ürün çıkarma
-   ✅ Sepeti temizleme
-   ✅ Stok kontrolü

### Sipariş Yönetimi

-   ✅ Sipariş oluşturma (sepetten otomatik dönüşüm)
-   ✅ Kullanıcı siparişlerini listeleme
-   ✅ Sipariş detay görüntüleme
-   ✅ Toplam tutar hesaplama

### API Özellikleri

-   ✅ RESTful API tasarımı
-   ✅ Tutarlı response formatı (ResponseHelper)
-   ✅ Swagger/OpenAPI dokümantasyonu
-   ✅ Interaktif API test arayüzü (Swagger UI)
-   ✅ HTTP status kodları (200, 201, 400, 401, 404, 422, 500)
-   ✅ Detaylı hata mesajları ve validasyon hataları

### Mimari ve Tasarım

-   ✅ Service Layer pattern
-   ✅ Controller-Service-Model ayrımı
-   ✅ Eloquent ORM ile veritabanı işlemleri
-   ✅ Migration ve Seeder yapısı
-   ✅ Middleware yapısı

### Docker ve Deployment

-   ✅ Docker Compose ile containerization
-   ✅ Nginx web server
-   ✅ PHP-FPM
-   ✅ PostgreSQL veritabanı
-   ✅ Adminer (Veritabanı yönetim arayüzü)
-   ✅ Kolay kurulum ve çalıştırma

### Veritabanı

-   ✅ PostgreSQL 13+ desteği
-   ✅ Migration tabanlı veritabanı yönetimi
-   ✅ Seeder ile örnek veri yükleme
-   ✅ İlişkisel veritabanı tasarımı

## Proje Kurulumu

### Gereksinimler

-   Docker Desktop (kurulu ve çalışıyor olmalı)
-   Git

### Kurulum Adımları

1. **Projeyi klonlayın:**

```bash
git clone <repository-url>
cd php
```

2. **Docker container'larını başlatın:**

```bash
docker-compose up -d --build
```

**Not:** `.env` dosyası projeye dahil edilmiştir ve Laravel için zorunludur. Veritabanı bağlantı bilgileri ile diğer yapılandırmalar bu dosyada tutulur.

3. **Composer bağımlılıklarını yükleyin:**

```bash
docker-compose exec php composer install
```

4. **Veritabanı migration'larını çalıştırın:**

```bash
docker-compose exec php php artisan migrate
```

5. **Sample data'yı ekleyin:**

```bash
docker-compose exec php php artisan db:seed
```

6. **Swagger dokümantasyonunu oluşturun:**

```bash
docker-compose exec php php artisan l5-swagger:generate
```

### Servisler

-   **API:** http://localhost:8000
-   **Swagger UI:** http://localhost:8000/api/documentation
-   **Adminer (DB UI):** http://localhost:8080
-   **PostgreSQL:** localhost:5432

## Veritabanı Bilgileri

-   **Host:** postgres (Docker içinden) / localhost:5432 (Host'tan)
-   **Database:** ecommerce_db
-   **Username:** ecommerce_user
-   **Password:** ecommerce_pass

### Adminer ile Bağlanma

1. http://localhost:8080 adresini açın
2. Bilgileri girin:
    - **System:** PostgreSQL
    - **Server:** postgres
    - **Username:** ecommerce_user
    - **Password:** ecommerce_pass
    - **Database:** ecommerce_db

## Test Kullanıcıları

### Admin Kullanıcı

-   **Email:** admin@test.com
-   **Password:** admin123
-   **Role:** admin

### Normal Kullanıcı

-   **Email:** user@test.com
-   **Password:** user123
-   **Role:** user

## API Endpoint'leri

### Authentication

#### Kullanıcı Kaydı

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (201):**

```json
{
    "success": true,
    "message": "Kullanıcı başarıyla kaydedildi",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
    },
    "errors": []
}
```

#### Kullanıcı Girişi

```http
POST /api/login
Content-Type: application/json

{
  "email": "user@test.com",
  "password": "user123"
}
```

**Response (200):**

```json
{
  "success": true,
  "message": "Giriş başarılı",
  "data": {
    "user": {...},
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

#### Profil Görüntüleme

```http
GET /api/profile
Authorization: Bearer {token}
```

#### Profil Güncelleme

```http
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name",
  "email": "newemail@example.com"
}
```

### Kategoriler

#### Kategori Listesi (Herkes)

```http
GET /api/categories
Authorization: Bearer {token}
```

#### Kategori Oluştur (Admin)

```http
POST /api/categories
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Yeni Kategori",
  "description": "Kategori açıklaması"
}
```

#### Kategori Güncelle (Admin)

```http
PUT /api/categories/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Güncellenmiş Kategori"
}
```

#### Kategori Sil (Admin)

```http
DELETE /api/categories/{id}
Authorization: Bearer {admin_token}
```

### Ürünler

#### Ürün Listesi (Filtreleme ve Sayfalama)

```http
GET /api/products?page=1&limit=20&category_id=1&min_price=1000&max_price=5000&search=iPhone
Authorization: Bearer {token}
```

**Query Parameters:**

-   `page`: Sayfa numarası (varsayılan: 1)
-   `limit`: Sayfa başına kayıt (varsayılan: 20)
-   `category_id`: Kategori filtresi
-   `min_price`: Minimum fiyat
-   `max_price`: Maximum fiyat
-   `search`: Ürün adında arama

#### Ürün Detayı

```http
GET /api/products/{id}
Authorization: Bearer {token}
```

#### Ürün Oluştur (Admin)

```http
POST /api/products
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Yeni Ürün",
  "description": "Ürün açıklaması",
  "price": 1000.00,
  "stock_quantity": 50,
  "category_id": 1
}
```

#### Ürün Güncelle (Admin)

```http
PUT /api/products/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "price": 1200.00,
  "stock_quantity": 45
}
```

#### Ürün Sil (Admin)

```http
DELETE /api/products/{id}
Authorization: Bearer {admin_token}
```

### Sepet

#### Sepeti Görüntüle

```http
GET /api/cart
Authorization: Bearer {token}
```

#### Sepete Ürün Ekle

```http
POST /api/cart/add
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}
```

#### Sepet Ürün Miktarı Güncelle

```http
PUT /api/cart/update
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 3
}
```

#### Sepetten Ürün Çıkar

```http
DELETE /api/cart/remove/{product_id}
Authorization: Bearer {token}
```

#### Sepeti Temizle

```http
DELETE /api/cart/clear
Authorization: Bearer {token}
```

### Siparişler

#### Sipariş Oluştur

```http
POST /api/orders
Authorization: Bearer {token}
```

**Not:** Sepetteki ürünler otomatik olarak siparişe dönüştürülür.

**Response (201):**

```json
{
  "success": true,
  "message": "Sipariş oluşturuldu",
  "data": {
    "id": 1,
    "user_id": 1,
    "total_amount": "90000.00",
    "status": "pending",
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": "45000.00",
        "product": {...}
      }
    ]
  }
}
```

#### Siparişleri Listele

```http
GET /api/orders
Authorization: Bearer {token}
```

#### Sipariş Detayı

```http
GET /api/orders/{id}
Authorization: Bearer {token}
```

## Response Format

Tüm API response'ları tutarlı formatta döner:

### Başarılı Response

```json
{
    "success": true,
    "message": "İşlem başarılı",
    "data": {},
    "errors": []
}
```

### Hata Response

```json
{
    "success": false,
    "message": "Hata mesajı",
    "data": null,
    "errors": []
}
```

### Validasyon Hatası (422)

```json
{
    "success": false,
    "message": "Validasyon hatası",
    "data": null,
    "errors": {
        "email": ["Email alanı zorunludur"],
        "password": ["Şifre minimum 8 karakter olmalıdır"]
    }
}
```

## HTTP Status Kodları

-   **200:** Başarılı işlem
-   **201:** Oluşturma başarılı
-   **400:** Geçersiz istek
-   **401:** Yetkisiz erişim
-   **404:** Bulunamadı
-   **422:** Validasyon hatası
-   **500:** Sunucu hatası

## Validasyon Kuralları

### Kullanıcı Kaydı

-   **name:** Zorunlu, minimum 2 karakter
-   **email:** Zorunlu, geçerli email formatı, benzersiz
-   **password:** Zorunlu, minimum 8 karakter

### Ürün Oluşturma

-   **name:** Zorunlu, minimum 3 karakter
-   **price:** Zorunlu, pozitif sayı
-   **stock_quantity:** Zorunlu, negatif olmayan tamsayı
-   **category_id:** Zorunlu, geçerli kategori ID

## Güvenlik

-   Şifreler bcrypt ile hash'lenir
-   SQL injection koruması (Eloquent ORM)
-   XSS koruması
-   JWT token tabanlı authentication
-   Input validation ve sanitization
-   Admin yetkisi kontrolü (AdminMiddleware)

## Veritabanı Yapısı

### Tablolar

-   **users:** Kullanıcı bilgileri (id, name, email, password, role)
-   **categories:** Kategoriler (id, name, description)
-   **products:** Ürünler (id, name, description, price, stock_quantity, category_id)
-   **carts:** Sepetler (id, user_id)
-   **cart_items:** Sepet öğeleri (id, cart_id, product_id, quantity)
-   **orders:** Siparişler (id, user_id, total_amount, status)
-   **order_items:** Sipariş öğeleri (id, order_id, product_id, quantity, price)

## Sample Data

Proje kurulumunda otomatik olarak eklenen veriler:

-   **2 Kullanıcı:** Admin ve normal kullanıcı
-   **3 Kategori:** Elektronik, Giyim, Ev & Yaşam
-   **15 Ürün:** Her kategoride 5 ürün

## API Dokümantasyonu

Swagger UI üzerinden interaktif API dokümantasyonuna erişebilirsiniz:

**URL:** http://localhost:8000/api/documentation

Swagger UI'de:

-   Tüm endpoint'leri görebilirsiniz
-   "Try it out" ile direkt test edebilirsiniz
-   Request/Response örneklerini görebilirsiniz
-   "Authorize" butonu ile token ekleyebilirsiniz

## Komutlar

### Migration Çalıştırma

```bash
docker-compose exec php php artisan migrate
```

### Migration Geri Alma

```bash
docker-compose exec php php artisan migrate:rollback
```

### Seeders Çalıştırma

```bash
docker-compose exec php php artisan db:seed
```

### Swagger Dokümantasyonu Güncelleme

```bash
docker-compose exec php php artisan l5-swagger:generate
```

### Container Loglarını Görüntüleme

```bash
docker-compose logs -f php
```

### Container'a Bağlanma

```bash
docker-compose exec php bash
```

## Proje Yapısı

```
php/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # API Controller'ları
│   │   └── Middleware/      # Middleware'ler (AdminMiddleware)
│   ├── Models/              # Eloquent Modeller
│   └── Helpers/             # Helper sınıfları (ResponseHelper)
├── database/
│   ├── migrations/          # Veritabanı migration'ları
│   └── seeders/             # Seed dosyaları
├── routes/
│   └── api.php              # API route tanımları
├── docker-compose.yml       # Docker Compose yapılandırması
└── Dockerfile               # PHP-FPM Dockerfile
```

## Sorun Giderme

### Container'lar çalışmıyor

```bash
docker-compose down
docker-compose up -d --build
```

### Veritabanı bağlantı hatası

-   PostgreSQL container'ının çalıştığından emin olun: `docker-compose ps`
-   `.env` dosyasındaki DB bilgilerini kontrol edin

### Migration hatası

```bash
docker-compose exec php php artisan migrate:fresh --seed
```

## Lisans

Bu proje case study amaçlı geliştirilmiştir.
