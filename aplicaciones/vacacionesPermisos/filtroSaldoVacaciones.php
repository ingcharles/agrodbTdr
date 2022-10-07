<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';

	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	
	$identificador = $_SESSION['usuario'];
	
	$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
		
?>

<header>

	<h1>Saldo vacaciones</h1>

	<nav>
	
	<form id="listarSaldoVacaciones" data-rutaAplicacion="vacacionesPermisos" data-opcion="listaSaldoVacaciones" data-destino="tabla">
		
		<table class="filtro">
		
			<tr>
				<th>Cédula</th>
					<td>
						<input id="identificador" name="identificador" type="text"  style="width: 100%;"/>
					</td>
				<th>Estado</th>
					<td>
						<select id="estadoSaldo" name="estadoSaldo" style="width: 100%;">
							<option value="TRUE">Activo</option>
							<option value="FALSE">Inactivo</option>
						</select>
					</td>		
			</tr>
			
			<tr>
				<th>Apellidos</th>
					<td>
						<input id="apellidoUsuario" name="apellidoUsuario" type="text"  style="width: 100%;"/>
					</td>
				<th>Nombres</th>
					<td>
						<input id="nombreUsuario" name="nombreUsuario" type="text"  style="width: 100%;"/>
					</td>		
			</tr>
			
			<tr>
				<th>Área pertenece</th>
					<td colspan="3">
						<select id="area" name="area" style="width: 100%;">
							<option value="" selected="selected">Área....</option>
							<?php 
								while($fila = pg_fetch_assoc($area)){
									echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
								}			
							?>
						</select>
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

$("#listarSaldoVacaciones").submit(function(event){

	event.preventDefault();
	abrir($(this),event,false);
	
});

</script>	

	