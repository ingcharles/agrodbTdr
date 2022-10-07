<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorCertificacionBPA.php';

$conexion = new Conexion();
$crs = new ControladorRevisionSolicitudesVUE();
$ccb = new ControladorCertificacionBPA();
$cce = new ControladorCertificados();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$res = $ccb->abrirSolicitud($conexion, $idSolicitud);
$filaSolicitud = pg_fetch_assoc($res);

$estadoActual = $filaSolicitud['estado'];



if($estadoActual == 'verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'certificacionBPA', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'certificacionBPA');
}



if($condicion == 'pago'){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-certificacionBPA-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-certificacionBPA-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-pago-certificacionBPA-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_operador'].'-'.$estadoActual.'-certificacionBPA-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}

?>

<header>
	<h1>Solicitud de Certificación BPA</h1>
</header>

	<fieldset>
		<legend>Datos Operador</legend>
		<div data-linea="2">
			<label>Identificador: </label> <?php echo $filaSolicitud['identificador']; ?>
		</div>

		<div data-linea="3">
			<label>Nombre/Razón Social: </label> <?php echo $filaSolicitud['razon_social']; ?>
		</div>

		<hr />

		<div data-linea="4">
			<label>Identificación Representante: </label> <?php echo $filaSolicitud['identificador_representante_legal']; ?>
		</div>

		<div data-linea="5">
			<label>Representante Legal: </label> <?php echo $filaSolicitud['nombre_representante_legal']; ?>
		</div>

		<div data-linea="8">
			<label>E-mail: </label> <?php echo $filaSolicitud['correo_representante_tecnico']; ?>
		</div>

		<div data-linea="8">
			<label>Teléfono: </label> <?php echo $filaSolicitud['telefono_representante_tecnico']; ?>
		</div>

		<div data-linea="12">
			<label>Dirección: </label> <?php echo $filaSolicitud['direccion_unidad_produccion']; ?>
		</div>
		
		<div data-linea="13">
			<label>Provincia: </label> <?php echo $filaSolicitud['provincia_unidad_produccion']; ?>
		</div>
		
		<div data-linea="14">
			<label>Cantón: </label> <?php echo $filaSolicitud['canton_unidad_produccion']; ?>
		</div>
		
		<div data-linea="15">
			<label>Parroquia: </label> <?php echo $filaSolicitud['parroquia_unidad_produccion']; ?>
		</div>

	</fieldset>

	<!-- mostrar tabla con los sitios registrados en la solicitud y su estado -->
	<fieldset>
		<legend><?php echo ($filaSolicitud['es_asociacion']=='Si'?"Sitios de Miembros de la Asociación a Certificar":"Sitios, Áreas y Productos Agregados")?></legend>
		<div data-linea="6">
			<table id="tbSitiosAreasProductos" style="width: 100%">
				<thead>
					<tr>
						<th style="width: 5%;">Nº</th>
						<th style="width: 15%;">Nombre Sitio</th>
						<th style="width: 15%;">Nombre Área</th>
						<th style="width: 15%;">Producto</th>
						<th style="width: 15%;">Provincia</th>
						<th style="width: 10%;">Hectáreas</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$res = $ccb->obtenerDetalleSitiosAreasProductos($conexion, $idSolicitud);
				$i = 1;

				while ($fila = pg_fetch_assoc($res)){
					echo '<tr>' . 
							'<td>' . $i ++ . '</td>' . 
							'<td>' . $fila['nombre_sitio'] . '</td>' . 
							'<td>' . $fila['nombre_area'] . '</td>' . 
							'<td>' . $fila['nombre_producto'] . '</td>' . 
							'<td>' . $fila['nombre_provincia'] . '</td>' . 
							'<td>' . $fila['superficie'] . '</td>' . 
					'</tr>';
				}
				?>
				</tbody>
			</table>
		</div>

	</fieldset>

	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="1">
			<label>Tipo Solicitud: </label> <?php echo $filaSolicitud['tipo_solicitud']; ?>
		</div>

		<div data-linea="1">
			<label>Tipo Explotación: </label> 
			<?php echo ($filaSolicitud['tipo_explotacion']=="SV"?"Sanidad Vegetal":($filaSolicitud['tipo_explotacion']=="SA"?"Sanidad Animal":"Inocuidad de Alimentos"));?>	
		</div>

		<div data-linea="15">
			<label>Tipo de Certificado: </label> <?php echo $filaSolicitud['tipo_certificado']; ?>
		</div>

		<div data-linea="15">
			<label>Fecha de Solicitud: </label> <?php echo date('Y-m-d',strtotime($filaSolicitud['fecha_creacion'])); ?>
		</div>

		<div data-linea="25" id="contenedorAuditoria">
			<label>Tipo de Auditoría</label>
				<?php
				$res = $ccb->obtenerDetalleAuditoriasSolicitadas($conexion, $idSolicitud);

				$i = 1;

				while ($fila = pg_fetch_assoc($res)){

					echo '</br>'. $i ++ . '. ' . $fila['tipo_auditoria'] . '</br>';
				}
				?>
		</div>

	</fieldset>


<div id="ordenPago"></div>

<script type="text/javascript">

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>