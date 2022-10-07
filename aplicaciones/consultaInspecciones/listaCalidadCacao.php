<?php 
session_start();
$identificadorUsuario=$_SESSION['usuario'];

?>
<header>
<h1>Calidad de Cacao</h1>
	<nav>
		<form action="aplicaciones/consultaInspecciones/reporteImprimirCalidadCacao.php" method="post">
			<input type="hidden" name="identificadorUsuario" value="<?php echo $identificadorUsuario; ?>" />
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Buscar</th>					
					</tr>
					<tr>
						<td>Fecha Inicio:</td>
						<td><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" required style='width:98%;'></td>
						<td>Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly" required style='width:98%;' ></td>					
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte</button></td>
					</tr>
					<tr>
						<td colspan="4"  align="center" id="estadoError" ></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>	
	$(document).ready(function(){
		distribuirLineas();
		var fecha = new Date();
        fecha.setMonth(fecha.getMonth() - 3);
        $("#fechaInicio").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd",
            defaultDate: -1
        }).datepicker('setDate', fecha);
        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', new Date());
        
	});

</script>