<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorDossierPecuario.php';

$conexion = new Conexion();
$cce = new ControladorCertificados();
$crs = new ControladorRevisionSolicitudesVUE();
$cdpmvc = new ControladorDossierPecuario();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$res = $cdpmvc->abrirSolicitud($conexion, $idSolicitud);
$filaSolicitud = pg_fetch_assoc($res);

$estadoActual = $filaSolicitud['estado_solicitud'];

if ($estadoActual == 'verificacion') {
    $qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'dossierPecuario', 'Financiero');
    $idGrupo = pg_fetch_assoc($qIdGrupo);
}

if ($idGrupo['id_grupo'] != '') {
    $ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'dossierPecuario');
}

if ($condicion == 'pago') {
    echo '<input type="hidden" class= "abrirPago" id="' . $idSolicitud . '-' . $filaSolicitud['identificador'] . '-' . $estadoActual . '-dossierPecuario-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "' . $idGrupo['id_grupo'] . '"/>';
} else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago) != 0) {
    echo '<input type="hidden" class= "abrirPago" id="' . $idSolicitud . '-' . $filaSolicitud['identificador'] . '-' . $estadoActual . '-dossierPecuario-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "' . $idGrupo['id_grupo'] . '"/>';
} else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0) {
    echo '<input type="hidden" class= "abrirPago" id="' . $idSolicitud . '-' . $filaSolicitud['identificador'] . '-pago-dossierPecuario-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "' . $idGrupo['id_grupo'] . '"/>';
} else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0) {
    $numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
    echo '<input type="hidden" class= "abrirPago" id="' . $idSolicitud . '-' . $filaSolicitud['identificador'] . '-' . $estadoActual . '-dossierPecuario-' . $numeroOrdenPago . '" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "' . $idGrupo['id_grupo'] . '"/>';
}

?>

<header>
	<h1>Solicitud de Registro de Producto Dossier Pecuario</h1>
</header>

<fieldset>
	<legend>Información del Solicitante</legend>

	<div data-linea="1">
		<label>Nombre/Razón Social: </label> <?php echo $filaSolicitud['razon_social']; ?>
		</div>

	<div data-linea="2">
		<label>CI / RUC / RISE: </label> <?php echo $filaSolicitud['identificador']; ?>
		</div>

	<div data-linea="3">
		<label>Representante Legal: </label> <?php echo $filaSolicitud['nombre_representante'] .' ' .  $filaSolicitud['apellido_representante']; ?>
		</div>

	<div data-linea="4">
		<label>Provincia: </label> <?php echo $filaSolicitud['provincia']; ?>
		</div>

	<div data-linea="5">
		<label>Dirección: </label> <?php echo $filaSolicitud['direccion']; ?>
		</div>

	<div data-linea="6">
		<label>Teléfono: </label> <?php echo $filaSolicitud['telefono_uno']; ?>
		</div>

</fieldset>

<fieldset>
	<legend>Información del Producto</legend>

	<div data-linea="7">
		<label>Nombre del producto: </label> <?php echo $filaSolicitud['nombre_producto']; ?>
		</div>

	<div data-linea="8">
		<label>Tipo de producto: </label> <?php echo $filaSolicitud['nombre']; ?>
		</div>

	<div data-linea="9">
		<label>Clasificación: </label> <?php echo $filaSolicitud['clasificacion']; ?>
		</div>

	<div data-linea="10">
		<label>Tipo de solicitud: </label> <?php echo $filaSolicitud['tipo_solicitud']; ?>
		</div>

</fieldset>


<div id="ordenPago"></div>

<script type="text/javascript">

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>