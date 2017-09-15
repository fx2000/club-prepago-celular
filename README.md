# Club Prepago Celular

Plataforma transaccional para comercialización de productos y servicios intangibles.

[Club Prepago Celular](http://www.clubprepago.com)


## Requerimientos

Linux

Apache 2.4

MySQL 5.5

PHP 5.6

## Instalación

Clonar repositorio en directorio raiz de apache

Correr install.sh con un usuario del grupo Sudo

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
