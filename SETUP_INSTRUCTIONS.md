# Kurulum Talimatları

## Gereksinimler

### 1. PHP 8.0+ Kurulumu

**Windows için:**
1. https://windows.php.net/download/ adresinden PHP indirin
2. PHP 8.0 veya üzeri sürüm seçin (Thread Safe veya Non-Thread Safe)
3. İndirilen zip dosyasını `C:\php` klasörüne çıkarın
4. Sistem değişkenlerine ekleyin:
   - Windows tuşu + "environment variables" yazın
   - "Path" değişkenini düzenleyin
   - `C:\php` ekleyin

**Alternatif: XAMPP (Önerilen - Kolay)**
1. https://www.apachefriends.org/ adresinden XAMPP indirin
2. Kurulum yapın (PHP dahil gelir)
3. PHP genellikle `C:\xampp\php` konumunda olur
4. PATH'e `C:\xampp\php` ekleyin

### 2. Composer Kurulumu

1. https://getcomposer.org/download/ adresine gidin
2. "Composer-Setup.exe" indirin ve kurun
3. Kurulum sırasında PHP yolunu seçin (XAMPP kullandıysanız `C:\xampp\php\php.exe`)

### 3. PostgreSQL Kurulumu

1. https://www.postgresql.org/download/windows/ adresinden PostgreSQL indirin
2. Kurulum yapın (şifre belirleyin - hatırlayın!)
3. pgAdmin4 ile veritabanı oluşturabilirsiniz

## Kurulum Kontrolü

Kurulumdan sonra terminalde şu komutları çalıştırın:

```bash
php --version
composer --version
```

Her ikisi de versiyon bilgisi göstermeli.

## Sonraki Adım

Kurulum tamamlandıktan sonra bana "kurulum tamam" yazın, Laravel projesini oluşturalım.

