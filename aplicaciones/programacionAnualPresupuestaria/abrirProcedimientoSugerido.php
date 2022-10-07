<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$idTipoCompra = $_POST['idTipoCompra'];
	$idProcedimientoSugerido = $_POST['idProcedimientoSugerido'];
	
	$procedimientoSugerido = pg_fetch_assoc($cpp->abrirProcedimientoSugerido($conexion, $idProcedimientoSugerido));
?>

	<header>
		<h1>Procedimiento Sugerido</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirTipoCompra" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idTipoCompra;?>"/>
		<button class="regresar">Regresar a Tipo de Compra</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarProcedimientoSugerido" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarProcedimientoSugerido">
					<input type="hidden" id="idTipoCompra" name="idTipoCompra" value="<?php echo $idTipoCompra;?>">
					<input type="hidden" id="idProcedimientoSugerido" name="idProcedimientoSugerido" value="<?php echo $idProcedimientoSugerido;?>">
					
					<fieldset id="fs_detalle">
						<legend>Procedimiento Sugerido</legend>	
						
						<div data-linea="1">
							<label>Nombre:</label>
								<input type="text" id="nombreProcedimientoSugerido" name="nombreProcedimientoSugerido" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü.\-\ ]+$" required="required" value="<?php echo $procedimientoSugerido['nombre'];?>" disabled="disabled" />
						</div>
						
						<div data-linea="3">
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>
<script type="text/javascript">
						
	$('document').ready(function(){
		acciones("#nuevoProcedimientoSugerido","#detalleProcedimientoSugerido");
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#modificarProcedimientoSugerido").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreProcedimientoSugerido").val()) || !esCampoValido("#nombreProcedimientoSugerido")){
			error = true;
			$("#nombreProcedimientoSugerido").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});
</script>