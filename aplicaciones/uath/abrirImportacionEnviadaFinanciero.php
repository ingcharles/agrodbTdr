<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$crs = new ControladorRevisionSolicitudesVUE();
$ci = new ControladorImportaciones();
$cc = new ControladorCatalogos();

$idSolicitud = $_POST['id'];
$identificadorInspector = $_SESSION['usuario'];

$qImportacion = $ci->abrirImportacionEnviada($conexion, $idSolicitud);

$qDocumentos = $ci->abrirImportacionesArchivos($conexion, $idSolicitud);

$qAmpliacion = $ci->abrirImportacionesArchivoIndividual($conexion,$idSolicitud, 'PEDIDO DE AMPLIACION');

$qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
$regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');

$qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
$moneda = pg_fetch_result($qMoneda, 0, 'nombre');

if($qImportacion[0]['estadoImportacion']=='verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'Importación', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
	//Obtener monto a pagar
	$qDatosPago = $crs->buscarIdImposicionTasa($conexion, $idGrupo['id_grupo'], 'Importación', 'Financiero');
	$datosPago = pg_fetch_assoc($qDatosPago);
}


//Obtener datos de entidades bancarias
$qEntidadesBancarias = $cc->listarEntidadesBancariasAgrocalidad($conexion);

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);


?>

<header>
	<h1>Solicitud Importación</h1>
</header>
	
<div id="estado"></div>

