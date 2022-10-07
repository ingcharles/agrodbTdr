<?php 

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitario.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$fi = new ControladorFitosanitario();

//Obtener lista del Fito 
$qfitoExportacion = $fi -> listarFitoExportacion($conexion, $_POST['id']);
$fitoExportacion = pg_fetch_assoc($qfitoExportacion);

//Obtener la lista Documental
$qDocumentos = $fi->abrirExFitoArchivos($conexion, $_POST['id']);

//Obtener lista del Fito Detalle
$qfitoExportacionDetalle = $fi -> listarFitoExportacionDetalle($conexion, $_POST['id']);

//Obtener lista del Fito Detalle
$qfitoExportacionTransito = $fi -> listarFitoExportacionTransito($conexion, $_POST['id']);


?>

<header>
	<h1>Solicitud Exportación</h1>
</header>
<div id="estado"></div>
<div class="pestania">


	<?php 
		if($fitoExportacion['id_vue'] != ''){
			echo '<fieldset>
				<legend>Información de la Solicitud</legend>
					<div data-linea="1">
						<label>Identificación VUE: </label> '. $fitoExportacion['id_vue'] .'
					</div>
			</fieldset>';
		}
	?>
	
	<fieldset>
            <input type="hidden" id="idExportacion" name="idExportacion" value=<?php echo $fitoExportacion['id_fito_exportacion']; ?> />
            
			<legend>Datos del importador</legend>
			
				<div data-linea="1">
					<label>Nombre importador: </label> <?php echo $fitoExportacion['nombre_importador']; ?> 
				</div>
				
				<div data-linea="2">
					<label>Direccion importador: </label> <?php echo $fitoExportacion['direccion_importador']; ?> 
				</div>
	</fieldset>

	<fieldset>
		<legend>Datos de Generales de la Exportación</legend>
							
				<div data-linea="1">
					<label>País embarque: </label> <?php echo $fitoExportacion['pais_embarque']; ?> 
				</div>
				
				<div data-linea="2">
					<label>Puerto embarque: </label> <?php echo $fitoExportacion['puerto_embarque']; ?> 
				</div>
				
				<div data-linea="3">
					<label>País destino: </label> <?php echo $fitoExportacion['pais_destino']; ?> 
				</div>
				
				<div data-linea="20">
					<label>Puerto destino: </label> <?php echo $fitoExportacion['puerto_destino']; ?> 
				</div>
				
				<div data-linea="4">
					<label>Pais origen: </label> <?php echo $fitoExportacion['pais_origen']; ?> 
				</div>
				
				<div data-linea="4">
					<label>Lugar inspección: </label> <?php echo $fitoExportacion['lugar_inspeccion']; ?> 
				</div>
													
				<div data-linea="6">
					<label>Transporte: </label> <?php echo $fitoExportacion['transporte']; ?> 
				</div>
				
				<div data-linea="6">
					<label>Fecha embarque: </label> <?php echo $fitoExportacion['fecha_embarque']; ?> 
				</div>
				
				<div data-linea="7">
					<label>Número viaje: </label> <?php echo $fitoExportacion['numero_viaje']; ?> 
				</div>
				
				<div data-linea="7">
					<label>Provincia: </label> <?php echo $fitoExportacion['provincia']; ?> 
				</div>
				
				<div data-linea="10">
					<label>Producto orgánico: </label> <?php echo ($fitoExportacion['producto_organico'] == 'S' ? 'SI': 'NO'); ?>  
				</div>
				
				<div data-linea="10">
					<label>Certificación orgánica: </label> <?php echo ($fitoExportacion['numero_producto_organico'] == '' ? 'No disponible': $fitoExportacion['numero_producto_organico']); ?> 
				</div>
				
				<div data-linea="8">
					<label>Marca: </label> <?php echo $fitoExportacion['nombre_marcas']; ?> 
				</div>
				
				<div data-linea="14">
					<label>Reporte inspección: </label> <?php echo $fitoExportacion['reporte_inspeccion']; ?> 
				</div>
				
				<div data-linea="15">
					<label>Información adicional: </label> <?php echo $fitoExportacion['observacion_operador']; ?> 
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Datos de Generales del Tratamiento del Producto</legend>	
								
				<div data-linea="8">
					<label>Tratamiento realizado: </label> <?php echo $fitoExportacion['tratamiento_realizado']; ?> 
				</div>
				
				<div data-linea="9">
					<label>Duración: </label> <?php echo $fitoExportacion['duracion_tratamiento']; ?> 
				</div>
				
				<div data-linea="9">
					<label>Temperatura: </label> <?php echo $fitoExportacion['temperatura_tratamiento'].' '.$fitoExportacion['unidad_temperatura']; ?> 
				</div>
				
				<div data-linea="10">
					<label>Fecha: </label> <?php echo $fitoExportacion['fecha_tratamiento']; ?> 
				</div>
				
				<div data-linea="10">
					<label>Químico usado: </label> <?php echo $fitoExportacion['quimico_tratamiento']; ?> 
				</div>
				
				<div data-linea="11">
					<label>Concentración: </label> <?php echo $fitoExportacion['concentracion_producto']; ?> 
				</div>
							
		</fieldset>
		
	<?php 
	//IMPRESION DE DOCUMENTOS
	if(count($qDocumentos)>0){
		$i=1;
		
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
	
	//Transito
	if(count($qfitoExportacionTransito)>0){
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
	
	
		foreach ($qfitoExportacionTransito as $transito){
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
	?>
	
	<?php 
		$i=1;	
		
		echo '<fieldset>
		<legend>Productos para Exportación</legend>;
		<table>
			<tr>
			<td>#</td>
			<td><label>Subtipo</label></td>
			<td><label>Producto</label></td>
			<td><label>Cédula/RUC</label></td>
			<td><label>Operador</label></td>
			<td><label>Número de bultos</label></td>
			<td><label>Cantidad neta</label></td>';
		
			foreach ($qfitoExportacionDetalle as $detalleFito){
				if($detalleFito['permisoMusaceas']!=''){
					echo '<td><label>Permiso musaceas</label></td>';
					break;
				}
			}
			
			echo '</tr>';
		$i=1;
		
		foreach ($qfitoExportacionDetalle as $detalleFito){
			
			$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $detalleFito['idProducto']);
			$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);

			echo '<tr>
			<td>'.$i.'</td>
			<td>'.$productoTipoSubtipo['nombre_subtipo'].'</td>
			<td>' . $detalleFito['nombreProducto'] . '</td>
			<td>' . $detalleFito['identificador'] . ' </td>
			<td>' . $detalleFito['nombreRepresentante'] . ' ' .  $detalleFito['apellidoRepresentante'] . '</td>
			<td>' . $detalleFito['numeroBultos'] . ' ' . $detalleFito['unidadBultos'] .'</td>
			<td>' . $detalleFito['cantidadProducto'] . ' ' . $detalleFito['unidadCantidadProducto'] . '</td>';
			if($detalleFito['permisoMusaceas']!=''){
					echo '<td>' . $detalleFito['permisoMusaceas'] . '</td>';
				}
			echo '</tr>';
			$i++;
				
		}
		echo '</table>
		</fieldset>';

    ?>	
</div>
<!-- SECCION DE REVISIÓN DE PRODUCTOS PARA EXPORTACION -->
<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Fitosanitario"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $fitoExportacion['id_vue'];?>"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="pago">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
</div>    
    
    
<script type="text/javascript">
var estado= <?php echo json_encode($fitoExportacion['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
	
		$("#evaluarDocumentosSolicitud").hide();
		
		if(estado == "enviado" || estado == "asignadoDocumental"){
			$("#evaluarDocumentosSolicitud").show();
		}else{
			$("#evaluarDocumentosSolicitud").hide();
		}
	});
	
	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}
	
		if($("#resultadoDocumento").val()=='subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}
		
		
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
