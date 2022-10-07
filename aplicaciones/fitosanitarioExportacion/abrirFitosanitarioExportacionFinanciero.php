<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cce = new ControladorCertificados();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();

$cfe = new ControladorFitosanitarioExportacion();

$idFitosanitarioExportacion = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];
$condicion = $_POST['opcion'];

$qCabeceraFitosanitarioExportacion = $cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);
$cabeceraFitosanitarioExportacion = pg_fetch_assoc($qCabeceraFitosanitarioExportacion);

$exportadoresFitosanitarioExportacion = $cfe->obtenerExportadoresFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

$qDocumentos = $cfe->obtenerArchivosAdjuntosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

$qTransitoFitosanitarioExportacion = $cfe -> obtenerTransitoFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

$estadoActual = $cabeceraFitosanitarioExportacion['estado'];

if($estadoActual == 'verificacion' || $estadoActual == 'verificacionVUE'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idFitosanitarioExportacion, 'FitosanitarioExportacion', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
	//Obtener monto a pagar
	$qDatosPago = $crs->buscarIdImposicionTasa($conexion, $idGrupo['id_grupo'], 'FitosanitarioExportacion', 'Financiero');
	$datosPago = pg_fetch_assoc($qDatosPago);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idFitosanitarioExportacion, 'FitosanitarioExportacion');
}

if($condicion == 'pago'){
	echo '<input type="hidden" class= "abrirPago" id="'.$idFitosanitarioExportacion.'-'.$cabeceraFitosanitarioExportacion['numero_identificacion_solicitante'].'-'.$estadoActual.'-FitosanitarioExportacion-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$cabeceraFitosanitarioExportacion['id_vue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idFitosanitarioExportacion.'-'.$cabeceraFitosanitarioExportacion['numero_identificacion_solicitante'].'-'.$estadoActual.'-FitosanitarioExportacion-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$cabeceraFitosanitarioExportacion['id_vue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idFitosanitarioExportacion.'-'.$cabeceraFitosanitarioExportacion['numero_identificacion_solicitante'].'-pago-FitosanitarioExportacion-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$cabeceraFitosanitarioExportacion['id_vue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idFitosanitarioExportacion.'-'.$cabeceraFitosanitarioExportacion['numero_identificacion_solicitante'].'-'.$estadoActual.'-FitosanitarioExportacion-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$cabeceraFitosanitarioExportacion['id_vue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}

?>
<div id="formularioGeneral">
<header>
	<h1>Solicitud fitosanitaria de exportación</h1>
