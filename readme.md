php bin/console doctrine:schema:create
php bin/console doctrine:schema:update

php bin/console messenger:consume async -vv

php bin/console rabbitmq:consumer task

# Pint
pint --config rental_app/config/pint/pint.json
