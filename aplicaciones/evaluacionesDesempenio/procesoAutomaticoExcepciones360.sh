#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/evaluacionesDesempenio
date

/opt/lampp/bin/php procesoAutomaticoExcepciones360.php

exit

