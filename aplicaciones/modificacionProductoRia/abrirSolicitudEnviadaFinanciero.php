<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorModificacionProductoRia.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cce = new ControladorCertificados();
$crs = new ControladorRevisionSolicitudesVUE();
$cmp = new ControladorModificacionProductoRia();
$cca = new ControladorCatalogos();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$res = $cmp->abrirSolicitud($conexion, $idSolicitud);
$filaSolicitud = pg_fetch_assoc($res);

$estadoActual = $filaSolicitud['estado_solicitud_producto'];

if($estadoActual == 'verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'modificacionProductoRia', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'modificacionProductoRia');
}



if($condicion == 'pago'){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-modificacionProductoRia-tarifarioNuevo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-modificacionProductoRia-tarifarioNuevo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-pago-modificacionProductoRia-tarifarioAntiguo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-modificacionProductoRia-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}

?>

<header>
	<h1>Solicitud de Mdificación de productos</h1>
</header>

	<fieldset>
		<legend>Datos Operador</legend>
		<div data-linea="2">
			<label>Identificador: </label> <?php echo $filaSolicitud['identificador_operador']; ?>
		</div>

		<div data-linea="3">
			<label>Nombre/Razón Social: </label> <?php echo $filaSolicitud['razon_social']; ?>
		</div>

		<hr />

		<div data-linea="5">
			<label>Representante Legal: </label> <?php echo $filaSolicitud['nombre_representante'] .' ' .  $filaSolicitud['apellido_representante']; ?>
		</div>

		<div data-linea="6">
			<label>E-mail: </label> <?php echo $filaSolicitud['correo']; ?>
		</div>

		<div data-linea="6">
			<label>Teléfono: </label> <?php echo $filaSolicitud['telefono_uno']; ?>
		</div>

		<div data-linea="7">
			<label>Dirección: </label> <?php echo $filaSolicitud['direccion']; ?>
		</div>


	</fieldset>
	
	<fieldset>
		<legend>Forma de Pago Solicitada</legend>
		<div data-linea="8">
			<label>Forma de Pago: </label> efectivo
		</div>

		<div data-linea="8">
			<label>Descuento: </label> <?php echo ($filaSolicitud['descuento'] != null) ? $filaSolicitud['descuento'] : 'N/A'; ?>
		</div>

		<div data-linea="9">
			<label>Motivo de Descuento: </label> <?php echo ($filaSolicitud['descuento'] == 'Si') ? 'Persona natural de la tercera edad o artesano' : 'N/A'; ?>
		</div>	
	</fieldset>

	<!-- mostrar tabla con los sitios registrados en la solicitud-->
	<fieldset>
		<legend>Producto a modificar</legend>		
		<?php
		$qSolicitudProducto = $cmp->abrirInformacionGeneralSolicitudPorIdSolicitud($conexion, $idSolicitud);
		$solicitudProducto = pg_fetch_assoc($qSolicitudProducto);
		echo '<div data-linea="1">
		<label>Área: </label>' .$solicitudProducto['nombre_area']. '
		</div>
        <div data-linea="2">
		<label>Tipo producto: </label>' .$solicitudProducto['nombre_tipo_producto']. '
		</div>
        <div data-linea="3">
		<label>Subtipo producto: </label>' .$solicitudProducto['nombre_subtipo_producto']. '
		</div>
		<div data-linea="4">
		<label>Producto: </label>' .$solicitudProducto['nombre_producto']. '
        </div>
        <div data-linea="5">
		<label>Número de registro: </label>' .$solicitudProducto['numero_registro']. '
        </div>';

		?>


	</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});
	
</script>