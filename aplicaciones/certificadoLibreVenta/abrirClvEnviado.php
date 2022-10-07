<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cl  = new ControladorClv();
$crq = new ControladorRequisitos();
$cr = new ControladorRegistroOperador();

$idCLV = $_POST['id'];

$cClv	  = $cl->listarCertificados($conexion,$idCLV);
$dClv     = $cl->listarDetalleCertificados($conexion,$idCLV);
$dcClv	  = $cl->listarDocumentos($conexion,$idCLV);
$pClv     = $cl->listaProdInocuidad($conexion,$idCLV);

$cTitular = $cr->buscarOperador($conexion,$cClv[0]['idTitular']);

$fabricanteFormulador = $crq->listarFabricanteFormulador($conexion, $cClv[0]['id_producto']);
$presentacionProducto = $crq->listarCodigoInocuidad($conexion, $cClv[0]['id_producto']);
$especieProducto = $crq->listarUsos($conexion, $cClv[0]['id_producto']);

?>

<header>
	<h1>Certificado de libre venta</h1>
</header>

<div id="estado"></div>
	
<div class="pestania">

	<fieldset>
		<legend>Información de la Solicitud</legend>
			<div data-linea="1">
				<label>Identificación VUE: </label> <?php echo $cClv[0]["idVue"] ; ?> 
			</div>
	</fieldset>

	<fieldset>
		<legend>Información del titular</legend>
			<div data-linea="3">
				<label>RUC / Cédula: </label> <?php echo pg_fetch_result($cTitular, 0, 'identificador'); ?> 
			</div>

			<div data-linea="4">
				<label>Nombre: </label> <?php echo pg_fetch_result($cTitular, 0, 'nombre_representante') . ' ' . pg_fetch_result($cTitular, 0, 'apellido_representante'); ?>
			</div>

			<div data-linea="7">
				<label>Provincia: </label> <?php echo pg_fetch_result($cTitular, 0, 'provincia'); ?>
			</div>

			<div data-linea="7">
				<label>Cantón: </label> <?php echo pg_fetch_result($cTitular, 0, 'canton'); ?>
			</div>

			<div data-linea="8">
				<label>Parroquia: </label> <?php echo pg_fetch_result($cTitular, 0, 'parroquia'); ?>
			</div>

			<div data-linea="9">
				<label>Dirección: </label> <?php echo pg_fetch_result($cTitular, 0, 'direccion'); ?> 
			</div>
	</fieldset>	

	<fieldset id="informacionProductoClv">
			 <legend>Información del producto <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?></legend>
			    <div data-linea="140">
					<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?> 
				</div>
				<div data-linea="14">
					<label>Tipo operación: </label> <?php echo $cClv[0]['tipoDatoCertificado']; ?> 
				</div>
			 	<div data-linea="15">
					<label>Producto: </label> <?php echo $cClv[0]['nombre_producto']; ?>
				</div>
				<div data-linea="16">
				    <label>Subpartida: </label> <?php echo $cClv[0]['subpartida']; ?>
				 </div>

				<?php 
					 if ($cClv[0]['tipoProducto'] == 'IAP'){
				  		echo '<div data-linea="17">
								<label>Formulación VUE: </label>' .$pClv[0]['formulacion'] .
							'</div>
							<div data-linea="17">
								<label>Formulación GUIA: </label>' .$pClv[0]['formulacionGuia'] .
							'</div>
							<div data-linea="18">
								<label>Composición: </label>' .$pClv[0]['composicionGuia'] .
							'</div>';
					 }else{
					 	echo '<div data-linea="16">
						 		<label>Forma farmacética: </label>' . $pClv[0]['formulacionGuia'] .
						 	'</div>';
					 }
				?>

				<div data-linea="19">
					<label>Clasifición: </label> <?php echo $pClv[0]['clasificacion']; ?>
				</div>
	</fieldset>

	<?php 
	//IMPRESION DE DOCUMENTOS

		if(count($dcClv)>0){
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
									foreach ($dcClv as $documento){
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
 
	//DETALLE DE PRODUCTOS
	if($cClv[0]['tipoProducto'] == 'IAP'  && count($dClv) > 0){
		echo '<fieldset>
				<legend>Composición Plaguicida</legend>;
			      	<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Ingrediente activo</label></td> 
							<td><label>Concentración</label></td>
						</tr>';
				$i=1;
				
				foreach ($dClv as $detalleProducto){
					echo '<tr>
							<td>'.$i.'</td>
							<td>' . $detalleProducto['ingredienteActivo'] . ' </td>
						  	<td>' . number_format($detalleProducto['concentracion'], 2) . ' '. $detalleProducto['unidadMedida'] . ' </td>
						</tr>';			
					$i++;
					
				}
		echo '</table>
			</fieldset>';
	}
	
	if($cClv[0]['tipoProducto'] == 'IAV' && count($dClv) > 0) {
		$i=1;
		
		echo '<fieldset>
				<legend>Composición Veterinario</legend>;
					<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Nombre</label></td>
							<td><label>Cantidad</label></td>
							<td><label>Descripción</label></td>
						</tr>';
		
		foreach ($dClv as $detalleProducto){
			echo '<tr>
					<td>' . $i . '</td>
					<td>' . $detalleProducto['composicionDeclarada'] . ' </td>
					<td>' . number_format($detalleProducto['cantidadComposicion'],2) . ' ' . $detalleProducto['unidadMedida'] . ' </td>
					<td>' . $detalleProducto['descripcionComposicion'] . ' </td>
				  </tr>';
			$i++;
				
		}
		echo '</table>
		</fieldset>';
	}		
	
?>	

</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS PARA CLV -->
<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idCLV;?>"/>
		<input type="hidden" name="tipoSolicitud" value="CLV"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $cClv[0]['idVue'];?>"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="aprobado">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
		</fieldset>
		
		<button id="guardar" type="submit" class="guardar" >Enviar resultado</button>
	</form> 
	
	<fieldset id="formularioOperador">
		<legend>Datos del Fabricante/Formulador</legend>
			 <div data-linea="1">
			 	<label>Fabricante/Formulador:</label>
				<select id="fabricanteFormuladorClv" name="fabricanteFormuladorClv">
					<option value="">Seleccione....</option>
					<?php
						while ($fila = pg_fetch_assoc($fabricanteFormulador)){
							echo '<option data-direccion="'.$fila['pais_origen'].'" value="'.$fila['nombre'].'">'.$fila['nombre'].' - '.$fila['pais_origen'].'</option>';
						}
					?>
				</select>
			</div>
			<label>País: </label><div id="paisCertificadoClv"></div>
			<br/><label>Dirección: </label><div id="direccionCertificadoClv"></div>
	</fieldset>
</div>

<div class="pestania">

	<fieldset id="plaguicidas">
		 <legend>Información del producto plaguicida</legend>
		  
		 	<div data-linea="1">
				<label>Tipo de producto: </label> Plaguicida
			</div>
			<div data-linea="2">
				<label>Tipo: </label> <?php echo $cClv[0]['tipoDatoCertificado']; ?>
			</div>
		 	<div data-linea="3">
				<label>Producto: </label> <?php echo $cClv[0]['nombre_producto']; ?>
			</div>
			<div data-linea="4">
				<label>Codigo producto: </label> <?php echo $pClv[0]['codigo_producto']; ?>
				</div>
			<div data-linea="5">
			    <label>Subpartida: </label> <?php echo $cClv[0]['subpartida']; ?>
			</div>
			<div data-linea="6">
				<label>Formulación VUE: </label>  <?php echo $pClv[0]['formulacion']; ?>
			</div>
			<div data-linea="7">
				<label>Formulación GUIA: </label>  <?php echo $pClv[0]['formulacionGuia']; ?>
			</div>
	</fieldset>

	<form id='abrirClv' data-rutaAplicacion='certificadoLibreVenta' data-opcion='actualizarClv' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			
		<input type="hidden" id="idClv" name="idClv" value="<?php echo $idCLV; ?>" />
		<input type="hidden" id="nombreOperador" name="nombreOperador"/>
		<input type="hidden" id="direccionOperador" name="direccionOperador"/>
		<input type="hidden" id="tipoProductoClv" name="tipoProductoClv" value="<?php echo $cClv[0]['tipoProducto']; ?>" />

		<fieldset id="veterinarios">
			 <legend>Información del producto veterinario</legend>

				<div data-linea="1">
					<label>Tipo de producto: </label> Veterinario
				</div>
				<div data-linea="2">
					<label>Tipo: </label> <?php echo $cClv[0]['tipoDatoCertificado']; ?>
				</div>
			 	<div data-linea="3">
					<label>Producto: </label> <?php echo $cClv[0]['nombre_producto']; ?>
				</div>
				<div data-linea="4">
					<label>Pais Origen: </label> Ecuador
				</div>
				<div data-linea="5">
				    <label>Subpartida: </label> <?php echo $pClv[0]['subpartida']; ?>
				</div>
				<div data-linea="6">
					<label>Forma farmacéutica: </label> <?php echo $pClv[0]['formulacionGuia']; ?>
				</div>
				<div data-linea="7">
					<label>Clasifición: </label> <?php echo $pClv[0]['clasificacion']; ?>
				</div>		
				<div data-linea="8">
					<label>Composición: </label> <?php echo $pClv[0]['composicionGuia']; ?>
				</div>
	</fieldset>
	
	<fieldset id="veterinariosDetalle">
		<legend>Datos producto</legend>
		<label>Presentación:</label>
		<ol>
			<?php
				
				while ($fila = pg_fetch_assoc($presentacionProducto)) {
					echo '<li>'.$fila['presentacion'].' '.$fila['unidad_medida'] .'</li>';
				}
			?>
		</ol>
			
		<label>Aplicado a:</label>
		<ol>
			<?php
				$usoProducto = array();
				$aplicacionUso = array();
				
				while ($fila = pg_fetch_assoc($especieProducto)) {
					$usoProducto[] = $fila['nombre_uso'];
					if($fila['id_especie']){
						$aplicacionUso[] =  $fila['nombre'];
					}
					
				}
				
				$aplicacionUso = array_unique($aplicacionUso);
				foreach ($aplicacionUso as $aplicado){
					echo '<li>'.$aplicado.'</li>';
				}
			?>
		</ol>
	
		<label>Uso:</label>
		<ol>
			<?php
				$usoProducto = array_unique($usoProducto);
				foreach ($usoProducto as $uso){
					echo '<li>'.$uso.'</li>';
				}
			?>
		</ol>
	</fieldset>
	
	<fieldset>
		<legend>Información de la composición del producto:</legend>
			<div data-linea="1">
				<table style="width:100%">
					<thead>
						<tr>
							<th>Nombre composición</th>
							<th>Descripción composición</th>
							<th>Cantidad</th>
							<th>Unidad</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						$composicionProducto = $crq->listarComposicionProductosInocuidad($conexion, $cClv[0]['id_producto']);
						
						while ($fila = pg_fetch_assoc($composicionProducto)){
							echo '<tr>
									<td>'. $fila['tipo_componente'] .'</td>
									<td>'.$fila['ingrediente_activo'].'</td>
									<td>'.$fila['concentracion'].'</td>
									<td>'.$fila['unidad_medida'].'</td>
								</tr>';
						}
					?>
					</tbody>
				</table>
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Detalles del producto</legend>
			<div data-linea="1">
				<label>Nombre comercial </label> <?php echo $pClv[0]['producto'];?>
			</div>
			<div data-linea="2">
				<label>Número registro </label> <?php echo $pClv[0]['numero_registro'];?>
			</div>

			<div data-linea="3">
				<label>Fecha  de inscripción </label> <?php echo date('Y/m/d',strtotime($pClv[0]['fecha_registro']));?>
			</div>
			
			<div data-linea="3">
				<label>Fecha de vencimiento </label><?php echo ($cClv[0]['tipoProducto'] == 'IAV'? date('Y/m/d',strtotime(date("Y/m/d")."+ 1 year" )) : date('Y/m/d',strtotime(date("Y/m/d")."+ 6 month" )));?>
			</div>

			<div data-linea="4">
				<label>Observación</label> 
					<input type="text" id="observacion" name="observacion" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
			</div>

	</fieldset>
		
			<button id="btn_guardar" type="submit" name="btn_guardar" class="guardar">Guardar</button>
	</form>
</div>

<script type="text/javascript">

$(document).ready(function(){

	construirAnimacion($(".pestania"));	
	distribuirLineas();

	$('#guardar').hide();
	$('#formularioOperador').hide();
	$('button.bsig').attr("disabled","disabled");
	$('button.bsig').first().removeAttr("disabled");
	$("#veterinarios").hide();
	$("#veterinariosDetalle").hide();
	$("#plaguicidas").hide();

});

$("#resultadoDocumento").change(function(){
	if($("#resultadoDocumento option:selected").val() == 'aprobado'){
		$('#guardar').hide();
		$('#formularioOperador').show();
		$('#fabricanteFormuladorClv').val('');
	}else{
		$('#guardar').show();
		$('#formularioOperador').hide();
		$('button.bsig').attr("disabled","disabled");
		$('button.bsig').first().removeAttr("disabled");
	}
});

	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	$("#abrirClv").submit(function(event){
		event.preventDefault();
		
		chequearCamposGuardar(this);

		if($("#estado").html()=="Los datos han sido ingresados satisfactoriamente."){
			ejecutarJson($('#evaluarDocumentosSolicitud'));
		}
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccionDocumental(form){

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
		
		if($("#resultadoDocumento").val() == 'subsanacion'){
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

	$("#fabricanteFormuladorClv").change(function(){
		if($("#fabricanteFormuladorClv option:selected").val() != ''){
			$('button.bsig').removeAttr("disabled");
			$('button.bsig').last().attr("disabled","disabled");

			$('#direccionCertificadoClv').html($('#fabricanteFormuladorClv option:selected').attr('data-direccion'));
			$('#paisCertificadoClv').html($('#fabricanteFormuladorClv option:selected').attr('data-direccion'));

			$('#nombreOperador').val($("#fabricanteFormuladorClv").val());
			$('#direccionOperador').val($('#fabricanteFormuladorClv option:selected').attr('data-direccion'));

			if($("#tipoProductoClv").val()!='IAP'){
				$("#veterinarios").show();
				$("#veterinariosDetalle").show();
				$("#plaguicidas").hide();
			}

			if($("#tipoProductoClv").val()!='IAV'){
				$("#veterinarios").hide();
				$("#veterinariosDetalle").hide();
				$("#plaguicidas").show();
			}
		}else{
			$('button.bsig').attr("disabled","disabled");
			$('button.bsig').first().removeAttr("disabled");
			$("#veterinarios").hide();
			$("#veterinariosDetalle").hide();
			$('#nombreOperador').val("");
			$('#direccionOperador').val("");
		}
	});

	function chequearCamposGuardar(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
        
		if(!$.trim($("#resultadoDocumento").val())){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
			error = true;
			$("#observacionDocumento").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson(form);
		}else{				
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
	}
	
</script>