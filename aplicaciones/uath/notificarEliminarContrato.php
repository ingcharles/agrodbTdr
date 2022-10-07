<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar eliminación</h1>
</header>

<div id="estado"></div>

	<p>El <b>contrato</b> a ser eliminado es: </p>
	
	<?php
		$contratos=explode(",",$_POST['elementos']);
		
		for ($i = 0; $i < count ($contratos); $i++) {
			
			$res = $ce->obtenerDatosContrato($conexion, $contratos[$i]);
			$contrato = pg_fetch_assoc($res);
			
			echo '<fieldset>
					<legend>Contratos</legend>
						<div>
							 <label>Funcionario: </label>' .$contrato['identificador'].' - ' .$contrato['apellido'].' '.$contrato['nombre'] . '<br/>' . 
							'<label>Contrato: </label>' .$contrato['tipo_contrato'].' - ' .$contrato['regimen_laboral']. '<br/>' .
							'<label>Puesto: </label>' .$contrato['nombre_puesto'].' - ' .$contrato['grupo_ocupacional']. '<br/>' .
							'<label>Fechas: </label>' .$contrato['fecha_inicio'].'<b> -> </b>' .$contrato['fecha_fin']. '<br/>' .
					   '</div>
				  </fieldset>';
		}		
		
		$idContrato
	?>
	
 
<form id="eliminarContrato" data-rutaAplicacion="uath" data-opcion="eliminarContrato" data-destino="detalleItem" data-accionenexito="#ventanaAplicacion #filtrar">


			<input type="hidden" name="id" value="<?php echo $_POST['elementos'];?>"/>
			
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar contrato</button>
	
</form>

</body>

<script type="text/javascript">
var array_contrato= <?php echo json_encode($contratos); ?>;

$(document).ready(function(){

	distribuirLineas();
	construirValidador();

	if(array_contrato == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un contrato para eliminar.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarContrato").hide();
	}

});

$("#eliminarContrato").submit(function(event){

	  if($("#observacion").val()==""){
	    	$("#observacion").focus();
	    	$("#observacion").addClass("alertaCombo");
	        alert("Debe ingresar una observación");
	        return false;
	  }else{
		  	event.preventDefault();
			ejecutarJson($(this));
	  }
});

</script>

</html>