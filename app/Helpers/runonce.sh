#!/bin/bash
\cp /var/www/laravel/config/httpd/php.ini /etc/php/8.2/cli/php.ini

# Override ldap-record SSL
/usr/bin/mkdir -p /etc/ldap
/usr/bin/echo "TLS_CACERT /etc/ssl/certs/ca-certificates.crt" > /etc/ldap/ldap.conf
