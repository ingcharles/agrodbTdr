<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';	
$conexion = new Conexion();	
$ppc = new ControladorVacunacionAnimal();



?>
<header>
	<h1>Catastro</h1>
	<nav>
		<?php
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
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
			<form id="filtrarCatastro" data-rutaAplicacion="vacunacionAnimal" data-opcion="listaRegistroCatastro" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<input type="hidden" name="provinciaFiltro" value="<?php echo $_POST['provinciaFiltro']; ?>" />
			<table class="filtro" style='width:100%;' >
				<tbody>
				<tr>
					<th colspan="4">Buscar catastro de animales</th>					
				</tr>
				<tr>
					<th>Identificación:</th>
					<th colspan="3"><input id="numeroIdentificacion" type="text" size="49%" name="numeroIdentificacion"></th>
				</tr>
				<tr>
					<th>Nombre de la granja:</th>
					<th colspan="3"><input id="nombreGranja" type="text" size="49%" name="nombreGranja"></th>
				</tr>
				<tr>
					<th>Provincia:</th>
					<th colspan="3">
						<select id="provinciaFiltro" name="provinciaFiltro" style="width: 100%;">	
							<option value="0">Seleccionar...</option>											
							<?php 
								$cc = new ControladorCatalogos();
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>							 
						</select>	
					</th>			
				</tr>						
				<tr>					
					<td colspan="4"><button  type="submit">Buscar certificados de vacunación</button></td>
				</tr>
				<tr>
					<td colspan="4" id="estado1" align="center"></td>
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
				<th>Identificador</th>
				<th>Nombre sitio</th>
				<th>Nombre área</th>
				<th>Provincia</th>		
			</tr>
		</thead>
		<?php 			
		    if($_POST['numeroIdentificacion']=='')
		    	$numeroIdentificacion = "0";
		    if($_POST['numeroIdentificacion']!='')
		    	$numeroIdentificacion = $_POST['numeroIdentificacion'];
		   
		    if($_POST['nombreGranja']=='')
		    	$nombreGranja = "0";
		    if($_POST['nombreGranja']!='')
		    	$nombreGranja = $_POST['nombreGranja'];
		    
		    if($_POST['provinciaFiltro']=='')
		    	$provincia = "0";
		    if($_POST['provinciaFiltro']!='')
		    	$provincia = $_POST['provinciaFiltro'];
		    		  
			$ppc = new ControladorVacunacionAnimal();
			$contador = 0;
			$itemsFiltrados[] = array();						

			if(($numeroIdentificacion=="0")  &&  ($nombreGranja=="0") && ($provincia=="0")){
				echo 'No hay registros.';
			}
			else
			{
				$res = $ppc->listaCatastro($conexion, $numeroIdentificacion, $nombreGranja, $provincia, $_SESSION['usuario']);
				if(pg_num_rows($res) == 0){
					echo 'No hay registros.';
				}else{
					while($fila = pg_fetch_assoc($res)){
					echo '<tr
							id="'.$fila['id_sitio'].'@'.$fila['id_area'].'@'.$fila['id_especie'].'"
							class="item"
							data-rutaAplicacion="vacunacionAnimal"
							data-opcion="abrirRegistroCatastro"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$fila['identificador'].'</b></td>
			       			<td>'.$fila['nombre_sitio'].'</td>
							<td>'.$fila['nombre_area'].'</td>
							<td>'.$fila['provincia'].'</td>
						</tr>';
					}			
				}
			}
       ?>
	</table>
<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Registro para revisarlo.</div>');												
	});

	$("#filtrarCatastro").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#numeroIdentificacion").val()=="" && ($('#nombreGranja').val()=="") && ($('#provinciaFiltro').val()=="0") ){
			error = true;
			$("#numeroIdentificacion").addClass("alertaCombo");
			$("#nombreGranja").addClass("alertaCombo");
			$("#provinciaFiltro").addClass("alertaCombo");
		}
	
		if (error){
			$("#estado1").html("Por favor digite los criterios de búsquedas.").addClass('alerta');
			event.preventDefault();
		}else{                
			$("#estado1").html("").removeClass('alerta');  
			abrir($('#filtrarCatastro'),event, false); 
		}
	});
</script>