<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ccert = new ControladorCertificadoCalidad();
$crs = new ControladorRevisionSolicitudesVUE();


$operaciones = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);
$identificadorInspector = $_SESSION['usuario'];
$idGrupo = $_POST['nombreOpcion'];

$registros = $ccert->buscarLotesCertificadoCalidad($conexion,$operaciones);

while($fila = pg_fetch_assoc($registros)){
	$certificados[] = array(identificador => $fila['identificador_exportador'], numeroLote => $fila['numero_lote'], idProducto => $fila['id_producto'], 
							nombreProducto => $fila['nombre_producto'], nombreVariedad => $fila['nombre_variedad_producto'], nombreCalidad => $fila['nombre_calidad_producto'], 
							estado => $fila['estado'], nombreOperador =>$fila['razon_social_exportador']);
}

//Obtener monto a pagar
if($certificados[0]['estado']=='verificacion'){
	$qMonto = $crs->buscarIdImposicionTasa($conexion, $idGrupo, 'certificadoCalidad', 'Financiero');
}


//Obtener datos de entidades bancarias
$qEntidadesBancarias = $cc->listarEntidadesBancariasAgrocalidad($conexion);

?>

<header>
	<h1>Grupo de operaciones</h1>
</header>

<div id="estado"></div>

<p>Las <b>operaciones</b> agrupadas son: </p>

<fieldset>
	<legend><?php echo 'Operador: '.$certificados[0]['nombreOperador']?></legend>

<table>
	<thead>
		<tr>
			<th># Lote</th>
			<th>Producto</th>
			<th>Variedad</th>
			<th>Calidad</th>
			
		</tr>
	</thead>
	<tbody>
	</tbody>
	

	<?php 
	
	foreach ($certificados as $certificado){
		echo '<tr id="'.$certificado['identificador'].'">
					
					<td><b>'.$certificado['numeroLote'].'</b></td>
					<td>'.$certificado['nombreProducto'].'</td>
					<td>'.$certificado['nombreVariedad'].'</td>
					<td>'.$certificado['nombreCalidad'].'</td>
					
				</tr>';
	}
	?>
	
	</table>
	
</fieldset>



<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $operaciones;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificadoCalidad"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacion"/>
		<!-- input type="hidden" name="idVue" value="< ?php echo $operadores[0]['idVue'];?>"/-->
		<input type="hidden" name="idOperador" value="<?php echo $certificados[0]['identificador'];?>"/>
		
		<fieldset>
			<legend>Valor a cancelar</legend>
				<div data-linea="11" >		
									
					<label>Monto: </label>
						<input type="text" id="monto" name="monto" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
						
						<p class="nota">Por favor ingrese el valor a cancelar por las operaciones.</p>
				</div>
		</fieldset>			
		<button type="submit" class="guardar">Autorizar pago</button>
</form>	
	

<form id="verificarPago" data-rutaAplicacion="revisionFormularios" data-opcion="verificarPagoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $operaciones;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificadoCalidad"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="idOperador" value="<?php echo $certificados[0]['identificador'];?>"/>
		<!-- input type="hidden" name="idVue" value="< ?php echo $operadores[0]['idVue'];?>"/-->
		<input type="hidden" name="idGrupo" value="<?php echo $idGrupo;?>"/>
		
		<fieldset id="factura">
				<legend>Pago de arancel</legend>
					<div data-linea="12" >
						<label>Monto a pagar: </label> $ <?php echo pg_fetch_result($qMonto, 0, 'monto'); ?>
					</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
				<!-- >div data-linea="5">
					<label>Número de transacción: </label>
						<input type="text" id="transaccion" name="transaccion" placeholder="Ej: 153628965" data-er="^[0-9]+$"/>
				</div-->
				
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
					<label>Fecha de facturación: </label>
						<input type="text" id="fechaFacturacion" name="fechaFacturacion" />
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
	
<script type="text/javascript">	
							
	var estado= <?php echo json_encode($certificados[0]['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();

		$("#verificarPago").hide();
		$("#asignarMonto").hide();

		if(estado == 'pago'){
			$("#asignarMonto").show();
		}else if(estado == 'verificacion'){
			$("#verificarPago").show();
		}		

		$("#fechaFacturacion").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });
	});

	$("#asignarMonto").submit(function(event){
		event.preventDefault();
		chequearCamposAsignarMonto(this);
	});

	$("#verificarPago").submit(function(event){
		event.preventDefault();
		chequearCamposVerificarPago(this);
	});

	$("#codigoBanco").change(function(){
    	$('#nombreBanco').val($("#codigoBanco  option:selected").text());

	});

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

		if(!$.trim($("#numeroFactura").val()) || !esCampoValido("#numeroFactura")){
			error = true;
			$("#numeroFactura").addClass("alertaCombo");
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

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	

</script>
	