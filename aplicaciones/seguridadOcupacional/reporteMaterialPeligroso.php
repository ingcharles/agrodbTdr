<?php 
session_start();
?>
<header>
<h1>Reporte Material Peligroso</h1>
	<nav>
		<form id="filtrarMaterialPeligroso" data-rutaAplicacion='seguridadOcupacional' action="aplicaciones/seguridadOcupacional/reporteImprimirMaterialPeligroso.php" target="_self" method="post">
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Reporte material peligroso</th>					
					</tr>
					
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte Excel</button></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>

	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#filtrarMaterialPeligroso").submit(function(event){ 
		ejecutarJson(form);      	
});
</script>