<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	try {
		$conexion = new Conexion();
		$cp=new ControladorDossierPecuario();
		$ce=new ControladorEnsayoEficacia();

	$dato['id_solicitud']=$id_solicitud;
	$dato['margen_seguridad'] = trim(htmlspecialchars ($_POST['margen_seguridad'],ENT_NOQUOTES,'UTF-8'));
	$dato['margen_seguridad_referencia'] = htmlspecialchars ($_POST['margen_seguridad_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['requiere_preparacion']=$ce->normalizarBoolean($_POST['requiere_preparacion']);
	$dato['preparacion_descripcion'] = trim(htmlspecialchars ($_POST['preparacion_descripcion'],ENT_NOQUOTES,'UTF-8'));
	$dato['preparacion_duracion'] = htmlspecialchars ($_POST['preparacion_duracion'],ENT_NOQUOTES,'UTF-8');
	$dato['preparacion_unidad'] = htmlspecialchars ($_POST['preparacion_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['farmacocinetica'] = trim(htmlspecialchars ($_POST['farmacocinetica'],ENT_NOQUOTES,'UTF-8'));
	$dato['farmacocinetica_referencia'] = htmlspecialchars ($_POST['farmacocinetica_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['farmacodinamica'] = trim(htmlspecialchars ($_POST['farmacodinamica'],ENT_NOQUOTES,'UTF-8'));
	$dato['farmacodinamica_referencia'] = htmlspecialchars ($_POST['farmacodinamica_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['efectos_colaterales'] = trim(htmlspecialchars ($_POST['efectos_colaterales'],ENT_NOQUOTES,'UTF-8'));
	$dato['efectos_colaterales_referencia'] = htmlspecialchars ($_POST['efectos_colaterales_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['sobredosis'] = trim(htmlspecialchars ($_POST['sobredosis'],ENT_NOQUOTES,'UTF-8'));
	$dato['sobredosis_referencia'] = htmlspecialchars ($_POST['sobredosis_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['toxicidad'] = trim(htmlspecialchars ($_POST['toxicidad'],ENT_NOQUOTES,'UTF-8'));
	$dato['toxicidad_referencia'] = htmlspecialchars ($_POST['toxicidad_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['tiene_categoria_toxicologica'] = $ce->normalizarBoolean($_POST['tiene_categoria_toxicologica']);
	$dato['categoria_toxicologica'] = htmlspecialchars ($_POST['categoria_toxicologica'],ENT_NOQUOTES,'UTF-8');

	$dato['nivel']=intval($_POST['nivel']);

		$res=$cp->guardarSolicitud($conexion,$dato);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
		else
			$fila=$res['resultado'];

		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La solicitud ha sido actualizada';

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

