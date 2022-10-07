<?php

session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$opcion = $_POST['opcion'];

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

switch($opcion){

	case 'cargarDatosMiembro':
		
		$datosMiembro =  htmlspecialchars ($_POST['datosDetalle'],ENT_NOQUOTES,'UTF-8');
		list($identMiembro, $nombMiembro, $apellMiembro, $codMagap) = explode("-", $datosMiembro);
				
		echo '<input type="hidden" value="'.$identMiembro.'" id="identificadorMiembroAnterior" name="identificadorMiembroAnterior" />
			<input type="hidden" value="'.$nombMiembro.'" id="nombreMiembroAnterior" name="nombreMiembroAnterior" />
			<input type="hidden" value="'.$apellMiembro.'" id="apellidoMiembroAnterior" name="apellidoMiembroAnterior" />
			<input type="hidden" value="'.$codMagap.'" id="codigoMagapAnterior" name="codigoMagapAnterior" />';		
		
		break;
	
	default:
		
		$datos =  htmlspecialchars ($_POST['datosDetalle'],ENT_NOQUOTES,'UTF-8');
		list($identSitio, $identMiembro) = explode("-", $datos);
		
		$qExisteSitio = $cro -> buscarExisteSitio($conexion, $identSitio);
		
		if(pg_num_rows($qExisteSitio)!=0){
		
			$qDuenioSitio = $cro -> buscarMiembroDuenioSitio($conexion, $identSitio);
			$duenioSitio = pg_fetch_assoc($qDuenioSitio);
				
			if ($duenioSitio['identificador_miembro_asociacion'] == $identMiembro){
				echo "true";
			}else{
				echo "false";
			}
		}

}

?>