#!/bin/sh


PATH=$PATH:$HOME/bin/:/opt/lampp/bin/

export PATH

cd /opt/lampp/htdocs/agrodbPrueba/aplicaciones/procesosAutomaticos/mail/
date

/opt/lampp/bin/php enviarCorreo.php

exit

