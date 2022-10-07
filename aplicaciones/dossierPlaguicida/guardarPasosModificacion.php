<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$cg=new ControladorDossierPlaguicida();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_modificacion']=$id_solicitud;
	$dato['nivel']=intval($_POST['nivel']);
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=false;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){
			case "P1":
				$dato['tipo_modificacion'] = htmlspecialchars ($_POST['tipo_modificacion'],ENT_NOQUOTES,'UTF-8');
				$dato['registro'] = $ce->normalizarBoolean($_POST['registro']);
				$esGuardar=true;
				break;
			case "P2":
				$dato['precaucion_uso'] = htmlspecialchars ($_POST['precaucion_uso'],ENT_NOQUOTES,'UTF-8');
				$dato['almacenamieno_manejo'] = htmlspecialchars ($_POST['almacenamieno_manejo'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_ingestion'] = htmlspecialchars ($_POST['aux_ingestion'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_telefono'] = htmlspecialchars ($_POST['aux_telefono'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_disposicion'] = htmlspecialchars ($_POST['aux_disposicion'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_ambiente'] = htmlspecialchars ($_POST['aux_ambiente'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_instrucciones'] = htmlspecialchars ($_POST['aux_instrucciones'],ENT_NOQUOTES,'UTF-8');
				$dato['frecuencia_aplicacion'] = htmlspecialchars ($_POST['frecuencia_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['responsabilidad'] = htmlspecialchars ($_POST['responsabilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['paraquat'] = htmlspecialchars ($_POST['paraquat'],ENT_NOQUOTES,'UTF-8');
				$dato['tiene_hoja_informativa'] = $ce->normalizarBoolean($_POST['tiene_hoja_informativa']);
				$dato['hoja_informativa'] = htmlspecialchars ($_POST['hoja_informativa'],ENT_NOQUOTES,'UTF-8');
				$dato['hoja_informativa_ref'] = htmlspecialchars ($_POST['hoja_informativa_ref'],ENT_NOQUOTES,'UTF-8');
				$esGuardar=true;
				break;
			case "P3":
				$dato['ruc_nuevo'] = htmlspecialchars ($_POST['ruc_nuevo'],ENT_NOQUOTES,'UTF-8');
				$dato['ruta_1'] = htmlspecialchars ($_POST['rutaArchivo_1'],ENT_NOQUOTES,'UTF-8');
				$dato['ruta_2'] = htmlspecialchars ($_POST['rutaArchivo_2'],ENT_NOQUOTES,'UTF-8');
				$dato['ruta_3'] = htmlspecialchars ($_POST['rutaArchivo_3'],ENT_NOQUOTES,'UTF-8');
				$esGuardar=true;
				break;
			case "P4":

				break;

			case "P10":
				$archivo = htmlspecialchars ($_POST['rutaArchivo'],ENT_NOQUOTES,'UTF-8');
				$referencia = htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8');
				$fase = htmlspecialchars ($_POST['fase'],ENT_NOQUOTES,'UTF-8');
				$tipoArchivo=htmlspecialchars ($_POST['tipoArchivo'],ENT_NOQUOTES,'UTF-8');
				$cg->agregarArchivoAnexo($conexion, $id_solicitud,$archivo,$referencia,$fase,$identificador,$tipoArchivo);
				$mensaje['datos']=$cg->imprimirArchivosAnexos($conexion, $id_solicitud);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El documento ha sido cargado.';

				break;
			case "P11":
				break;
		}
		if($esGuardar){
			$res=$cg->guardarModificaciones($conexion,$dato);
			if($res['tipo']=="insert")
				$idProtocolo = $res['resultado'][0]['id_modificacion'];
			else
				$fila=$res['resultado'];

			$mensaje['id'] = $idProtocolo;
			$mensaje['dato'] = $res['resultado'];
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido actualizada';
		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

