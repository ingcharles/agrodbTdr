<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';

//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado
$conexion = new Conexion();
//$firephp = FirePHP::getInstance(true); borrado	
unset($_SESSION['usuario_seleccionado']);
?>


	<nav>
		<form id="filtrar" data-rutaAplicacion="uath" data-opcion="listaContratoAdmin" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro">
				<tbody>
				<tr>
					<th colspan="3">Buscar Funcionario:</th>
					</tr>
				<tr>
					<td>Número de Cédula:</td>
					<td> <input id="identificador" type="text" name="identificador" maxlength="10">	</td>
				</tr>
				
				<tr>
					<td id="mensajeError"></td>
					<td colspan="5"> <button>Buscar</button>	</td>
				</tr>
				</tbody>
				</table>
		</form>
		
	</nav>


<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();
	});
	


	$("#filtrar").submit(function(event){
		event.preventDefault();
		/*ejecutarJson($(this));*/
		
		if($('#identificador').val().length==10)
		{		
				abrir($('#filtrar'),event, false);
				$("input").attr("disabled","disabled");
				$("select").attr("disabled","disabled");
				$("#modificar").removeAttr("disabled");
				$("#actualizar").attr("disabled","disabled");
		}
		else
		{
			if($('#identificador').val().length>0)
			{
				$('#mensajeError').html('Error con el número');
			}
		}
	});
	$('#identificador').focus(function(event){
		$('#mensajeError').html('');
	}
	
	
	)
	</script>
