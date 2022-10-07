<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
?>

<header>
	<h1>Nuevo Proceso/Nuevo Proyecto</h1>
</header>
<form id="nuevoProceso" data-rutaAplicacion="poa" data-opcion="guardarNuevoProceso" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>

	<fieldset>
		<legend>Seleccione el Tipo</legend>
		<table>
			<tr>
				<td>
					<input type="radio" name="tipo" value="0" checked="checked">
				</td>
				<td>
					<label>Proceso</label>
				</td>		
				<td>
					<input type="radio" name="tipo" value="1">
				</td>
				<td>
					<label>Proyecto</label>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>Descripción</legend>

		<div data-linea="1">
			<input type="text" id="descripcion" name="descripcion" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
	</fieldset>

	<button type="submit" class="guardar
">Generar Proceso</button>

</form>
<script type="text/javascript">

	$("#nuevoProceso").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
			error = true;
			$("#descripcion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($("#estado").html()=='El Proceso ha sido generado satisfactoriamente'){
				$("#_actualizar").click();
			}
		}
	}
</script>
