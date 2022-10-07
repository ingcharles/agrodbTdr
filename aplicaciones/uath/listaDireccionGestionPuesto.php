<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$ca  = new ControladorAplicaciones();
$cc = new ControladorCatalogos();
$car= new ControladorAreas();

$identificador = $_SESSION['usuario'];

$qDireccion=$car->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(1,2,3,4)");

?>

<header>
	<h1>Lista</h1>
	<nav>
		<?php			
			$contador = 0;
			$itemsFiltrados[] = array();
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);			
			while($fila = pg_fetch_assoc($res)){				
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
					  >'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';							
			}
		?>
</nav>

</header>
		
<header>
		<nav>
<form id="filtroListaDireccionGestionPuesto" data-rutaAplicacion="uath" data-opcion="listaDireccionGestionPuesto" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" >
					<tbody>
					<tr>
						<th colspan="2">Busqueda</th>
						</tr>
						<tr>
						<td><b>Dirección:</b></td>
						<td> <select id="sDireccion" name="sDireccion" style='width: 100%;'>
						<option value="0">Seleccione...</option>
						<?php 
							while ($direccionOficina = pg_fetch_assoc($qDireccion)){
					    		echo '<option value="'.$direccionOficina['id_area'].'">'. $direccionOficina['nombre'] .'</option>';
					    	}
						?>
					</select></td>
					</tr>
					<tr>
					<td colspan="2" id="mensajeError"></td>
					<td colspan="2" > <button id='buscar'>Buscar</button></td>
					</tr>
					</tbody>
					</table>
	
							
	
		</form> 
		</nav>
</header>
<table>		
		<thead>
			<tr>
				<th>#</th>
				<th>Dirección</th>
				<th>Gestión</th>
				<th>Puesto</th>
			</tr>
		</thead>
	<?php 			
	    if($_POST['sDireccion']=='')
	    	$idArea = "0";
	    if($_POST['sDireccion']!='')
	    	$idArea = $_POST['sDireccion'];
	    
	    
	    $qDireccionGestionPuesto=$cc->buscarDireccionGestionPuesto($conexion, $idArea);
	    $contador=0;
	    while($fila = pg_fetch_assoc($qDireccionGestionPuesto)){
	    	echo '<tr id="'.$fila['id_puesto'].'"class="item"
								data-rutaAplicacion="uath"
								data-opcion="anadirNuevaFuncion" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td>'.$fila['nombre_area_padre'].'</td>
		       			<td>'.$fila['nombre'].'</td>
						<td>'.$fila['nombre_puesto'].'</td>
					</tr>';
	    }

	    ?>
	    	</table>

	    	
	    	<script>	

	    	$(document).ready(function(){

	    		$("#listadoItems").removeClass("comunes");
	    		$("#listadoItems").addClass("lista");	
	    		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');	

	   	 });
	 $("#filtroListaDireccionGestionPuesto").submit(function(event){    	
			event.preventDefault();					
			if(($('#sDireccion').val()!='0'))
			{		
				abrir($('#filtroListaDireccionGestionPuesto'),event, false);
			}
			else
			{			
				$('#mensajeError').html('<span class="alerta"><td colspan=2>Por favor seleccione algín criterio de busqueda</td></span>');
			}
				
					
		});	

</script>
	    	

