<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorVacaciones.php';

	
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$anioInicial = 2022;
	$anioActual = date('Y') + 1;
		
?>

<header>

	<h1>Histórico vacaciones</h1>

	<nav>
	
	<form id="listarHistoricoCronogramaVacaciones" data-rutaAplicacion="vacacionesPermisos" data-opcion="listaHistoricoCronogramaVacaciones" data-destino="tabla">
		
		<table class="filtro" style="width: 500px;">
		
			<tr>
				<th>Año: </th>
					<td colspan="3">
						<select id="bAnio" name="bAnio" style="width: 100%;">
							<option value="" selected="selected">Seleccione....</option>
							<?php 
								for ($i = $anioInicial; $i <= $anioActual ; $i++) {
									echo '<option value="'.$i.'">'.$i.'</option>';
								}		
							?>
						</select>
					</td>
			</tr>
			<tr>
				<th>Identificador: </th>
					<td colspan="3">
						<input type="text" id="bIdentificador" name="bIdentificador" style="width: 100%;" >
					</td>
			</tr>
			<tr>
				<th>Nombres: </th>
					<td colspan="3">
						<input type="text" id="bNombre" name="bNombre" style="width: 100%;" >
					</td>
			</tr>							
			<tr>	
				<td colspan="5">
					<button>Filtrar</button>
				</td>
			</tr>

		</table>
		
	</form>
		
	</nav>

</header>

<div id="tabla"></div>

<script type="text/javascript">

	$("#listarHistoricoCronogramaVacaciones").submit(function(event){

		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 

		if($("#bAnio").val()==""){	
			error = true;		
			$("#bAnio").addClass("alertaCombo");
		}

		if (!error){
			event.preventDefault();
			abrir($(this),event,false);
		}
		
	});

</script>	

	