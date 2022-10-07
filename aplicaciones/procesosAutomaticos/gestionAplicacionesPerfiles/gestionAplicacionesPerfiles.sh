#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/procesosAutomaticos/gestionAplicacionesPerfiles 
date
/opt/lampp/bin/php gestionAplicacionesPerfiles.php
exit

