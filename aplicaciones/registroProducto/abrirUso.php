<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cr = new ControladorRequisitos();

$res = $cr->abrirUsoInocuidad($conexion, $_POST['id']);
$usoProducto = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Uso</h1>
</header>

	<div id="estado"></div>
	
<form id="fUso" data-rutaAplicacion="registroProducto" data-opcion="actualizarUso" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

<fieldset>
		<legend>Uso</legend>
			<input type="hidden" name="idUso" value="<?php echo $usoProducto['id_uso'];?>" />
				
			<div data-linea="1">
					<label for="area">Área</label>
						<select id="area" name="area" disabled="disabled">
								<option value="" selected="selected">Seleccione una dirección...</option>
								<option value="IAP">Registro de insumos agrícolas</option>
								<option value="IAV">Registro de insumos pecuarios</option>
								<option value="IAF">Registro de insumos fertilizantes</option>								
								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
						</select>
			</div>
				
		<label>Plaga Nombre común</label>
		<div data-linea="3">
			<textarea id="nombreComun" name="nombreComun" disabled="disabled" ><?php echo $usoProducto['nombre_comun_uso'];?></textarea>
		</div>

		<label>Plaga Nombre científico</label>
		<div data-linea="2">
			<textarea id="nombreCientifico" name="nombreCientifico" disabled="disabled" ><?php echo $usoProducto['nombre_uso'];?></textarea>
		</div>		
		
</fieldset>	
</form>

</body>

<script type="text/javascript">

var area = <?php echo json_encode($usoProducto['id_area']);?>;

$('document').ready(function(){
	cargarValorDefecto("area","<?php echo $usoProducto['id_area'];?>");
    distribuirLineas();
});	

$("#modificar").click(function(){
	$("select").removeAttr("disabled");
	$("textarea").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr('disabled','disabled');
});

$("#fUso").submit(function(event){

	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#area").val()==""){
		error = true;
		$("#area").addClass("alertaCombo");
	}

	if($("#nombreCientifico").val()==""){
		error = true;
		$("#nombreCientifico").addClass("alertaCombo");
	}

	if($("#area").val() != 'IAV'){
		if($.trim($("#nombreComun").val())==""){
			error = true;
			$("#nombreComun").addClass("alertaCombo");
		}
	}

	if (!error){
		ejecutarJson($(this));
	}else{
		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
	}
});

</script>

</html>
