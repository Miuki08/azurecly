set -e

mkdir -p /var/run

sed -i \
    's|listen = 127.0.0.1:9000|listen = /var/run/php82-fpm.sock|g' \
    /etc/php82/php-fpm.d/www.conf

php-fpm82 -D

exec nginx -g "daemon off;"