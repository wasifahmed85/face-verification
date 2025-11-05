# Step 1: Laravel Project তৈরি করুন
composer create-project laravel/laravel face-verification "10.*"
cd face-verification

# Step 2: প্রয়োজনীয় packages install করুন
composer require intervention/image
npm install face-api.js

# Step 3: Storage link তৈরি করুন
php artisan storage:link

# Step 4: Database migration তৈরি করুন
php artisan make:migration create_face_verifications_table
php artisan make:model FaceVerification -m

# Step 5: Controller তৈরি করুন
php artisan make:controller FaceVerificationController

# Step 6: .env ফাইল কনফিগার করুন (Database setup)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=face_verification
# DB_USERNAME=root
# DB_PASSWORD=

# Step 7: Database migrate করুন
php artisan migrate

# Step 8: Development server চালু করুন
php artisan serve
