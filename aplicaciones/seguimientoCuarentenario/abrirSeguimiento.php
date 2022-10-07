<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$conexion = new Conexion();
$csc = new ControladorSeguimientoCuarentenario();

$usuario = $_SESSION['usuario'];
$provincia=$_SESSION['nombreProvincia'];
$idDestinacionAduanera=$_POST['id'];
$qDestinacionAduanera = $csc->abrirDatosDDA($conexion, $idDestinacionAduanera);
$qSeguimientoDDA=$csc->abrirSeguimientoDDA($conexion, $idDestinacionAduanera);
?>

<header>
	<h1>Nuevo Seguimiento</h1>
</header>
	<div id="estado"></div>
		<fieldset >
			<legend>Datos Generales</legend>
			<div data-linea="1">
				<label>Razón Social del Importador: </label> <?php echo $qDestinacionAduanera[0]['nombreImportador']; ?> <br/>
			</div>
			<div data-linea="2">
				<label>País de Origen: </label> <?php echo $qDestinacionAduanera[0]['paisExportacion']; ?> <br/>
			</div>
			<div data-linea="3" >
				<table style="width:100%;">
					<thead>	
						<tr>
							<th>#</th>
							<th>Subtipo de Producto</th>
							<th>Producto</th>
							<th>Peso</th>
							<th>Unidad Peso</th>
							<th>Cantidad</th>
							<th>Unidad Medida</th>
						</tr>	
					</thead>
					<tbody>
					<?php 
						$contador=0;
						foreach($qDestinacionAduanera as $fila){
							echo '<tr>
									<td>'.++$contador.'</td>
									<td>'.$fila['nombreSubTipoProducto'].'</td>
									<td>'.$fila['nombreProducto'].'</td>
									<td>'.$fila['peso'].'</td>
									<td>'.$fila['unidadPeso'].'</td>
									<td>'.$fila['unidad'].'</td>
									<td>'.$fila['unidadMedida'].'</td>
									</tr>';
						}
					?>
					</tbody>
				</table>
			</div>
	</fieldset>
	
	<form id='nuevoSeguimiento' data-rutaAplicacion='seguimientoCuarentenario' data-opcion="guardarSeguimientoCuarentenario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value="<?php echo $idDestinacionAduanera;	?>"/>
		<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $provincia;	?>"/>
	<input type="hidden" id="numSeguimientosValidar" name="numSeguimientosValidar" value="<?php echo $qSeguimientoDDA[0]['numeroSeguimientos'];	?>"/>
	<fieldset >
			<legend>Seguimientos</legend>
			<div data-linea="1">
				<label>Número de Seguimientos Planificados: </label> 
				<input type="text" id="numeroSeguimientos" name="numeroSeguimientos" maxlength="4" value="<?php echo $qSeguimientoDDA[0]['numeroSeguimientos'];	?>" onKeyPress='soloNumeros()' data-er="^[0-9]+$" />
			</div>
			<div data-linea="2">
				<label>Número de Plantas Ingresadas: </label>
				<input type="text" id="numeroPlantas" name="numeroPlantas" maxlength="7" value="<?php echo $qSeguimientoDDA[0]['numeroPlantas'];?>" onKeyPress='soloNumeros()' data-er="^[0-9]+$" />
			</div>
			<div data-linea="3" style="width:100%; text-align: center;">
					<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>	
			</div>
			<div data-linea="4" >
				<table style="width:100%;" id="tabla">
					<thead>	
						<tr>
						<th>Número Seguimiento</th>
							<th>Fecha</th>
							<th>Resultado Inspección</th>
							<th>Observación</th>			
						</tr>	
					</thead>
					<tbody id="tablaDetalle">
					<?php 
						$qSeguimientosCuarentenarios = $csc->obtenerSeguimientosCuarentenariosDDA($conexion, $idDestinacionAduanera);
						foreach($qSeguimientosCuarentenarios as $fila){
							echo '<tr>
									<td>'.$fila['numeroSeguimiento'].'</td>
								  	<td>'.$fila['fechaSeguimiento'].'</td>
									<td>'.$fila['resultadoInspeccion'].'</td>
									<td>'.$fila['observacionSeguimiento'].'</td>
								</tr>';
					}
					?>
					</tbody>
				</table>
			</div>
	</fieldset>
	</form>
	<form id='nuevoSeguimientoCierre' data-rutaAplicacion='seguimientoCuarentenario'  data-opcion="guardarCierreCuarentenario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value="<?php echo $idDestinacionAduanera;	?>"/>
	<fieldset>
			<legend>Cierre Cuarentenario</legend>
			<div data-linea="1">
				<label>Cantidad de Productos al Cierre: </label> 
				<input type="text" id="cantidadProductosCierre" name="cantidadProductosCierre" maxlength="7" value="<?php echo $qSeguimientoDDA[0]['cantidadProductoCierre'];	?>" onKeyPress='soloNumeros()' data-er="^[0-9]+$"/>
			</div>
			<div data-linea="2">
				<label>Fecha de Cierre: </label>
				<input type="text" id="fechaCierre" name="fechaCierre" readonly="readonly" value="<?php echo $qSeguimientoDDA[0]['fechaCierre'];?>" />
			</div>
			<div data-linea="3">
				<label>Observaciones: </label>
				<input type="text" id="observaciones" name="observaciones" maxlength="512" value="<?php echo $qSeguimientoDDA[0]['observacionCierre'];?>" />
			</div>
	</fieldset>
	<button type="submit" id="btnCierre"  name="btnCierre" class="guardar" >Cierre</button>	
	</form>
