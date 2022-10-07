<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
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

        <form id = 'formulario' action="<?php echo URL."Laboratorios/FirmasElectronicas/activar"; ?>" method="post">
            <div id="main">
                <p>
                <h1>Área restringida únicamente para funcionarios con firma electrónica</h1>
                </p>
                 Estimado/a <strong><?php echo $this->nombreUsuario; ?> :</strong><p>La contraseña de la firma electrónica es personal e intransferible, su uso indebido puede ocasionarle las acciones legales correspondientes. Por su seguridad se le ha dirigido a esta sección restringida del sistema, donde su clave otorgada será encriptada y almacenada en una base de datos de forma segura impidiendo ser vista por otra persona.</p>

                <p>
                <h3>Proceso de activación</h3>
                </p>

                <div id="contenedor">

                    <div>Ingrese la contraseña de la Firma Electrónica, registrada en la autoridad certificadora.</div>
                    <input class="input_texto" type="password" id="contrasena1" name="contrasena1" placeholder="Escribir contraseña" required>
                    <div class="ayuda">* Esta contraseña será encriptada y guardada en la base de datos.</div>
                </div>
                <div>Repetir contraseña para verificación</div>
                <input class="input_texto" type="password" id="contrasena2" name="contrasena2" placeholder="Escribir nuevamente contraseña" required>
                <div class="ayuda">* Necesario para validar.</div>
                <div id="resutladoVerificacion"></div>
<input type ="hidden" name="cedula" id="cedula" 
                   value ="<?php echo $this->cedulaUsuario; ?>">
                <div class="boton">
<button type="submit" id="enviar" name="enviar">Activar firma electrónica</button>
                  
                </div>
                <div id="resultadoMail"></div>
                <a href="https://www.eci.bce.ec/marco-normativo" target="_blank" style="float:right;color:#009;">Normativa de firma electrónica vigente
                </a>
            </div>

        </form>
        <footer>
	    	<span class="pie">&copy; El Sistema Gestionador Unificado de Información para Agrocalidad - GUIA es un SII (Sistema de Información Integrado).</span>
	    </footer>
        
    </body>
    <script src="<?php echo URL_GUIA ?>/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="<?php echo URL_GUIA ?>/general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
	<script src="<?php echo URL_GUIA ?>/general/funciones/agrdbfunc.js" type="text/javascript"></script>
	<script src="<?php echo URL_GUIA ?>/general/funciones/jquery.inputmask.js" type="text/javascript"></script>
    <script type ="text/javascript">
                        
         
            
        </script>
</html>