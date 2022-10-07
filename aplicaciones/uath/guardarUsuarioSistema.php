<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorUsuarios.php';

    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $numero = htmlspecialchars($_POST['numero'], ENT_NOQUOTES, 'UTF-8');
        $nombre = htmlspecialchars($_POST['nombre'], ENT_NOQUOTES, 'UTF-8');
        $apellido = htmlspecialchars($_POST['apellido'], ENT_NOQUOTES, 'UTF-8');
        $tipo = htmlspecialchars($_POST['tipo'], ENT_NOQUOTES, 'UTF-8');
        $mailInstitucional = htmlspecialchars($_POST['mail_institucional'], ENT_NOQUOTES, 'UTF-8');
        $mailPersonal = htmlspecialchars($_POST['mail_personal'], ENT_NOQUOTES, 'UTF-8');

        try {
            
            $conexion = new Conexion();
            $cu = new ControladorUsuarios();
            
            if(!isset($mailInstitucional)){
                $mailInstitucional = null;
            }else if (!isset($mailPersonal) ){
                $mailPersonal = null;
            }
                        
            $id = pg_fetch_row($cu->guardarUsuarioSistema($conexion, $numero, $nombre, $apellido, $tipo, $mailInstitucional, $mailPersonal));
            
            //echo 'Éxito';
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $id;
            
            $conexion->desconectar();
            echo json_encode($mensaje);
        } catch (Exception $ex){
            pg_close($conexion);
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = "Error al ejecutar sentencia ".$ex;
            echo json_encode($mensaje);
        }
    } catch (Exception $ex) {
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
        echo json_encode($mensaje);
    }
    //echo '<input type="hidden" id="' . $id[0] . '" data-rutaAplicacion="uath" data-opcion="abrirUsuarioSistema" data-destino="detalleItem"/>';
?>