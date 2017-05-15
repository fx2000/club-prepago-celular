# Club Prepago Celular Venezuela

Plataforma transaccional para comercialización de productos y servicios intangibles.

[Club Prepago Celular](http://www.clubprepago.com)


## Requerimientos

Linux

Apache 2.4

MySQL 5.5

PHP 5.6

## Instalación

Clonar repositorio en directorio raiz de apache

Cambiar la propiedad de los directorios /app/tmp, /app/log, /app/invoices, /app/webroot/uploads y /app/webroot/img/rewards a www-data (recursivo)

Correr "composer update" en directorio /app/webroot/API

Configurar acceso a MySQL en /app/Config/database.php

Cambiar la configuración de Apache:

```
<Directory /var/www/>
     Options FollowSymLinks
     AllowOverride All
     Require all granted
</Directory>
```

Activar `a2enmod rewrite`