<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idImportacion" name="idImportacion" value=<?php echo $qImportacion[0]['idImportacion']; ?> />
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qImportacion[0]['tipoCertificado']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $qImportacion[0]['razonSocial']; ?> <br/>
			</div>
			
			<div data-linea="3">
				<label>Representante legal: </label> <?php echo $qImportacion[0]['nombreRepresentante'] . ' ' . $qImportacion[0]['apellidoRepresentante']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label> <?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': (($qImportacion[0]['estadoImportacion']=='enviado' && pg_num_rows($qAmpliacion) != 0) ?'<span class="alerta">Solicitud de ampliación</span>':($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) == 0 ? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) != 0?'<span class="alerta">Solicitud en proceso de pago de ampliación</span>':($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':$qImportacion[0]['estadoImportacion']))))); ?>
			</div>

	</fieldset>
	
	<fieldset>
		<legend>Datos de Importación</legend>		
			<div data-linea="4">
				<label>Nombre exportador: </label> <?php echo $qImportacion[0]['nombreExportador']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Dirección exportador: </label> <?php echo $qImportacion[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País origen: </label> <?php echo $qImportacion[0]['paisExportacion']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País embarque: </label> <?php echo $qImportacion[0]['paisEmbarque']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Nombre embarcador: </label> <?php echo $qImportacion[0]['nombreEmbarcador']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Régimen aduanero: </label> <?php echo $regimenAduanero; ?> 
			</div>
			
			<div data-linea="8">
				<label>Moneda: </label> <?php echo $moneda; ?> 
			</div>
			
			<div data-linea="8">
				<label>Medio transporte: </label> <?php echo $qImportacion[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto embarque: </label> <?php echo $qImportacion[0]['puertoEmbarque']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto destino: </label> <?php echo $qImportacion[0]['puertoDestino']; ?> 
			</div>
	</fieldset>
	
	
	<?php 
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
	
	$i=1;
	foreach ($qImportacion as $importacion){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>';
		
		$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $importacion['idProducto']);
		$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
		
		echo '<div data-linea="3">	
					<label>Tipo producto: </label> ' . $productoTipoSubtipo['nombre_tipo'] . ' <br/>
				</div>
				<div data-linea="4">	
					<label>Subtipo producto: </label> ' . $productoTipoSubtipo['nombre_subtipo'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $importacion['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>presentación producto: </label> ' . $importacion['presentacion'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $importacion['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Cantidad: </label> ' . $importacion['unidad'] . ' '.$importacion['unidadMedida']. ' <br/>
				</div>
				<div data-linea="7">
					<label>Peso neto: </label> ' . $importacion['peso'] . ' kgs <br/>
				</div>
				<div data-linea="8">
					<label>Valor FOB: </label> ' . $importacion['valorFob'] . ' <br/>
				</div>
				<div data-linea="8">
					<label>Valor CIF: </label> ' . $importacion['valorCif'] . ' <br/>
				</div>';
				if($importacion['licenciaMagap']!=''){
					echo '
							<div data-linea="9">
								<label>Licencia MAGAP: </label> ' . $importacion['licenciaMagap'] . ' <br/>
							</div>';
				}
				
				if($importacion['registroSemillas']!=''){
					echo '
							<div data-linea="9">
								<label>Registro Semillas: </label> ' . $importacion['registroSemillas'] . ' <br/>
							</div>';
				}
				
		echo '</fieldset>';
		
		$i++;
	}
	
	
	?>	
</div>

<!-- SECCION DE REVISIÓN DE PAGOS PARA IMPORTACION -->
<div class="pestania">	 
	
	<!--form id="asignarMonto" data-rutaAplicacion="importaciones" data-opcion="asignarMontoImportacion" data-accionEnExito="ACTUALIZAR"-->
	<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Importación"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacionVUE"/>
		<input type="hidden" name="idVue" value="<?php echo $qImportacion[0]['idVue'];?>"/>
		<input type="hidden" name="idOperador" value="<?php echo $qImportacion[0]['identificador'];?>"/>
		
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
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Importación"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="idOperador" value="<?php echo $qImportacion[0]['identificador'];?>"/>
		<input type="hidden" name="idVue" value="<?php echo $qImportacion[0]['idVue'];?>"/>
		<input type="hidden" name="idGrupo" value="<?php echo $idGrupo['id_grupo'];?>"/>
		
		
		<fieldset id="factura">
				<legend>Pago de arancel</legend>
					<div data-linea="12" >
						<label>Monto a pagar: </label> $ <?php echo $datosPago['monto']; ?>
					</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
								
				<div data-linea="5">
					<label>Número de factura: </label>
						<input type="text" id="numeroFactura" name="numeroFactura" placeholder="Ej: 00234" data-er="^[0-9-]+$"/>
				</div>
				
				<div data-linea="6">
					<label>Entidad bancaria</label>
						<select id="codigoBanco" name="codigoBanco">
							<option value="">Seleccione....</option>
							<?php 
								while ($fila = pg_fetch_assoc($qEntidadesBancarias)){
									echo '<option value="'.$fila['id_banco']. '" data-codigovue="'.$fila['codigo_vue'].'">'. $fila['nombre'] .'</option>';
								}
							?>
						</select>
						
						<input type="hidden" id="nombreBanco" name="nombreBanco"></input>
				</div>	
				
				<div data-linea="7">
					<label>Monto recaudado: </label>
						<input type="text" id="montoRecaudado" name="montoRecaudado" placeholder="Ej: 153" data-er="^[0-9]+(\.[0-9]{1,3})?$" value="<?php echo $datosPago['monto_recaudado'] ?>"/>
				</div>
				
				<div data-linea="7">
					<label>Fecha de facturación: </label>
						<input type="text" id="fechaFacturacion" name="fechaFacturacion" value="<?php echo $datosPago['fecha_facturacion']; ?>"/>
				</div>
					
				<div data-linea="8">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="aprobado">Confirmar pago</option>
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
var estado= <?php echo json_encode($qImportacion[0]['estadoImportacion']); ?>;
var banco = <?php echo json_encode($datosPago['codigo_banco']);?>;

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

		if($("#montoRecaudado").val().length >= 1){
			$("#montoRecaudado").prop("readonly",true);
		}

		if($("#fechaFacturacion").val().length >= 1){
			$("#fechaFacturacion").prop("readonly",true);
		}else{
			$("#fechaFacturacion").datepicker({
			    changeMonth: true,
			    changeYear: true
			  });
		}

		if(banco == '456'){
			$("#codigoBanco").find('option[data-codigovue="'+banco+'"]').prop("selected","selected");
			$("#codigoBanco").attr("disabled","disabled");
			$('#nombreBanco').val($("#codigoBanco  option:selected").text());
		}else{
			cargarValorDefecto("codigoBanco","<?php echo $datosPago['codigo_banco'];?>");
		}
		
	});

	$("#codigoBanco").change(function(){
    	$('#nombreBanco').val($("#codigoBanco  option:selected").text());

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

		if(!$.trim($("#codigoBanco").val())){
			error = true;
			$("#codigoBanco").addClass("alertaCombo");
		}

		if(!$.trim($("#montoRecaudado").val()) || !esCampoValido("#montoRecaudado")){
			error = true;
			$("#montoRecaudado").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaFacturacion").val())){
			error = true;
			$("#fechaFacturacion").addClass("alertaCombo");
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