#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/notificacionesCFE/
date

/opt/lampp/bin/php actualizarEstadoSancionCFE.php

exit

