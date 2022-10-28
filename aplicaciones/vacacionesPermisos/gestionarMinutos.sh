#!/bin/sh


PATH=$PATH:$HOME/bin/:/usr/bin/

export PATH

cd /var/www/html/agrodb/aplicaciones/vacacionesPermisos
date
/usr/bin/php gestionarMinutos.php 
 
exit
