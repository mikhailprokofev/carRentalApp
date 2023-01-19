# Eloquent
php artisan migrate:refresh

# Pint
php vendor/bin/pint --config ./config/pint/pint.json

# Swagger OpenAPI
php artisan l5-swagger:generate

# Queue start
php artisan queue:work

# Seeder
php artisan migrate:fresh --seed
