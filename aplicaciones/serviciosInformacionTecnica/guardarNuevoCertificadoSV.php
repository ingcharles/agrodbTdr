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
        $pais= htmlspecialchars ($_POST['pais'],ENT_NOQUOTES,'UTF-8');
        $certificado = $_POST['certificado'];;        
        $fecha = $_POST['fechaIngreso'];
        $rutaCertificado=$_POST['rutaCertificado'];
        $cargo=$_POST['cargo'];
        $funcionario=$_POST['funcionario'];
        $firma=$_POST['rutaFirma'];
        $estado=$_POST['estadoFirma'];
        
        $conexion->ejecutarConsulta("begin;");
        
        $certificadoResultado=$controladorInformacion->obtenerCertificadoPorTipoYPais($conexion, $certificado, $pais);
        
        if(pg_num_rows($certificadoResultado)<=0){
       
            $idCertificado=pg_fetch_row($controladorInformacion->guardarCertificado($conexion, $certificado, $pais, $fecha, $rutaCertificado));
            
            $fecha_registro = date('d-m-Y H:i:s');        
			if(count($cargo)>0){
				$guardarDetalle="";
				for($i=0; $i<count($cargo);$i++){
					$estadoFirma = $estado[$i] == 'on' ? "1" : "0"; 
					$guardarDetalle.= "('".$usuario ."','".$cargo[$i] ."','".$funcionario[$i]."','".$firma[$i]."','".$estadoFirma."','".$fecha_registro."','".$idCertificado[0]."'),";
				}
				
				$trim = rtrim($guardarDetalle,",");        
			   
				$idFirma=$controladorInformacion->guardarFirmasDetalle($conexion,$trim);
				
				$arrayResultado=Array();
				while($fila=pg_fetch_assoc($idFirma)){
					$arrayResultado[]=$fila['id_firma'];
				}
				
				$guardarDetalle="";
				for($i=0; $i<count($cargo);$i++){
					$estadoFirma = $estado[$i] == 'on' ? "1" : "0";
					$guardarDetalle.= "('".$arrayResultado[$i]."','".$idCertificado[0]."','".$usuario ."','".$cargo[$i] ."','".$funcionario[$i]."','".$firma[$i]."','".$estadoFirma."','".$fecha_registro."'),";            
				}
				
				$trim ="";
				$trim = rtrim($guardarDetalle,",");    
				
				$controladorInformacion->guardarFirmasDetalleHistorial($conexion,$trim);
            }
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos han sido guardados satisfactoriamente!';
        } else{
            $fila=pg_fetch_assoc($certificadoResultado);
            $mensaje['estado'] = 'fallo';
            $mensaje['mensaje'] = 'Ya se encuentra cargado actualmente un certificado para el pais '.$fila['pais'];
        }
        
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