<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$va = new ControladorVacunacion();

$identificadorUsuario=$_SESSION['usuario'];
$contador = 0;
$itemsFiltrados[] = array();
$res = $va->listaCertificadoAanular($conexion, $_POST['especieH'],$_POST['numeroCertificadoH']);
	
while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
						id="'.$fila['id_certificado_vacunacion'].'"
						class="item"
						data-rutaAplicacion="vacunacion"
						data-opcion="abrirCertificadoAnulado"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td ><b>'.++$contador.'</b></td>
						<td>'.$fila['numero_documento'].'</td>
						<td>'.$fila['nombre_especie'].'</td>
						<td>'.$fila['estado'].'</td>
						<td>'.$fila['fecha_registro'].'</td>
					</tr>');
}

?>
<header>
	<h1>Dar de Baja Certificado Físico</h1>
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
	<nav>
	<form id="nuevoFiltroCertificado" data-rutaAplicacion="vacunacion" data-opcion="listaCertificadoAnulado" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<table class="filtro" style='width: 100%;'>
			<tbody>
				<tr>
					<th colspan="4">Consultar Certificado:</th>
				</tr>
				<tr>
					<td>Especie:</td>
					<td>
						<select id="especieH" name="especieH" style='width:100%;'>
							<option value="0">Seleccione...</option>
							<?php
								$especie = $va->listaEspeciesXvacunacion($conexion, 'si');
								while ($fila = pg_fetch_assoc($especie)){
								    	echo '<option  value="' . $fila['id_especies'] . '">' . $fila['nombre'] . '</option>';
								}		    
							?>
						</select>
					</td>
					<td>N° Certificado:</td>
					<td><input id="numeroCertificadoH" name="numeroCertificadoH" type="text" style='width: 100%;'  maxlength="20" /></td>
				</tr>		
				
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Certificado</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError"></td>
				</tr>
			</tbody>
		</table>
	</form>
	</nav>
</header>
 <div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
				<th>#</th>
				<th>N° Certificado</th>
				<th>Especie</th>
				<th>Estado</th>
				<th>Fecha Registro</th>					
			</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

	$("#_eliminar").click(function(event){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione un registro de vacunacion a la vez');
				return false;
		}
	});
	
	$("#nuevoFiltroCertificado").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#numeroCertificadoH").val()==""){	
			 error = true;	
			 $("#mensajeError").html("Por favor ingrese el numero de certificado para realizar la consulta").addClass('alerta');	
		}
		if($("#especieH").val()=="0"){	
			 error = true;	
			 $("#mensajeError").html("Por favor seleccione la especie para realizar la consulta").addClass('alerta');	
		}
		
		
		if(!error){
			abrir($(this),event,false);
		}	
	});
</script>