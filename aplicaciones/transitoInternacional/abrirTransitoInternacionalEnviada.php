<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTransitoInternacional.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cti = new ControladorTransitoInternacional();
$crs = new ControladorRevisionSolicitudesVUE();

$qTransitoInternacional = $cti->abrirTransitoInternacional($conexion, $_POST['id']);
$transitoInternacional = pg_fetch_assoc($qTransitoInternacional);

$qTransitoInternacionalProductos = $cti->abrirTransitoInternacionalProductos($conexion, $_POST['id']);

$identificadorOperador = $transitoInternacional['identificador_importador'];
$estado = $transitoInternacional['estado'];
$nombreComercialProducto = $transitoInternacional['nombre_comercial_producto'];

$qDocumentos = $cti->abrirDocumentosTransitoInternacional($conexion, $_POST['id']);

?>

<header>
	<h1>Solicitud de Tránsito</h1>
</header>
	
<div id="estado"></div>

<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Tránsito</legend>
			
			<div data-linea="0">
				<label>Tipo de Certificado: </label> <?php echo $transitoInternacional['nombre_documento']; ?> 
			</div>
			
			<div data-linea="1">
				<label>Razón social solicitante: </label> <?php echo $transitoInternacional['nombre_solicitante']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social : </label> <?php echo $transitoInternacional['nombre_importador']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Representante legal Importador: </label> <?php echo  $transitoInternacional['representante_legal_importador']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label><?php echo ($estado=='aprobado'? '<span class="exito">'.$estado.'</span>': '<span class="alerta">Solicitud en revisión documental</span>'); ?>
			</div>
			<?php
			
			     /*if($transitoInternacional['estado'] == 'asignadoDocumental'){ //////////////////////REVISAR EL NOMBRE DE CMAPO ESTADOIMPORTACION
					$res = $crs->listarInspectoresAsignados($conexion, $_POST['id'], 'TránsitoInternacional', 'Documental');

					echo '
						<div data-linea="5">
							<label>Inspectores asignados: </label>';
					
					while($fila = pg_fetch_assoc($res)){
						echo $fila['apellido'].", ".$fila['nombre']."; "; 
					}

					echo '</div>';
				}*/
			?> 
	</fieldset>
	
	<fieldset>
		<legend>Datos de Tránsito Internacional</legend>	
			
			<div data-linea="1">
				<label>Régimen aduanero: </label> <?php echo $transitoInternacional['nombre_regimen_aduanero']; ?> 
			</div>
			
			<div data-linea="2">
				<label>País de origen: </label> <?php echo $transitoInternacional['nombre_pais_origen']; ?>
			</div>
			
			<div data-linea="2">
				<label>País de procedencia: </label> <?php echo $transitoInternacional['nombre_pais_procedencia']; ?>
			</div>
			
			<div data-linea="3">
				<label>País de destino: </label> <?php echo $transitoInternacional['nombre_pais_destino']; ?>
			</div>
			
			<div data-linea="4">
				<label>Lugar de ubicación de envío: </label><?php echo $transitoInternacional['nombre_ubicacion_envio']; ?> 
			</div>	
			
			<div data-linea="5">
				<label>Punto de ingreso: </label> <?php echo $transitoInternacional['nombre_punto_ingreso']; ?>
			</div>
			
			<div data-linea="5">
				<label>Punto de salida: </label> <?php echo $transitoInternacional['nombre_punto_salida']; ?>
			</div>			
			
			<div data-linea="6">
				<label>Medio de transporte: </label> <?php echo $transitoInternacional['nombre_medio_transporte']; ?>
			</div>
			
			<div data-linea="6">
				<label>Placa del vehículo: </label> <?php echo $transitoInternacional['placa_vehiculo']; ?>
			</div>		
			
			<div data-linea="7">
				<label>Ruta a seguir: </label><?php echo $transitoInternacional['ruta_seguir']; ?> 
			</div>	
	</fieldset>
	
	
	<?php 
	
	$i=1;
	while ($producto = pg_fetch_assoc($qTransitoInternacionalProductos)){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
			
				<div data-linea="3">
					<label>Tipo de producto: </label> ' . $producto['nombre_tipo_producto'] . ' <br/>
				</div>
				<div data-linea="4">
					<label>Subtipo de producto: </label> ' . $producto['nombre_subtipo_producto'] . ' <br/>
				</div>
				<div data-linea="5">
					<label>Nombre del producto: </label> ' . $producto['nombre_producto'] . ' <br/>
				</div>
				<div data-linea="5">
					<label>Partida arancelaria: </label> ' . $producto['subpartida_arancelaria'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Cantidad: </label> ' . $producto['cantidad_producto'] . ' ' . $producto['nombre_unidad_cantidad'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Peso neto (Kg): </label> ' . $producto['peso_kilos'] . ' <br/>
				</div>';
		echo '</fieldset>';
		$i++;
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
									<input name="idVue" value="'.$documento['reqNo'].'" type="hidden">
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

<!-- SECCION DE REVISIÓN DE PRODUCTOS PARA IMPORTACION -->
<div class="pestania">
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/>
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="TransitoInternacional"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $transitoInternacional['req_no'];?>"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $identificadorOperador;?>"/>
		<input type="hidden" name="nombreComercialProducto" value="<?php echo $nombreComercialProducto;?>"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>					
				<div data-linea="6">
					<label>Resultado: </label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="aprobado">Aprobar solicitud</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">Solicitud rechazada</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones: </label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
				
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
</div>

<script type="text/javascript">
var estado= <?php echo json_encode($estado); ?>;

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

	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultadoDocumento").val())){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if($("#resultadoDocumento").val()!= 'aprobado'){
			if(!$.trim($("#observacionDocumento").val())){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			$('#evaluarDocumentosSolicitud').attr('data-opcion','evaluarDocumentosSolicitud');
			ejecutarJson(form);
		}
	}
	
	
</script>
