<?php
session_start();
require_once '../../clases/Conexion.php';

$conexion = new Conexion();

?>

<header>
	<h1>Tipo Depósito</h1>
	<nav>
	<form id="listaDeposito" data-rutaAplicacion="certificadosFitosanitarios" data-opcion="listaDepositoFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<td>RUC:</td>
				<td><input id="ruc" name="ruc" type="text" /></td>	
				<td colspan="5"><button>Filtrar</button></td>	
			</tr>
		</table>
		<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
	</form>
		
	</nav>
</header>

<div id="tabla"></div>

<script>
	$("#listaDeposito").submit(function(e){
		abrir($(this),e,false);
	});

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		
	});
</script>
