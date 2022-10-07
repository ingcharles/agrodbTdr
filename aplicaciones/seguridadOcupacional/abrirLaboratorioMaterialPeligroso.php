<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idLaboratorioMaterialPeligroso = $_POST['id'];
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	$cc = new ControladorCatalogos();
	
	$qLaboratorioMaterialPeligroso=$so->buscarLaboratorioMaterialPeligroso($conexion, $idLaboratorioMaterialPeligroso);
	$filaLaboratorioMaterialPeligroso=pg_fetch_assoc($qLaboratorioMaterialPeligroso);
	
	$subtipoLaboratorios=$so->listarSubtipoLaboratorio($conexion, $idLaboratorioMaterialPeligroso);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Registro Coordinación Laboratorio </h1>
	</header>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
		<form id="actualizarLaboratorioMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="modificarLaboratorioMaterialPeligroso" data-accionEnExito="ACTUALIZAR">
			
			
			<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
			<input type="hidden" id="idLaboratorioMaterialPeligroso" name="idLaboratorioMaterialPeligroso" value="<?php echo $idLaboratorioMaterialPeligroso;?>">
		
			<fieldset>
				<legend>Datos Coordinación Laboratorio</legend>	
				<div data-linea="1">			
					<label>Nombre coordinación laboratorio:</label> 
					<input type="text" id="nombreLaboratorioUno" name="nombreLaboratorioUno" value="<?php echo $filaLaboratorioMaterialPeligroso['nombre_laboratorio'];?>" disabled="disabled"/>	
				</div>
				
			<div>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>
			</fieldset>
		</form>
		
		<form id="nuevoSubTipoLaboratorio" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoSubtipoLaboratorioMaterialPeligroso" >
					<input type="hidden" id="idLaboratorioMaterialPeligroso" name="idLaboratorioMaterialPeligroso" value="<?php echo $idLaboratorioMaterialPeligroso;?>">
					
					<fieldset>
						<legend>Nuevo Laboratorio</legend>	
						<div data-linea="1">
							<label for="nombreSubtipo">Nombre laboratorio:</label>
							<input id="nombreSubtipo" name="nombreSubtipo" type="text"  required="required"/>
							<button type="submit" class="mas">Añadir Laboratorio</button>		
						</div>

					</fieldset>
				</form>
				<fieldset>
					<legend>Laboratorios</legend>
					<table id="subTipoLaboratorio">
						<?php 
							while ($subtipoLaboratorio = pg_fetch_assoc($subtipoLaboratorios)){
								echo $so->imprimirLineaSubtipoLaboratorio($subtipoLaboratorio['id_laboratorio'], $subtipoLaboratorio['nombre_laboratorio'],$idLaboratorioMaterialPeligroso, 'seguridadOcupacional');
							}
						?>
					</table>
				</fieldset>
				
				

	</td>
		</tr>
	</table>
<script>
	$('document').ready(function(){
		acciones("#nuevoSubTipoLaboratorio","#subTipoLaboratorio");
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarLaboratorioMaterialPeligroso").submit(function(event){
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
			//ejecutarJson("#actualizarLaboratorioMaterialPeligroso");
			
			/*if( $('#estado').html()=='Los datos han sido actualizados satisfactoriamente' )
				$('#_actualizar').click();*/
		}
	});
</script>
</html>