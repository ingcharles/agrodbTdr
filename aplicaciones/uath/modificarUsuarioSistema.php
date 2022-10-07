<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorUsuarios.php';
    require_once '../../clases/ControladorEmpleados.php';
    require_once '../../clases/ControladorCatastro.php';
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    try{
        $identificador = htmlspecialchars($_POST['numero'], ENT_NOQUOTES, 'UTF-8');
        $estado = htmlspecialchars($_POST['estado'], ENT_NOQUOTES, 'UTF-8');
        $mailInstitucional = htmlspecialchars($_POST['mail_institucional'], ENT_NOQUOTES, 'UTF-8');
        $mailPersonal = htmlspecialchars($_POST['mail_personal'], ENT_NOQUOTES, 'UTF-8');
    
        try {
            $conexion = new Conexion();
            $cu = new ControladorUsuarios();
            $cc = new ControladorCatastro();
            $ce = new ControladorEmpleados();
        
            $cu->modificarEstadoUsuarioSistema($conexion, $identificador, $estado);
            $ce->modificarCorreosUsuarioSistema($conexion, $identificador, $mailInstitucional, $mailPersonal);
          	$cc->enviarEstadoEstructuraFuncionario($conexion, $identificador, $estado);
           	
            if($estado == 0){
            	$cc->inactivarTodosContratos($conexion, $identificador, 'Contratos eliminados por inactivación de usuario del sistema.', 4);
            	$cc->inactivarMinutosDisponibles($conexion, $identificador, 'False');
            	$cc->enviarEstadoFuncionario($conexion, $identificador);
            }else{
            	$cc->enviarEstadoFuncionario($conexion, $identificador, 'activo');
            }
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Se ha actualizado la información del usuario';
            
            $conexion->desconectar();
            echo json_encode($mensaje);
            
        } catch (Exception $ex){
            pg_close($conexion);
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = "Error al ejecutar sentencia ".$ex;
            echo json_encode($mensaje);
        }
    ;
    } catch (Exception $ex) {
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
        echo json_encode($mensaje);
    }
?>