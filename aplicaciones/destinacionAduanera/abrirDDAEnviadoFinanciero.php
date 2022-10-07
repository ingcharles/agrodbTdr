<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cd = new ControladorDestinacionAduanera();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();

$usuario = $_SESSION['usuario'];

$qDestinacionAduanera = $cd->abrirDDA($conexion, $_POST['id']);

$qDocumentos = $cd->abrirDDAArchivos($conexion, $_POST['id']);

//Obtener datos del operador
$qOperador = $cr->buscarOperador($conexion, $qImportacion[0]['identificador']);

//Obtener monto a pagar
$qMonto = $crs->obtenerMontoSolicitud($conexion, $_POST['id'], 'DDA');

//Obtener datos de entidades bancarias
$qEntidadesBancarias = $cc->listarEntidadesBancarias($conexion);

?>

<header>
	<h1>Documento de Destinación Aduanera</h1>
</header>


	<div id="estado"></div>
	
	<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value=<?php echo $qDestinacionAduanera[0]['idDestinacionAduanera']; ?> />
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qDestinacionAduanera[0]['tipoCertificado']; ?> 
			</div>
			<div data-linea="2">
				<label>Razón social importador:</label> <?php echo $qDestinacionAduanera[0]['razonSocial'];?>
			</div>
			<div data-linea="3">
				<label>Representante legal:</label> <?php echo $qDestinacionAduanera[0]['nombreRepresentante'] . " ";
															echo $qDestinacionAduanera[0]['apellidoRepresentante'];?>
			</div>
			<div data-linea="4">
				<label>Estado de solicitud: </label> <?php echo ($qDestinacionAduanera[0]['estado']=='aprobado'? '<span class="exito">'.$qDestinacionAduanera[0]['estado'].'</span>':'<span class="alerta">'.$qDestinacionAduanera[0]['estado'].'</span>'); ?> <br/>
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Datos de Importación</legend>
			
			<div data-linea="1">
				<label>Permiso importación: </label> <?php echo  $qDestinacionAduanera[0]['permisoImportacion']; ?>
			</div>
			
			<div data-linea="1">
				<label>Certificado exportación: </label> <?php echo $qDestinacionAduanera[0]['permisoExportacion']; ?>
			</div>	
			<div data-linea="2">
				<label>Propósito: </label> <?php echo $qDestinacionAduanera[0]['proposito']; ?> 
			</div>
				
			<div data-linea="3">
				<label>Categoría producto: </label> <?php echo $qDestinacionAduanera[0]['categoriaProducto']; ?> 
			</div>
			
			
			<div data-linea="4">
				<label>Exportador: </label> <?php echo $qDestinacionAduanera[0]['nombreExportador']; ?>
			</div>	
			<div data-linea="5">
				<label>Dirección: </label> <?php echo $qDestinacionAduanera[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="6">
				<label>País origen: </label> <?php echo  $qDestinacionAduanera[0]['paisExportacion']; ?>
			</div>
			
			<div data-linea="7">
				<label># carga: </label> <?php echo $qDestinacionAduanera[0]['numeroCarga']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Puerto destino: </label> <?php echo $qDestinacionAduanera[0]['nombrePuertoDestino']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Medio de transporte: </label> <?php echo $qDestinacionAduanera[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="8">
				<label># Doc. transporte: </label> <?php echo $qDestinacionAduanera[0]['numeroTransporte']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Lugar inspección: </label> <?php echo $qDestinacionAduanera[0]['nombreLugarInspeccion']; ?> 
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
	
	$i=1;
	foreach ($qDestinacionAduanera as $destinacionAduanera){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $destinacionAduanera['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $destinacionAduanera['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Unidad: </label> ' . $destinacionAduanera['unidad'] . ' ' . $destinacionAduanera['unidadMedida'] . '<br/>
				</div>';
				
				if($destinacionAduanera['estado'] == 'aprobado' || $destinacionAduanera['estado'] == 'rechazado'){
					echo '<div data-linea="10" >
					<label>Estado: </label> ' . ($destinacionAduanera['estadoProducto']=='aprobado'? '<span class="exito">'.$destinacionAduanera['estadoProducto'].'</span>':'<span class="alerta">'.$destinacionAduanera['estadoProducto'].'</span>'). '<br/>
					</div>';
					if($destinacionAduanera['rutaArchivo']!='0' && $destinacionAduanera['observacionProducto']!= ''){
						echo   '<div data-linea="10">
								    	<label>Informe: </label>'. ($destinacionAduanera['rutaArchivo']==''? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$destinacionAduanera['rutaArchivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
								    </div>
								
									<div data-linea="11">
								     	<label>Observación: </label> ' . $destinacionAduanera['observacionProducto'] . ' <br/>
								     </div>';
					}
				}
		echo '</fieldset>';
		
		$i++;
	}
	
	
?>	
</div>

<!-- SECCION DE REVISIÓN DE PAGOS PARA DDA -->
<div class="pestania">	 
	
	<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="DDA"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacion"/>
		
		<fieldset>
			<legend>Valor a cancelar</legend>
				<div data-linea="11" >		
					<p class="nota">Por favor ingrese el valor a cancelar por el certificado.</p>
					
					<label>Monto: </label>
						<input type="text" id="monto" name="monto" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
				</div>
		</fieldset>			
		<button type="submit" class="guardar">Autorizar pago</button>
	</form>	
	
	<form id="verificarPago" data-rutaAplicacion="revisionFormularios" data-opcion="verificarPagoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="DDA"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="inspeccion"/>
		<input type="hidden" name="idOperador" value="<?php echo $qDestinacionAduanera[0]['identificador'];?>"/>
		
		<fieldset id="factura">
				<legend>Pago de arancel</legend>
					<div data-linea="12" >
						<label>Monto a pagar: </label> $ <?php echo pg_fetch_result($qMonto, 0, 'monto'); ?>
					</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
				<div data-linea="5">
					<label># de transacción: </label>
						<input type="text" id="transaccion" name="transaccion" placeholder="Ej: 153628965" data-er="^[0-9]+$"/>
				</div>
				
				<div data-linea="5">
					<label># de factura: </label>
						<input type="text" id="numeroFactura" name="numeroFactura" placeholder="Ej: 00234" data-er="^[0-9]+(\-)$"/>
				</div>
				
				<div data-linea="5">
					<label># de factura: </label>
						<input type="text" id="numeroFactura" name="numeroFactura" placeholder="Ej: 00234" data-er="^[0-9-]+$"/>
				</div>
				
				<div data-linea="6">
					<label>Entidad bancaria</label>
						<select id="codigoBanco" name="codigoBanco">
							<option value="">Seleccione....</option>
							<?php 
								while ($fila = pg_fetch_assoc($qEntidadesBancarias)){
									echo '<option value="'.$fila['id_banco']. '">'. $fila['nombre'] .'</option>';
								}
							?>
						</select>
						
						<input type="hidden" id="nombreBanco" name="nombreBanco"></input>
				</div>	
				
				<div data-linea="7">
					<label>Monto recaudado: </label>
						<input type="text" id="montoRecaudado" name="montoRecaudado" placeholder="Ej: 153" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
				</div>
				
				<div data-linea="7">
					<label>Fecha recaudación: </label>
						<input type="text" id="fechaRecaudacion" name="fechaRecaudacion" />
				</div>
					
				<div data-linea="8">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="inspeccion">Confirmar pago</option>
						</select>
				</div>	
				<div data-linea="9">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Finalizar proceso</button>
	</form>
</div>

<script type="text/javascript">
var estado= <?php echo json_encode($qDestinacionAduanera[0]['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		
		$("#verificarPago").hide();
		$("#asignarMonto").hide();

		if(estado == 'pago'){
			$("#asignarMonto").show();
		}else if(estado == 'verificacion'){
			$("#verificarPago").show();
		}
	});

	$("#codigoBanco").change(function(){
    	$('#nombreBanco').val($("#codigoBanco  option:selected").text());

	});

	$("#fechaRecaudacion").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });

	$("#asignarMonto").submit(function(event){
		event.preventDefault();
		chequearCamposAsignarMonto(this);
	});

	$("#verificarPago").submit(function(event){
		event.preventDefault();
		chequearCamposVerificarPago(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposAsignarMonto(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#monto").val()) || !esCampoValido("#monto")){
			error = true;
			$("#monto").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	function chequearCamposVerificarPago(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#transaccion").val()) || !esCampoValido("#transaccion")){
			error = true;
			$("#transaccion").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoBanco").val())){
			error = true;
			$("#codigoBanco").addClass("alertaCombo");
		}

		if(!$.trim($("#montoRecaudado").val()) || !esCampoValido("#montoRecaudado")){
			error = true;
			$("#montoRecaudado").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaRecaudacion").val())){
			error = true;
			$("#fechaRecaudacion").addClass("alertaCombo");
		}

		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>