</header>
	
	<fieldset>
		<legend>Información general.</legend>
			<div data-linea="1">
				<label>Estado actual: </label> <?php echo $cabeceraFitosanitarioExportacion['estado']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Observación: </label> <?php echo $cabeceraFitosanitarioExportacion['observacion']; ?> 
			</div>
		
	</fieldset>
	
	<fieldset>
			<legend>Certificado fitosanitario de exportación # <?php echo $cabeceraFitosanitarioExportacion['id_vue']; ?></legend>
						
			<div data-linea="1">
				<label>Idioma: </label> <?php echo $cabeceraFitosanitarioExportacion['codigo_idioma']; ?> 
			</div>
			<div data-linea="1">
				<label>Identificador solicitante: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_identificacion_solicitante']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $cabeceraFitosanitarioExportacion['razon_social_solicitante']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Dirección: </label> <?php echo $cabeceraFitosanitarioExportacion['direccion_solicitante']; ?>
			</div>
			
			<div data-linea="4">
				<label>Teléfono: </label> <?php echo $cabeceraFitosanitarioExportacion['telefono_solicitante']; ?> 
			</div>
			
			<div data-linea="4">
				<label>E-mail: </label> <?php echo $cabeceraFitosanitarioExportacion['correo_electronico_solicitante']?> 
			</div>
			
			<hr/>
			
			<div data-linea="5">
				<label>Nombre importador: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_importador']?> 
			</div>
			
			<div data-linea="6">
				<label>Dirección importador: </label> <?php echo $cabeceraFitosanitarioExportacion['direccion_importador']?> 
			</div>
			
			<hr/>
			
			<div data-linea="7">
				<label>Producto orgánico: </label> <?php echo $cabeceraFitosanitarioExportacion['producto_organico']?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['producto_organico'] == 'SI'){
					echo '<div data-linea="7">
								<label>Certificado #: </label> '. $cabeceraFitosanitarioExportacion['certificado_organico'].' 
						  </div>';
				}
			?>
			
			<div data-linea="8">
				<label>Número de Bultos: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_bultos'].' '.$cabeceraFitosanitarioExportacion['unidad_bultos']?> 
			</div>
			
			<div data-linea="8">
				<label>País de origen: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_origen']?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['identificacion_agencia_carga'] != ''){
					echo '<div data-linea="9">
								<label>Identificador agencia carga: </label> '. $cabeceraFitosanitarioExportacion['identificacion_agencia_carga'].' 
						  </div>
						  <div data-linea="10">
								<label>Nombre agencia carga: </label> '. $cabeceraFitosanitarioExportacion['nombre_agencia_carga'].'
						  </div>
						';
				}
			?>
			
			<div data-linea="11">
				<label>País destino: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_destino']?> 
			</div>
			
			<div data-linea="12">
				<label>Puerto destino: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_puerto_destino']?> 
			</div>
			
			<div data-linea="13">
				<label>País embarque: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_embarque']?> 
			</div>
			
			<div data-linea="13">
				<label>Fecha embarque: </label> <?php echo date('j/n/Y',strtotime($cabeceraFitosanitarioExportacion['fecha_embarque']))?> 
			</div>
			
			<div data-linea="14">
				<label>Puerto embarque: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_puerto_embarque'] ?> 
			</div>
			
			<div data-linea="14">
				<label>Medío transporte: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_medio_transporte'] ?> 
			</div>
			
			<div data-linea="15">
				<label>Nombre marca: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_marca'] ?> 
			</div>
			
			<div data-linea="15">
				<label>Número de viaje: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_viaje'] ?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['informacion_adicional'] != ''){
					echo '<div data-linea="16">
								<label>Información adicional: </label> '. $cabeceraFitosanitarioExportacion['informacion_adicional'] .'
						  </div>';
				}
			
				if($cabeceraFitosanitarioExportacion['descuento'] == 'SI'){
					echo '<div data-linea="17">
								<label>Posee descuento: </label> '. $cabeceraFitosanitarioExportacion['descuento'] .' 
						  </div>
						  <div data-linea="18">
								<label>Motivo descuento: </label> '. $cabeceraFitosanitarioExportacion['motivo_descuento'] .'
						  </div>
							';
				}
			
				if($cabeceraFitosanitarioExportacion['uso_ciudad_transito'] == 'SI'){
					echo '<div data-linea="16">
								<label>Posee transito: </label> '. $cabeceraFitosanitarioExportacion['uso_ciudad_transito'] .' 
						  </div>';
				}
			?>
			
	</fieldset>
	
	
		
		<?php 
			//foreach ($exportadoresFitosanitarioExportacion as $exportadores){
			while ($exportador = pg_fetch_assoc($exportadoresFitosanitarioExportacion)){
		
			echo '<fieldset>
				  	<legend>Datos del exportador </legend>';
					
					$datosExportador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $exportador['numero_identificacion_exportador']));
					
					echo '<div data-linea="1">
								<label>Identificador del exportador: </label> '. $exportador['numero_identificacion_exportador'] .' 
							</div>
							<div data-linea="2">
								<label>Razón social: </label> '. $datosExportador['nombre_operador'] .' 
						  </div><hr/>';
					
					$productosFitosanitarioExportacion = $cfe->obtenerProductosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $exportador['id_fitosanitario_exportador']);
					
					$i=2;
					while ($producto = pg_fetch_assoc($productosFitosanitarioExportacion)){

						$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $producto['id_producto']);
						$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
						
						$datosProducto = pg_fetch_assoc($cc->ObtenerProductoPorId($conexion, $producto['id_producto']));

						echo '<div data-linea="'.++$i.'">
									<label>Tipo producto: </label> '. $productoTipoSubtipo['nombre_tipo'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Subtipo producto: </label> '. $productoTipoSubtipo['nombre_subtipo'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Nombre producto: </label> '. $datosProducto['nombre_comun'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Partida arancelaria: </label> '. $datosProducto['partida_arancelaria'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Cantidad cobro: </label> '. $producto['cantidad_cobro'].' '.$producto['unidad_cobro'].' 
						  	  </div>
							  <div data-linea="'.$i.'">
									<label>Cantidad peso neto: </label> '. $producto['cantidad_peso_neto'].' '.$producto['unidad_peso_neto'].' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Cantidad peso bruto: </label> '. $producto['cantidad_peso_bruto'].' '.$producto['unidad_peso_bruto'].' 
						  	  </div>
							  <div data-linea="'.$i.'">
									<label>Cantidad comercial: </label> '. $producto['cantidad_comercial'].' '.$producto['unidad_cantidad_comercial'].' 
						  	  </div>';
						
						if($producto['descripcion_tipo_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Tipo tratamiento: </label> '. $producto['descripcion_tipo_tratamiento'].' 
						  	  </div>';
						}
						
						if($producto['descripcion_nombre_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Nombre tratamiento: </label> '. $producto['descripcion_nombre_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['duracion_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Duración tratamiento: </label> '. $producto['duracion_tratamiento'].' '.$producto['unidad_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['temperatura_tratamiento'] != ''){
							echo '<div data-linea="'.$i.'">
									<label>Temperatura tratamiento: </label> '. $producto['temperatura_tratamiento'].' '.$producto['unidad_temperatura_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['concentracion_producto_quimico'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Concentración: </label> '. $producto['concentracion_producto_quimico'].' 
						  	  </div>';
						}
						
						if($producto['fecha_tratamiento'] != ''){
							echo '<div data-linea="'.$i.'">
									<label>Fecha tratamiento: </label> '. date('j/n/Y (G:i)',strtotime($producto['fecha_tratamiento'])).'
						  	  </div>';
						}
						
						if($producto['producto_quimico'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Producto quimico: </label> '. $producto['producto_quimico'].'
						  	  </div>';
						}
						
						if($producto['informacion_adicional'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Información adicional: </label> '. $producto['informacion_adicional'].'
						  	  </div>';
						}
						
						$areasFitosanitarioExportacion = $cfe->obtenerAreasFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $exportador['id_fitosanitario_exportador'], $producto['id_fitosanitario_producto']);
						
						$codigoArea = '';
						while ($area = pg_fetch_assoc($areasFitosanitarioExportacion)){
							$codigoArea .= $area['codigo_area_agrocalidad_unibanano'].', ';
						}
						
						$codigoArea = rtrim($codigoArea, ', ');
						
						echo '<div data-linea="'.++$i.'">
								<label>Código área: </label> '. $codigoArea.'
					  	  </div><hr/>';
					
						
						
					}
					
					
			echo '</fieldset>';
		
		}
		
		//Transito
		if(count($qTransitoFitosanitarioExportacion)>0){
			$j=1;
		
			echo'<div>
				<fieldset>
					<legend>Detalle de Tránsito</legend>
		
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>País</label></td>
										<td><label>Puerto</label></td>
										<td><label>Medio de transporte</label></td>
									</tr>';
		
		
			foreach ($qTransitoFitosanitarioExportacion as $transito){
				echo '<tr>
						<td>'.$j.'</td>
						<td>'.$transito['nombrePais'].'</td>
						<td>'.$transito['nombrePuerto'].'</td>
						<td>'.$transito['tipoTransporte'].'</td>
				 </tr>';
				$j++;
			}
		
			echo '</table>
			</fieldset>
			</div>';
		}
			 
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(count($qDocumentos)>0){
		
		echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
				
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';


		foreach ($qDocumentos as $documento){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipoArchivo'].'</td>
							<td>
								<form id="f_'.$i.'" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
									<input name="rutaArchivo" value="'.$documento['rutaArchivo'].'" type="hidden">
									<input name="nombreArchivo" value="'.$documento['tipoArchivo'].'.pdf" type="hidden">
									<input name="idVue" value="'.$documento['idVue'].'" type="hidden">
									<button type="submit" name="boton">Descargar</button>
								</form>
							</td>
						 </tr>';
						$i++;
		}

		echo '</table>
			</fieldset>
			</div>';

	}
		
	?>	
</div>

	<form id="evaluarFinancieroSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarFinancieroSolicitud" data-accionEnExito="ACTUALIZAR">
	
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idFitosanitarioExportacion;?>"/>
		<input type="hidden" name="tipoSolicitud" value="FitosanitarioExportacion"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $cabeceraFitosanitarioExportacion['id_vue'];?>"/>
		<input type="hidden" name="resultadoFinanciero" value="subsanacion"/>
		<input type="hidden" id="observacionFinanciero" name="observacionFinanciero" value="Su solicitud no aplica para recibir descuento, dado que no cumple con las condiciones establecidas de tercera edad o discapacidad"/>
		
		<button type="submit" class="guardar">Enviar subsanación</button>
	
	</form>

<div id="ordenPago"></div>
	
<script type="text/javascript">

var estado = <?php echo json_encode($cabeceraFitosanitarioExportacion['estado']); ?>;

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();

		$(".guardar").hide();

		if(estado == 'pago'){
			$(".guardar").show();
		}
	});

	$("#evaluarFinancieroSolicitud").submit(function(event){
		event.preventDefault();
		//$("#ordenPago").hide();
		//$(".guardar").hide();
		//$("#formularioGeneral").hide();
		ejecutarJson(this);
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de pago.</div>');
		
	});

	
</script>
