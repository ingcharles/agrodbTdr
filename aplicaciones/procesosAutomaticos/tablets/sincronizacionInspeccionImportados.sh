#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/procesosAutomaticos/tablets/
date
/opt/lampp/bin/php sincronizacionInspeccionImportados.php
exit

