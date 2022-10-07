<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorCertificadoFito.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$crs = new ControladorRevisionSolicitudesVUE();
$ccf = new ControladorCertificadoFito();
$cce = new ControladorCertificados();
$cca = new ControladorCatalogos();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$res = $ccf->abrirSolicitud($conexion, $idSolicitud);
$filaSolicitud = pg_fetch_assoc($res);

$estadoActual = $filaSolicitud['estado_certificado'];

if($estadoActual == 'verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'certificadoFito', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'certificadoFito');
}



if($condicion == 'pago'){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_solicitante'].'-'.$estadoActual.'-certificadoFito-tarifarioNuevo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_solicitante'].'-'.$estadoActual.'-certificadoFito-tarifarioNuevo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_solicitante'].'-pago-certificadoFito-tarifarioAntiguo-'.$filaSolicitud['forma_pago'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_solicitante'].'-'.$estadoActual.'-certificadoFito-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}

?>

<header>
	<h1>Solicitud de Certificado Fitosanitario</h1>
</header>

	<fieldset>
		<legend>Datos Operador</legend>
		<div data-linea="2">
			<label>Identificador: </label> <?php echo $filaSolicitud['identificador_solicitante']; ?>
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
			<label>Forma de Pago: </label> <?php echo ucfirst($filaSolicitud['forma_pago']); ?>
		</div>

		<div data-linea="8">
			<label>Descuento: </label> <?php echo $filaSolicitud['descuento']; ?>
		</div>

		<div data-linea="9">
			<label>Motivo de Descuento: </label> <?php echo $filaSolicitud['descuento'] = 'No' ? 'N/A' : $filaSolicitud['motivo_descuento']; ?>
		</div>	
	</fieldset>

	<!-- mostrar tabla con los sitios registrados en la solicitud y su estado -->
	<fieldset>
		<legend>Productos a Enviar</legend>
		<div data-linea="6">
			<table id="tbExportadoresProductos" style="width: 100%">
				<thead>
					<tr>
						<th style="width: 5%;">Nº</th>
						<th style="width: 15%;">Nombre Producto</th>
						<th style="width: 15%;">Cantidad Comercial</th>
						<th style="width: 15%;">Peso Bruto</th>
						<th style="width: 15%;">Peso Neto</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$res = $ccf->obtenerDetalleExportadoresProductos($conexion, $idSolicitud);
				$i = 1;				
				
				while ($fila = pg_fetch_assoc($res)){
				    
				    $qCantidadComercial = pg_fetch_assoc($cca->obtenerUnidadMedida($conexion, $fila['id_unidad_cantidad_comercial']));
				    $codigoCantidadComercial = $qCantidadComercial['codigo'];
				    
				    if(isset($fila['id_unidad_peso_bruto'])){
				        $qPesoBruto = pg_fetch_assoc($cca->obtenerUnidadMedida($conexion, $fila['id_unidad_peso_bruto']));
				        $codigoPesoBruto = $qPesoBruto['codigo'];
				    }
				    
				    $qPesoNeto = pg_fetch_assoc($cca->obtenerUnidadMedida($conexion, $fila['id_unidad_peso_neto']));
				    $codigoPesoNeto = $qPesoNeto['codigo'];
				    
					echo '<tr>' . 
							'<td>' . $i ++ . '</td>' . 
							'<td>' . $fila['nombre_producto'] . '</td>' . 
							'<td>' . ($fila['cantidad_comercial']!='' ? $fila['cantidad_comercial'] . ' '. $codigoCantidadComercial : 'N/A'). '</td>' . 
							'<td>' . ($fila['peso_bruto']!='' ? $fila['peso_bruto'] . ' '. $codigoPesoBruto : 'N/A'). '</td>' . 
							'<td>' . ($fila['peso_neto']!='' ? $fila['peso_neto'] . ' '. $codigoPesoNeto : 'N/A'). '</td>' . 
						  '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>

	</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});
	
</script>