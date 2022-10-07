<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$car = new ControladorAreas();

if ($_POST['nombreDelEmpleado']!='')
	$nombre=$_POST['nombreDelEmpleado'];
if($_POST['apellido']!='' )
	$apellido=$_POST['apellido'];
if($_POST['identificador']!='')
{
	$identificador=$_POST['identificador'];
}

$identificadorTH=$_SESSION['usuario'];

if($identificadorTH==''){
	$usuario=0;
}else{
	$usuario=1;
}

?>
<header>
			<h1>Datos capacitaciones</h1>
			<nav>
			<?php 
			/*$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
		
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}*/
		?>
		</nav>
		<nav>
		<form id="filtrar" data-rutaAplicacion="uath" data-opcion="listaCapacitacionesAdmin" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				
			<?php 
				if($identificadorTH != ''){ 		
					echo '<table class="filtro" style="width: 400px;" >
						<tbody>
						<tr>
							<th colspan="3">Buscar Funcionario:</th>
							</tr>
						<tr>
							<td>Número de Cédula:</td>
							<td> <input id="identificador" type="text" name="identificador" maxlength="10" value="'. $_POST['identificador'] .'">	</td>
						</tr>				
						<tr>
							<td>Apellido:</td>
							<td> <input id="apellido" type="text" name="apellido" maxlength="128" value="'. $_POST['apellido'] .'">	</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td> <input id="nombreDelEmpleado" type="text" name="nombreDelEmpleado" maxlength="128" value="'. $_POST['nombreDelEmpleado'] .'">	</td>					
						</tr>
						<tr>
							<td id="mensajeError"></td>
							<td colspan="5"> <button id="buscar">Buscar</button>	</td>
						</tr>
						</tbody>
						</table>';
				}			
			?>
				</form>
	</nav>
		
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Cédula Funcionario</th>
					<th>Apellido</th>
					<th>Nombre</th>
					
				</tr>
			</thead>
			<?php 
				$cd = new ControladorCatastro();
				$res = $cd->obtenerCapacitaciones($conexion,$identificador,$apellido,$nombre);
				$contador = 0;
				
				$areaUsuarioTH = pg_fetch_assoc($car->areaUsuario($conexion, $identificadorTH));
				
				if($areaUsuarioTH['clasificacion']=='Planta Central'){
					while($capacitacion = pg_fetch_assoc($res)){
						echo '<tr id="'.$capacitacion['identificador'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="revisarCapacitacionesAdmin" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem"
								>
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$capacitacion['identificador'].'</b></td>
							<td>'.$capacitacion['apellido'].'</td>
							<td>'.$capacitacion['nombre'].'</td>
							
						</tr>';
					}
				
				}else if($areaUsuarioTH['clasificacion']=='Unidad'){
				
						
					$zonaTH = pg_fetch_assoc($car->buscarArea($conexion, $areaUsuarioTH['id_area_padre']));
					$listaZonas = $car->buscarOficinaTecnicaXArea($conexion, $zonaTH['zona_area']);
				
						
					while($area = pg_fetch_assoc($listaZonas)){
						$zonasFuncionarios[] = $area['id_area'];
				
						$listaAreas = $car->buscarAreasYSubprocesos($conexion, $area['id_area']);
				
						while($areaGestiones = pg_fetch_assoc($listaAreas)){
							$zonasFuncionarios[] = $areaGestiones['id_area'];
						}
					}
				
						
					while($capacitacion = pg_fetch_assoc($res)){
						if (in_array($capacitacion['id_area'], $zonasFuncionarios)) {
							echo '<tr id="'.$capacitacion['identificador'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="revisarCapacitacionesAdmin" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem"
								>
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$capacitacion['identificador'].'</b></td>
							<td>'.$capacitacion['apellido'].'</td>
							<td>'.$capacitacion['nombre'].'</td>
							
						</tr>';
						}
					}
				}
				
				/*while($capacitacion = pg_fetch_assoc($res))
				{
					echo '<tr id="'.$capacitacion['identificador'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="revisarCapacitacionesAdmin" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem"
								>
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$capacitacion['identificador'].'</b></td>
							<td>'.$capacitacion['apellido'].'</td>
							<td>'.$capacitacion['nombre'].'</td>
							
						</tr>';
				}*/
			?>
		</table>

<script>

	var usuario = <?php echo json_encode($usuario); ?>;	

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		}
	});
	
	$("#filtrar").submit(function(event){
		event.preventDefault();
		
		if($('#identificador').val().length!=0 || $('#nombreDelEmpleado').val().length!=0 || $('#apellido').val().length!=0)
		{		
			abrir($('#filtrar'),event, false);
		}		
	});
</script>
