<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$usuario=$_SESSION['usuario'];


try{
    $conexion = new Conexion();
    $controladorInformacion = new ControladorServiciosInformacionTecnica();
    
    try {        
        $rutaCertificado = $_POST['rutaCertificado'];        
        $cargo=$_POST['cargo'];
        $funcionario=$_POST['funcionario'];
        $firma=$_POST['rutaFirma'];
        $estado=$_POST['estadoFirma'];
        $idFirma=$_POST['firma'];
        $idCertificado= $_POST['idCertificado'];
        
        $nuevoCargo= $_POST['nuevoCargo'];
        $nuevoFuncionario = $_POST['nuevoFuncionario'];
        $nuevoRutaFirma = $_POST['nuevoRutaFirma'];
        $nuevoEstadoFirma = $_POST['nuevoEstadoFirma'];
        
        $conexion->ejecutarConsulta("begin;");       
        
        $fecha_registro = date('d-m-Y H:i:s');        
       
        //actualizar certificados
        
        if($rutaCertificado !='0'){
            $controladorInformacion->actualizarCertificado($conexion,$idCertificado,$rutaCertificado); 
        }
        
        if(count($cargo)>0){
            $guardarDetalle="";
            for($i=0; $i<count($idFirma);$i++){
                $estadoFirma = $estado[$i] == 'on' ? "1" : "0"; 
                $guardarDetalle.= "(".$idFirma[$i].",'".$usuario ."','".$cargo[$i] ."','".$funcionario[$i]."','".$firma[$i]."',".$estadoFirma.",'".$fecha_registro."'),";            
                $guardarHistorial.= "('".$idFirma[$i]."','".$idCertificado."','".$usuario ."','".$cargo[$i] ."','".$funcionario[$i]."','".$firma[$i]."','".$estadoFirma."','".$fecha_registro."'),";
            }
            
            $trim = rtrim($guardarDetalle,",");    
            $controladorInformacion->actualizarFirmas($conexion,$trim);
            
            
            $trim ="";
            $trim = rtrim($guardarHistorial,",");
            
            $controladorInformacion->guardarFirmasDetalleHistorial($conexion,$trim);
        }
        //fin actualizar certificados
        
        //nuevos certificados 
        $guardarDetalle="";
        
        if(count($nuevoCargo)>0){
            
            for($i=0; $i<count($nuevoCargo);$i++){
                $estadoFirma = $nuevoEstadoFirma[$i] == 'on' ? "1" : "0";
                $guardarDetalle.= "('".$usuario ."','".$nuevoCargo[$i] ."','".$nuevoFuncionario[$i]."','".$nuevoRutaFirma[$i]."','".$estadoFirma."','".$fecha_registro."','".$idCertificado."'),";
            }
            
            $trim = rtrim($guardarDetalle,",");
            
            $idFirma=$controladorInformacion->guardarFirmasDetalle($conexion,$trim);
            
            $arrayResultado=Array();
            while($fila=pg_fetch_assoc($idFirma)){
                $arrayResultado[]=$fila['id_firma'];
            }
            
            $guardarDetalle="";
            for($i=0; $i<count($nuevoCargo);$i++){
                $estadoFirma = $nuevoEstadoFirma[$i] == 'on' ? "1" : "0";
                $guardarDetalle.= "('".$arrayResultado[$i]."','".$idCertificado."','".$usuario ."','".$nuevoCargo[$i] ."','".$nuevoFuncionario[$i]."','".$nuevoRutaFirma[$i]."','".$estadoFirma."','".$fecha_registro."'),";
            }
            
            $trim ="";
            $trim = rtrim($guardarDetalle,",");
            
            $controladorInformacion->guardarFirmasDetalleHistorial($conexion,$trim);
            
        }
        // fin nuevos certificados
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Los datos han sido guardados satisfactoriamente!';
        
        $conexion->ejecutarConsulta("commit;");       
        
     
        
    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError.$ex;
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}

?>