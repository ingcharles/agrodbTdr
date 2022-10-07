<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <META HTTP-EQUIV="REFRESH" CONTENT="5;URL=<?php echo URL; ?>">">
        <title>Activar firma electrónica</title>
        <link rel='stylesheet'
              href='<?php echo URL_GUIA ?>/publico/recuperarClave/estilos/estilo.css'>
        <link rel='stylesheet'
              href='<?php echo URL_GUIA ?>/general/estilos/jquery-ui-1.10.2.custom.css'>
        <link rel='stylesheet'
              href='<?php echo URL_GUIA ?>/general/estilos/agrodb.css'>

    </head>
    <body>
        <header>
            <div>
                <h2 class="encabezado1"><a href="#">ACTIVACIÓN DE FIRMA ELECTRÓNICA</a></h2>
                <h2 class="encabezado2"> <a href="<?php echo URL_GUIA; ?>">INICIAR SESIÓN</a></h2>
            </div>
        </header>


        <section>
            <h2 class="encabezado1"><?php echo $this->mensajeActivacion; ?></h2>

        </section> 

        <footer>
            <span class="pie">&copy; El Sistema Gestionador Unificado de Información para Agrocalidad - GUIA es un SII (Sistema de Información Integrado).</span>
        </footer>

    </body>

</html>