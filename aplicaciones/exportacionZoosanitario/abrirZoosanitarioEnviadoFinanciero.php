<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$ci = new ControladorZoosanitarioExportacion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();

$idSolicitud = $_POST['id'];
$identificadorInspector = $_SESSION['usuario'];
$condicion = $_POST['opcion'];

$qZoosanitario = $ci->abrirZoo($conexion, $idSolicitud);
$zoosanitario = pg_fetch_assoc($qZoosanitario);

$qZoosanitarioProductos = $ci->abrirZooProductos($conexion,$idSolicitud);

$qDocumentos = $ci->abrirExportacionesArchivos($conexion, $idSolicitud);
//$qOperador = $cr->buscarOperador($conexion, $qExportacion[0]['identificador']);

$estadoActual = $zoosanitario['estado'];

//Obtener monto a pagar

if($estadoActual=='verificacion' || $estadoActual == 'verificacionVUE'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'Zoosanitario', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
	//Obtener monto a pagar
	$qDatosPago = $crs->buscarIdImposicionTasa($conexion, $idGrupo['id_grupo'], 'Zoosanitario', 'Financiero');
	$datosPago = pg_fetch_assoc($qDatosPago);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'Importación');
}

//Obtener datos de entidades bancarias
//$qEntidadesBancarias = $cc->listarEntidadesBancariasAgrocalidad($conexion);

?>

<header>
	<h1>Solicitud Exportación Zoosanitario</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">
	
<?php 
		if($zoosanitario['id_vue'] != ''){
			echo '<fieldset>
				<legend>Información de la Solicitud</legend>
					<div data-linea="1">
						<label>Identificación VUE: </label> '. $zoosanitario['id_vue'] .'
					</div>
			</fieldset>';
		}
	?>
	
	<fieldset>
			<legend>Información del exportador</legend>
			
			<div data-linea="4">
				<label>Nombre: </label> <?php echo $zoosanitario['nombre_importador']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Representante técnico: </label> <?php echo $zoosanitario['nombre_tecnico'] . ' ' . $zoosanitario['apellido_tecnico']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales de exportación</legend>
			<div data-linea="5">
				<label>País destino: </label> <?php echo $zoosanitario['pais_destino']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Dirección: </label> <?php echo $zoosanitario['direccion_importador']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Puerto embarque: </label> <?php echo $zoosanitario['puerto_embarque']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Medio de transporte: </label> <?php echo $zoosanitario['transporte']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Uso producto: </label> <?php echo $zoosanitario['nombre_uso']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Bultos: </label> <?php echo $zoosanitario['numero_bultos'] . ' ' . $zoosanitario['descripcion_bultos']; ?> 
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Información de inspección</legend>
			
			<div data-linea="19">
				<label>Código de sitio: </label> <?php echo $zoosanitario['codigo_sitio']; ?> 
			</div>
			<div data-linea="10">
				<table>
					
						<?php 
							foreach ($qZoosanitarioProductos as $productosZoo){
								$qAreaOperacion = $cr->buscarAreaOperacionXCodigoSitio($conexion, $zoosanitario['identificador_operador'], $zoosanitario['codigo_sitio'], $productosZoo['idProducto']);
								
								for ($i=0;$i<count($qAreaOperacion);$i++){
									echo '<tr><td><label>Área de inspección: </label>' . $qAreaOperacion[$i]['nombreArea'].' - '.$qAreaOperacion[$i]['tipoArea'].'</td></tr>';	
								}
							}
						?>
					
				</table>
			</div>
			
				<?php 
					if($zoosanitario['fecha_inspeccion'] != ''){
						echo '<div data-linea="12">
							<label>Fecha de inspección: </label>' . $zoosanitario['fecha_inspeccion'] . 
						'</div>';
					}
				?>
				
			<div data-linea="13">
				<label>Observación: </label> <?php echo $zoosanitario['observacion']; ?> 
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
	
	
	//DETALLE DE PRODUCTOS
	
	$i=1;
		
	echo'<div id="documentos" >
			<fieldset>
				<legend>Datos del producto</legend>
					<form id="f_'.$i.'" data-rutaAplicacion="../general" data-opcion="abrirPdfFtp" data-destino="documentoAdjunto" data-accionEnExito="ACTUALIZAR">
						<table>
							<tr>
								<td><label>#</label></td>
								<td><label>Nombre Producto</label></td>
								<td><label>Partida arancelaria</label></td>';
	
					foreach ($qZoosanitarioProductos as $zooProductos){
						if($zooProductos['sexo'] != '' && $zooProductos['edad'] != 0){
							echo '<td><label>Sexo</label></td>
								 <td><label>Edad</label></td>';
							break;
						}
					}
					
					echo '<td><label>Cantidad física</label></td>';
						echo '</tr>';
	
		foreach ($qZoosanitarioProductos as $zooProductos){
			echo '<tr>
					<td>'.$i.'</td>
					<td>' . $zooProductos['nombreProducto'] . '</td>
					<td>' . $zooProductos['partidaArancelaria'] . '</td>';
			
			if($zooProductos['sexo'] != ''){
				echo '<td>' . $zooProductos['sexo'] . '</td>';
			}
			if($zooProductos['edad'] != 0){
				$qEdad = $cc->buscarRangoEdadesAnimal($conexion, $zooProductos['edad']);
				echo '<td>' . pg_fetch_result($qEdad, 0, 'nombre') . '</td>';
			}
				
			echo   '<td>' . $zooProductos['cantidadFisica'].' '. $zooProductos['unidadFisica'] . '</td>';
			
			$i++;
		}
		
		//</td></tr>
		echo '</fieldset>';
	
	echo '</table>
	</form>
	</fieldset>
	</div>';
?>	

</div>
<!-- SECCION DE REVISIÓN DE PAGOS PARA ZOO -->
<div class="pestania">	 
	
	<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Zoosanitario"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacionVUE"/>
		<input type="hidden" name="idVue" value="<?php echo $zoosanitario['id_vue'];?>"/>
		<input type="hidden" name="idOperador" value="<?php echo $zoosanitario['identificador_operador'];?>"/>
		
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
		<input type="hidden" name="tipoSolicitud" value="Zoosanitario"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="inspeccion"/>
		<input type="hidden" name="idOperador" value="<?php echo $qExportacion[0]['identificador'];?>"/>
		<input type="hidden" name="idVue" value="<?php echo $zoosanitario['id_vue'];?>"/>
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
						<input type="text" id="montoRecaudado" name="montoRecaudado" placeholder="Ej: 153" data-er="^[0-9]+(\.[0-9]{1,3})?$" value="<?php echo $datosPago['monto_recaudado']; ?>"/>
				</div>
				
				<div data-linea="7">
					<label>Fecha de facturación: </label>
						<input type="text" id="fechaFacturacion" name="fechaFacturacion" value="<?php echo $datosPago['fecha_facturacion']; ?>"/>
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
var estado= <?php echo json_encode($zoosanitario['estado']) ?>;
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