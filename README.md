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

Cambiar la propiedad del directorio /app/log a www-data

Cambiar la propiedad del directorio /app/tmp a www-data

Instalar [KLogger](https://github.com/katzgrau/KLogger) utilizando Composer en /app/webroot/API

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


=======
# ServerClubPrepago
Club Prepago Application Server
>>>>>>> a232155af9f3b9a70bb5373d605b36f02e93a2ee
