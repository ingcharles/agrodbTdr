<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cif = new ControladorImportacionesFertilizantes();
$crs = new ControladorRevisionSolicitudesVUE();

$qImportacionFertilizantes = $cif->abrirImportacionFertilizantes($conexion, $_POST['id']);
$importacionFertilizantes = pg_fetch_assoc($qImportacionFertilizantes);

$qImportacionFertilizantesProductos = $cif->abrirImportacionFertilizantesProductos($conexion, $_POST['id']);

$identificadorOperador = $importacionFertilizantes['identificador'];
$estado = $importacionFertilizantes['estado'];
$nombreComercialProducto = $importacionFertilizantes['nombre_comercial_producto'];

$qDocumentos = $cif->abrirDocumentosImportacionFertilizantes($conexion, $_POST['id']);

?>

<header>
	<h1>Solicitud Importación Fertilizantes</h1>
</header>
	
<div id="estado"></div>

<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<div data-linea="1">
				<label>RUC: </label> <?php echo $identificadorOperador; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $importacionFertilizantes['razon_social']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label><?php echo ($estado=='aprobado'? '<span class="exito">'.$estado.'</span>': '<span class="alerta">Solicitud en revisión documental</span>'); ?>
			</div>
			<?php
			
				if($importacionFertilizantes['estadoImportacion'] == 'asignadoDocumental'){
					$res = $crs->listarInspectoresAsignados($conexion, $_POST['id'], 'ImportaciónFertilizantes', 'Documental');

					echo '
						<div data-linea="5">
							<label>Inspectores asignados: </label>';
					
					while($fila = pg_fetch_assoc($res)){
						echo $fila['apellido'].", ".$fila['nombre']."; "; 
					}

					echo '</div>';
				}
			?> 
	</fieldset>
	
	<fieldset>
		<legend>Datos de Importación</legend>		
			<div data-linea="1">
				<label>Operación registrada: </label> <?php echo $importacionFertilizantes['tipo_operacion']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Tipo solicitud: </label> <?php echo $importacionFertilizantes['tipo_solicitud']; ?> 
			</div>
			
			<div data-linea="3">
				<label>País origen: </label> <?php echo $importacionFertilizantes['nombre_pais_origen']; ?>
			</div>
			
			<div data-linea="3">
				<label>País procedencia: </label> <?php echo $importacionFertilizantes['nombre_pais_procedencia']; ?>
			</div>
			
			<div data-linea="4">
				<label>Producto a formular: </label><?php echo $importacionFertilizantes['producto_formular']; ?> 
			</div>	
			
			<div data-linea="5">
				<label>Número factura Agrocalidad: </label><?php echo $importacionFertilizantes['numero_factura_pedido']; ?> 
			</div>						
	</fieldset>
	
	
	<?php 
	
	$i=1;
	while ($producto = pg_fetch_assoc($qImportacionFertilizantesProductos)){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
			
				<div data-linea="3">
					<label>Nombre comercial producto: </label> ' . $producto['nombre_comercial_producto'] . ' <br/>
				</div>
				<div data-linea="4">
					<label>Nombre producto país de origen: </label> ' . $producto['nombre_producto_origen'] . ' <br/>
				</div>
				<div data-linea="5">
					<label># Registro en Ecuador: </label> ' . $producto['numero_registro'] . ' <br/>
				</div>
				<div data-linea="5">
					<label>Composición: </label> ' . $producto['composicion'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Cantidad comercial: </label> ' . $producto['cantidad'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Peso neto (Kg): </label> ' . $producto['peso_neto'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Partida arancelaria: </label> ' . $producto['partida_arancelaria'] . ' <br/>
				</div>';
		echo '</fieldset>';
		$i++;
	}
	
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(pg_num_rows($qDocumentos)>0){
		
		echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
				
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';


		while($documento = pg_fetch_assoc($qDocumentos)){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipo_archivo'].'</td>
							<td>
								<a href="'.$documento['ruta_archivo'].'" target="_blank">Archivo</a>
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
		<input type="hidden" name="tipoSolicitud" value="ImportaciónFertilizantes"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
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
				
				<p class="nota">Para solicitudes aprobadas colocar código de SENAE.</p>
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
