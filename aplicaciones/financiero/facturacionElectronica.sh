#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/financiero
date
/opt/lampp/bin/php facturacionElectronica.php
exit

