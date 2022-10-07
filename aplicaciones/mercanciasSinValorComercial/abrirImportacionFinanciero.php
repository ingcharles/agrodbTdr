<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$cce = new ControladorCertificados();
$crs = new ControladorRevisionSolicitudesVUE();
$ce = new ControladorMercanciasSinValorComercial();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$res=$ce->obtenerSolicitud($conexion, $idSolicitud);
$filaSolicitud=pg_fetch_assoc($res);

$estadoActual = $filaSolicitud['estado'];

if($estadoActual == 'verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'mercanciasSinValorComercialImportacion', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'mercanciasSinValorComercialImportacion');
}

if($condicion == 'pago'){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_propietario'].'-'.$estadoActual.'-mercanciasSinValorComercialImportacion-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_propietario'].'-'.$estadoActual.'-mercanciasSinValorComercialImportacion-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_propietario'].'-pago-mercanciasSinValorComercialImportacion-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago"  data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$filaSolicitud['identificador_propietario'].'-'.$estadoActual.'-mercanciasSinValorComercialImportacion-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}

?>

<header>
	<h1>Importación de mascotas</h1>
</header>

<?php
	echo '<fieldset>
			<legend>Datos del Propietario:</legend>';
				if($filaSolicitud['identificador_propietario']==""){
					echo '<div data-linea="1"><label>No existen datos del propietario</label></div>';
				} else{				
					echo'<div data-linea="2"><label for="identificacionPropietario">Identificación: </label>'.$filaSolicitud['identificador_propietario'].'</div>'.
						'<div data-linea="1"><label for="nombrePropietario">Nombre: </label>'.$filaSolicitud['nombre_propietario'].'</div>'.
						'<div data-linea="3"><label for="direccionPropietario">Dirección: </label>'.$filaSolicitud['direccion_propietario'].'</div>';
				}
		echo'</fieldset>';
	?>
	
	<fieldset>
		<legend>Datos Generales:</legend>
		<div data-linea="2">
			<label for="pais">País Origen</label>
			<?php
				echo $filaSolicitud['pais_origen_destino'];
			?>
		</div>
		<div data-linea="2" id="resultadoEmbarque">
			<label for="puertoEmbarque">Puerto de Embarque:</label>
			<?php
				echo $filaSolicitud['nombre_puerto'];
			?>
		</div>	
		<div data-linea="4"> 
			<label for=residencia>Dirección Ecuador: </label>
			<?php
				echo $filaSolicitud['direccion_ecuador'];
			?>
		</div>
		<div data-linea="4">
			<label for="fechaEmbarque">Fecha Embarque: </label>
			<?php
				echo date('Y/m/d',strtotime($filaSolicitud['fecha_embarque']));
			?>
		</div>
		<div data-linea="5">
			<?php
				echo '<label for="uso">Uso Destinado: </label>';
				echo $filaSolicitud['nombre_uso'];
			?>
		</div>
		<div data-linea="6">
			<label for="puestoControl">Puesto Control Cuarentenario: </label>
			<?php
				echo $filaSolicitud['puesto_control'];
			?>
		</div>	
		
	</fieldset>
	
	<?php
		$res=$ce->obtenerDetalleSolicitud($conexion, $idSolicitud);
		$contador=0;
			while($fila=pg_fetch_assoc($res)){
				$contador+=1;
				echo '<fieldset id="datosProducto">	<legend>Datos del Producto '.$contador.' :</legend>'.
						'<div data-linea="1"><label>Tipo de Producto: </label>'.$fila['nombre_tipo'].'</div>'.
						'<div data-linea="2"><label>Subtipo: </label>'.$fila['nombre_subtipo'].'</div>'.
						'<div data-linea="2"><label>Producto: </label>'.$fila['nombre_comun'].'</div>'.
						'<div data-linea="3"><label>Sexo: </label>'.$fila['sexo_completo'].'</div>'.
						'<div data-linea="3"><label>Edad: </label>'.$fila['edad'].' meses</div>'.
						'<div data-linea="4"><label>Raza: </label>'.$fila['raza'].'</div>'.
						'<div data-linea="4"><label>Color: </label>'.$fila['color'].'</div>'.
						'<div data-linea="5"><label>Identificación: </label>'.$fila['identificacion_producto'].'</div>'.
					'</fieldset>';
			}
	
	$res=$ce->cargarDocumentos($conexion, $idSolicitud);
	$filaDocumento=pg_fetch_assoc($res);
	
		echo '<fieldset id="resultadoProductos">
					<legend>Documentos Adjuntos</legend>
					<div data-linea="1">
						<label>Certificado Zoosanitario de Exportación: </label>';
						if($filaDocumento['ruta_zoosanitario_exp'] !=""){
							echo '<a href="'. $filaDocumento['ruta_zoosanitario_exp'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
						}else{
							echo '<span class="alerta">No ha subido ningún archivo aún</span>';
						}
				echo'</div>
					<div data-linea="2">
						<label>Autorización Ministerio de Ambiente: </label>';
						if($filaDocumento['ruta_autorizacion_min_ambiente'] !=""){
							echo '<a href="'. $filaDocumento['ruta_autorizacion_min_ambiente'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
						}else{
							echo '<span class="alerta">No ha subido ningún archivo aún</span>';
						}
				echo'</div>';
		echo'</fieldset>';
	?>

	<fieldset>
		<legend>Datos de referencia de pago</legend>
		<div data-linea="5">
			<?php
				echo '<label for="uso">Datos referencia: </label>';
				echo $filaSolicitud['detalle_pago'];
			?>
		</div>
	</fieldset>
	<div id="ordenPago"></div>
	
<script type="text/javascript">

	$("document").ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();	
	});
	
</script>