<script type="text/javascript">
var estadoSeguimiento = <?php echo json_encode($qSeguimientoDDA[0]['estadoSeguimiento']);?>;
	$(document).ready(function(){
		distribuirLineas();
		if(estadoSeguimiento=='cerrado'){
			$("#numeroSeguimientos").attr('disabled','disabled');
			$("#numeroPlantas").attr('disabled','disabled');
			$("#btnGuardar").attr('disabled','disabled');
			$("#cantidadProductosCierre").attr('disabled','disabled');
			$("#fechaCierre").attr('disabled','disabled');
			$("#observaciones").attr('disabled','disabled');
			$("#btnCierre").attr('disabled','disabled');
		}
		if ($('#tablaDetalle >tr').length == $('#numSeguimientosValidar').val() && $('#numSeguimientosValidar').val()!=''){
			$('#btnGuardar').attr('disabled','disabled');
			$('#numeroSeguimientos').attr('disabled','disabled');
			$('#numeroPlantas').attr('disabled','disabled');
		}
		$('#tablaDetalle >tr').length == 0 ? $("#tabla").remove():"";
	});

	$("#nuevoSeguimiento").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#numeroPlantas").val() ==''  || !esCampoValido("#numeroPlantas")){	
			error = true;		
			$("#numeroPlantas").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el número de plantas.').addClass("alerta");
		}
		
		if($("#numeroSeguimientos").val() == '' || !esCampoValido("#numeroSeguimientos")){	
			error = true;		
			$("#numeroSeguimientos").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el número de seguimientos planificados.').addClass("alerta");
		}
		
		if (!error){	
			$("#numSeguimientosValidar").val($("#numeroSeguimientos").val());
			ejecutarJson("#nuevoSeguimiento");
		}	
	});

	$("#fechaCierre").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	function soloNumeros() {
		if ((event.keyCode < 48) || (event.keyCode > 57))		 
		event.returnValue = false;
	}

	$("#nuevoSeguimientoCierre").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#observaciones").val() ==''){	
			error = true;		
			$("#observaciones").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la observación.').addClass("alerta");
		}

		if($("#fechaCierre").val() ==''){	
			error = true;		
			$("#fechaCierre").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la fecha de cierre.').addClass("alerta");
		}

		if($("#cantidadProductosCierre").val() == '' || !esCampoValido("#cantidadProductosCierre")){	
			error = true;		
			$("#cantidadProductosCierre").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la cantidad productos al cierre.').addClass("alerta");
		}

		if ($('#tablaDetalle >tr').length != $('#numSeguimientosValidar').val() || $('#tablaDetalle >tr').length==0){
			 error = true;	
			 $("#estado").html('Para realizar el cierre cuarentenario se debe cumplir con el número de seguimientos planificados.').addClass("alerta");
		}
	
		if (!error){  
			ejecutarJson("#nuevoSeguimientoCierre");	
		}	
	});
	
</script>