# استخدام صورة مبنية مسبقاً تحتوي على PHP وإعدادات خادم Apache
FROM php:8.2-apache

# تثبيت المتطلبات الأساسية ومكتبات النظام اللازمة لـ Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# تنظيف ذاكرة التخزين المؤقت لتخفيف مساحة السيرفر
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت إضافات PHP الضرورية (بما في ذلك pdo_mysql للاتصال بقاعدة بيانات Aiven)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# تفعيل وحدة إعادة الكتابة في Apache (ضرورية جداً لعمل مسارات وروابط Laravel)
RUN a2enmod rewrite

# تغيير المسار الافتراضي لخادم Apache ليقرأ من مجلد public الخاص بـ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تحميل وتثبيت مدير الحزم Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل داخل السيرفر
WORKDIR /var/www/html

# نسخ جميع ملفات مشروعك من GitHub إلى السيرفر
COPY . .

# تثبيت حزم ومكتبات المشروع
RUN composer install --no-dev --optimize-autoloader

# منح الصلاحيات اللازمة لمجلدات التخزين لتجنب أخطاء الكتابة
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تحديد المنفذ الافتراضي
EXPOSE 80
