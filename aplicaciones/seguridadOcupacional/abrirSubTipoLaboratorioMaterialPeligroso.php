<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	
	$idLaboratorioMaterialPeligroso = $_POST['idSubtipoLaboratorio'];
	$idLaboratorio = $_POST['idLaboratorio'];
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	
	$qLaboratorioMaterialPeligroso=$so->buscarSubTipoLaboratorioMaterialPeligroso($conexion, $idLaboratorioMaterialPeligroso);
	$filaLaboratorioMaterialPeligroso=pg_fetch_assoc($qLaboratorioMaterialPeligroso);
	$filaLaboratorioMaterialPeligroso['nombre_laboratorio'];

	?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Registro Laboratorio</h1>
	</header>
	<div id="estado"></div>

	<form id="regresar" data-rutaAplicacion="seguridadOcupacional" data-opcion="abrirLaboratorioMaterialPeligroso" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idLaboratorio;?>"/>
		<button class="regresar" >Regresar a Coordinación de Laboratorios</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarSubTipoLaboratorio" data-rutaAplicacion="seguridadOcupacional" data-opcion="modificarSubTipoLaboratorioMaterialPeligroso" >
					<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
					<input type="hidden" id="idLaboratorioMaterialPeligroso" name="idLaboratorioMaterialPeligroso" value="<?php echo $idLaboratorioMaterialPeligroso;?>">
					<fieldset>
						<legend>Datos Laboratorio</legend>	
						<div data-linea="1">			
							<label>Nombre laboratorio:</label> 
							<input type="text" id="nombreLaboratorioUno" name="nombreLaboratorioUno" value="<?php echo $filaLaboratorioMaterialPeligroso['nombre_laboratorio'];?>" disabled="disabled"/>	
						</div>
						<div>
						<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
					</div>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>	
				
</body>
<script>
	$('document').ready(function(){
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarSubTipoLaboratorio").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreLaboratorioUno").val())){
			error = true;
			$("#nombreLaboratorioUno").addClass("alertaCombo");
		}
			
		if (error){
			$("#estado").html("Por favor seleccione todos los campos.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#regresar").submit(function(event){
		event.preventDefault();
				abrir($(this),event,false);
	});

</script>
</html>