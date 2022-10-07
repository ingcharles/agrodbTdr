<header>
	<h1>Registros Proforma</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="poa" data-opcion="listadoRegistrosPOAFiltrados" data-destino="tabla">
			<table class="filtro">
				<tr>
					<th>Mostrar</th>
					<td></td>
					<td>
					</td>
					<td colspan="5"><button>Recuperar registros Proforma</button></td>
				</tr>
			</table>
		</form>
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#filtrar").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
		$("#detalleItem").html('<div class="mensajeInicial">Registros de matriz de presupuestos.</div>');
	});
</script>