#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/catastroProducto 
date
/opt/lampp/bin/php procesoCatastroAutomatico.php
exit

