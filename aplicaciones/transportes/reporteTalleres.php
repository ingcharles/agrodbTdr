<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();

$talleres = $cv->abrirDatosTalleres($conexion,$_SESSION['idLocalizacion']);

?>

<header>
	<h1>Histórico talleres</h1>
	<nav>
	<form id="reporteTalleres" data-rutaAplicacion="transportes" data-opcion="listaTalleresReporte" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>

				<td>gasolinera:</td>
				<td>
					<select id="talleres" name="talleres">
						<option value="" >Talleres....</option>
							<?php 
								while($fila = pg_fetch_assoc($talleres)){
									echo '<option value="' . $fila['id_taller'] . '" >' . $fila['nombretaller'] . '</option>';					
								}
							?>
					</select>
				</td>
				<td>dirección:</td>
				<td><input name="direccion" type="text" /></td>		
			</tr>

			<tr>
				<th>Mostrar</th>
				<td>estado:</td>
				<td><select name="estado">
					<option value="">Todos</option>
					<option value="1">Sin notificar</option>
					<option value="1">Pendientes</option>
					<option value="1">Negados</option>
					<option value="1">Aprobados</option>
				</select>
				</td>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#reporteTalleres").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});
	</script>
