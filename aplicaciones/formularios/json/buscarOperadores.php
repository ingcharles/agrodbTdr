<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorCRC.php';
require_once '../../../clases/ControladorUsuarios.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['registros'] = 0;
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorInspector = htmlspecialchars ($_POST['cedulaInspector'],ENT_NOQUOTES,'UTF-8');
	$parametroDeBusqueda = htmlspecialchars ($_POST['parametroBusqueda'],ENT_NOQUOTES,'UTF-8');
    $provinciaInspector = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'); //POR DEFINIR en TABLET
	
	try {
		$crc = new TransaccionCRC();

		if ($crc->validarCRC(true)){
			$conexion = new Conexion();
			$cf = new ControladorRegistroOperador();
            $cu = new ControladorUsuarios();
			
			/*$cc = new ControladorCatastro();
			$contrato = pg_fetch_assoc($cc->obtenerDatosContrato($conexion,$_POST['id']));
			
			$idProvinciaInspector = $contrato['provincia'];//TODO: Determinar la provincia del inspector para filtar los operadores a su provincia
			

            $localizacion = pg_fetch_assoc($cu->obtenerProvinciaUsuario($conexion, $_SESSION['usuario']));

            $_SESSION['idLocalizacion']=$localizacion['id_localizacion'];
            $_SESSION['nombreLocalizacion']=$localizacion['nombre'];
            $_SESSION['codigoLocalizacion']=$localizacion['codigo'];
            $_SESSION['idAplicacion']=$_POST["idAplicacion"];
            $_SESSION['nombreProvincia']=$provincia['nombre'];*/

            //$idProvinciaInspector =

			$operadores = $cf->jsonBuscarOperadores($conexion, $parametroDeBusqueda, $provinciaInspector);
			
			if($operadores[array_to_json] != null){
				$mensaje['estado'] = 'exito';
                //TODO: Probar con igor
                //$mensaje['registros'] = json_array_length($operadores[array_to_json]);
				$mensaje['mensaje'] = $operadores[array_to_json];
			} else {
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = null;
			}
			
			$conexion->desconectar();
		} else {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = $crc->getMensaje();
		}
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>