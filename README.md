# 🧠 Laravel Face Verification System

একটি সম্পূর্ণ **Face Recognition এবং Verification System**, যা তৈরি করা হয়েছে **Laravel 10**, **face-api.js**, এবং **Intervention Image** ব্যবহার করে।  
এই প্রজেক্টের মাধ্যমে ব্যবহারকারীর মুখের ডেটা সংরক্ষণ ও যাচাই করা যাবে — যা ভবিষ্যতে secure authentication এর জন্য ব্যবহারযোগ্য।

---

## 🚀 Features

- Face image capture এবং storage  
- 128-dimensional face descriptor (JSON format)  
- Real-time face verification using `face-api.js`  
- Face registration time tracking  
- Easy Laravel integration  

---

## 🛠️ Requirements

- PHP 8.1 বা তার বেশি  
- Composer  
- Node.js & NPM  
- MySQL Database  

---

## ⚙️ Installation & Setup (সব একসাথে)

```bash
# Step 1: Laravel Project তৈরি করুন
composer create-project laravel/laravel face-verification "10.*"
cd face-verification

# Step 2: প্রয়োজনীয় packages install করুন
composer require intervention/image
npm install face-api.js

# Step 3: Storage link তৈরি করুন
php artisan storage:link

# Step 4: Database migration তৈরি করুন
# 👉 নিচের কোডটি রাখুন: database/migrations/2024_01_01_000001_add_face_data_to_users_table.php
<?php
/**
 * File: database/migrations/2024_01_01_000001_add_face_data_to_users_table.php
 * 
 * এই migration users table এ face verification এর জন্য columns যোগ করবে
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Face image এর path store করবে (storage/app/public/faces/)
            $table->string('face_image')->nullable()->after('password');
            
            // Face descriptor - 128 dimensional array JSON format এ
            $table->text('face_descriptor')->nullable()->after('face_image');
            
            // User এর face verify করা আছে কিনা
            $table->boolean('face_verified')->default(false)->after('face_descriptor');
            
            // Face registration এর timestamp
            $table->timestamp('face_registered_at')->nullable()->after('face_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'face_image',
                'face_descriptor', 
                'face_verified',
                'face_registered_at'
            ]);
        });
    }
};
# Step 5: Controller তৈরি করুন
php artisan make:controller FaceVerificationController

# Step 6: .env ফাইল কনফিগার করুন (Database setup)
# নিচের মতো সেট করুন:DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=face_verification
DB_USERNAME=root
DB_PASSWORD=
# Step 7: Database migrate করুন
php artisan migrate

# Step 8: Development server চালু করুন
php artisan serve


face-verification/
├── app/
│   ├── Http/Controllers/FaceVerificationController.php
│   └── Models/User.php
├── database/
│   └── migrations/
│       └── 2024_01_01_000001_add_face_data_to_users_table.php
├── public/
│   └── storage/faces/
└── resources/
    ├── views/
    └── js/
        └── face-api.js integration

