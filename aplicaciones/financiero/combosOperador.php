<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCertificadoCalidad.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCertificadoCalidad();
$cct = new ControladorCatalogos();

$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$inspector = htmlspecialchars ($_POST['inspectores'],ENT_NOQUOTES,'UTF-8');
$condicion = htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8');
$estadoActual = htmlspecialchars ($_POST['estadoActual'],ENT_NOQUOTES,'UTF-8');

$provincia = ($_POST['provincia']==''?$_SESSION['nombreProvincia']:$_POST['provincia']);

$tamanio = ($estado != 'verificacion'?'76':'86');


switch ($tipoSolicitud){
	
	case 'Operadores':
		
		if($estado == 'pago'){
			$qOperadores = $cr->obtenerSolicitudesOperadores($conexion, $provincia, $estado, 'OPERADORES', $estadoActual, $tipoSolicitud);
		}else if ($estado == 'verificacionVUE'){
			$estado = 'verificacion';
			$qOperadores = $cr->obtenerSolicitudesOperadoresXOrdenPago($conexion, $provincia, $estado, $tipoSolicitud);
			
		}else{
			$qOperadores = $cr->obtenerSolicitudesOperadores($conexion, $provincia, $estado, 'OPERADORES', $estadoActual, $tipoSolicitud);
		}
			
			
		
	break;
	
	case 'operadoresLaboratorios':
		
		$idLaboratorio = pg_fetch_assoc($cct->buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $provincia));
		$provincia = $cct->obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorio['id_laboratorio_diagnostico']);
	
		$qOperadores = $cr->obtenerSolicitudesOperadores($conexion, $provincia, $estado, 'OPERADORES', $estadoActual, $tipoSolicitud);
		
	
	break;
	
	
	case 'certificadoCalidad':
		
		if($estado == 'enviado' || $estado == 'pago' || $estado == 'inspeccion' || $estado == 'verificacion'){
			$qOperadores = $cc->obtenerSolicitudesCertificadoCalidad($conexion, $provincia, "'$estado'", 'OPERADORES');	
		}else{
			$qOperadores = $cc->obtenerSolicitudesCertificadoCalidadRS($conexion, $estado, $inspector, $tipoSolicitud, $condicion, 'OPERADORES');
		}	
		
	break;

	default:
		echo 'Tipo desconocido';
}

	
	echo '
				<label>Operador</label>
					<select id="identificadorOperador" name="identificadorOperador" style="width:'.$tamanio.'%;">
					<option value="">Seleccione....</option>';
	while ($fila = pg_fetch_assoc($qOperadores)){
		echo '<option value="'.$fila['identificador'].'">'.$fila['nombre_operador'].'</option>';
	}
	
	echo '</select>';


?>

<script type="text/javascript">
	 
</script>
