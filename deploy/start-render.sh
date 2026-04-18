#!/usr/bin/env sh
set -eu

if ! php -r "\$key = getenv('APP_KEY') ?: ''; if (! str_starts_with(\$key, 'base64:')) { exit(1); } \$decoded = base64_decode(substr(\$key, 7), true); exit(\$decoded !== false && strlen(\$decoded) === 32 ? 0 : 1);"; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi

if [ ! -f "$DB_DATABASE" ]; then
    touch "$DB_DATABASE"
fi

php artisan migrate --force
php artisan db:seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
