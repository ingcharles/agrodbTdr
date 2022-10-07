<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$idPuesto=$_POST['id'];

$qlistarFunciones= $cc->listarFunciones($conexion);

$aListarFunciones= array();

while($fila = pg_fetch_assoc($qlistarFunciones)){
	
	$aListarFunciones[] = array (value => $fila['descripcion'],label => $fila ['descripcion'],idfuncion=>$fila ['id_funcion']);
}

//print_r($aListarFunciones); 

?>

<header>
	<h1 style="font-size: 22px;">Añadir nueva función</h1>
</header>

<div id="estado"></div>

<form id='nuevoPuestoFuncion' data-rutaAplicacion='uath' data-opcion='guardarFuncion'>

	<input type="hidden" id="idPuesto" name="idPuesto" value="<?php echo $idPuesto ?>" />

	<fieldset>
	
		<legend>Selección</legend>
		<div data-linea="8">
			<label>Digite la función:</label><input type="text" id="nombreFuncion" name="nombreFuncion" required="required"/>
		</div>
		
		<input type="hidden" id="idFuncion" name="idFuncion" />
		
	</fieldset>
		
	<button type="submit" class="mas">Añadir función</button>
		
	<p>

</form>

<fieldset>
	<legend>Funciones</legend>
	<table id="codigoPF">

	<?php $qListarFunciones=$cc->ListarFuncionesXPuesto($conexion, $idPuesto);
	
	while($listarFuncion = pg_fetch_assoc($qListarFunciones)){
	
		echo $cc->imprimirFuncionesXPuesto($idPuesto, $listarFuncion['id_funcion'],$listarFuncion['descripcion']);		
	}
	?>
	
	</table>
</fieldset>	

		

<script type="text/javascript">


$(document).ready(function(){
	distribuirLineas();
	acciones("#nuevoPuestoFuncion","#codigoPF");	
});


/*
$("#nuevoPuestoFuncion").submit(function(event){
    event.preventDefault();
    $(".alertaCombo").removeClass("alertaCombo");
  	var error = false;

		if($("#nombreFuncion").val()==""){	
			error = true;		
			$("#nombreFuncion").addClass("alertaCombo");
			
		}

		if (error){
			$("#estado").html("Por favor seleccione una variedad.").addClass('alerta');
		}
});*/

/*$("#nuevoPuestoFuncion").submit(function(event){
	  
	var error = false;
	   	 if($("#codigoPF >tbody >tr").length != 0){
	   		event.preventDefault();
	   	    $(".alertaCombo").removeClass("alertaCombo");
	   	  	
	   	    	if($("#nuevaFuncion").val()==""){	
	   				error = true;		
	   				$("#nuevaFuncion").addClass("alertaCombo");
	   				
	   			}
	   			if (error){
	   				$("#estado").html("Por favor ingrese una función.").addClass('alerta');
	   			}else{
	   				$('#nuevoPuestoFuncion').attr('data-opcion','guardarfuncio');
	   		   	   	ejecutarJson($(this));  
		   			}
	   	

	  	}
	});*/


var datos=<?php echo json_encode($aListarFunciones); ?>

$(function(){
	var data = datos;

	$("#nombreFuncion").autocomplete({
		source: data,
		select: function(event, ui){
//event.preventDefault();
$('#idFuncion').val(ui.item.idfuncion);

			},change:function(event, ui){
				  if (ui.item == null || ui.item == undefined) {
					  $('#idFuncion').val("");
				  }
				}
	});
	
});

</script>
