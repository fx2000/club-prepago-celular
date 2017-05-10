# FTP Upload script for Club Prepago Invoices
# This must be run automatically to upload
# pending invoices to the Fiscal Printer's
# FTP server.

#!/bin/sh

# Define fiscal printer server parameters
server=200.124.24.218
user=prepago
pass=ClubPre2075

# Move invoice files to out directory
mv /var/www/app/invoices/*.txt /var/www/app/invoices/out/

# Upload files to FTP
ftp -v -n $server <<END_OF_SESSION
user $user $pass
prompt
lcd /var/www/app/invoices/out/
mput *.txt
bye
END_OF_SESSION

# Move invoice files to sent directory
mv /var/www/app/invoices/out/*.txt /var/www/app/invoices/sent/
