# Install script for Club Prepago Celular
# This must be run after cloning the master
# repository to update dependencies and
# set proper folder permissions

#!/bin/sh

# Setting foder permissions
chown -R www-data:www-data app/tmp app/log app/invoices app/webroot/uploads app/webroot/img/rewards app/webroot/img/resellers

# Updating dependencies
(cd app/webroot/APIConfig && exec composer update)
