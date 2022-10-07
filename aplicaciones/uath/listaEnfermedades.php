<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
	$identificador=$_SESSION['usuario'];
	$usuario_seleccionado=$_SESSION['usuario_seleccionado'];
	
	?>
<header>
		<h1>Enfermedades <?php echo $_SESSION['nombre_familiar_seleccionado'];?></h1>
			<nav>
				<a id="_nuevo" data-rutaaplicacion="uath" data-opcion="nuevoEnfermedad" data-destino="detalleItem" href="#">Nuevo</a>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listaEnfermedades" data-destino="listadoItems" href="#">Actualizar</a>
				<a id="_eliminar" data-rutaaplicacion="uath" data-opcion="eliminarEnfermedad" data-destino="detalleItem" href="#">Eliminar</a>
				<a id="_regresar" data-rutaaplicacion="uath" data-opcion="listaFamiliares" data-destino="listadoItems" href="#">Regresar Familares</a>
			</nav>
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Discapacidad</th>
					<th>Porcentaje</th>
					<th>Carnet</th>
				</tr>
			</thead>
			<?php 
				$cd = new ControladorCatastro();
				$res = $cd->obtenerListaDiscapacidad($conexion, $usuario_seleccionado);
				$contador = 0;
				
				if(pg_num_rows($res) != 0){
				while($discapacidad = pg_fetch_assoc($res))
				{
						echo '
							<tr 	id="'.$discapacidad['id_enfermedades_familiares'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="modificarEnfermedad" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem"
							>
								<td>'.++$contador.'</td>
								<td style="white-space:nowrap;"><b>'.$discapacidad['descripcion'].'</b></td>
								<td>'.$discapacidad['porcentaje_discapacidad'].'</td>
								<td>'.$discapacidad['carnet'].'</td>
							</tr>';

				}
				}
				
				
			?>
		</table>

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();
		$('#detalleItem').html('<div class="mensajeInicial">Arrastre aquí una ficha para editarla.</div>');
	
	});
	

$('#_actualizar').click(function(event){
	event.preventDefault();
	abrir($('#_actualizar'),event, false);
});

$('#_regresar').click(function(event){
	abrir($('#_regresar'),event, false);
});


	
	
	
	</script>
