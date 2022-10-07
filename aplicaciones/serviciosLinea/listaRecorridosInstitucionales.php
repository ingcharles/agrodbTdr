<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$csl = new ControladorServiciosLinea();

$contador = 0;
$itemsFiltrados[] = array();
$res = $csl->buscarRutasTransporte($conexion, $_POST['provinciaH'], $_POST['cantonH'], $_POST['oficinaH'],$_POST['filtro']);

while($fila = pg_fetch_assoc($res)){	
	$contenido ='<article
		    		id="'.$fila['id_ruta_transporte'].'"
		    		class="item"
					data-rutaAplicacion="serviciosLinea"
					data-opcion="abrirRecorridosInstitucionales"
		    		ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<span><small><strong>'.$fila['oficina'].'</strong></small></span><br/>
					<span><small><strong>Nombre: </strong>'.$fila['nombre_ruta'].'</small></span><br/>
					<span><small><strong>Sector: </strong>'.$fila['sector'].'</small></span>
					<aside style="padding-left: 5px;" ><small><strong>Conductor: </strong>'	.$fila['conductor'].'<br/>
					<strong>Estado: </strong>'	.$fila['estado'].'</small></aside>
				</article>';
	
	?>
		
			<script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				$("#listado div.elementos").append(contenido);
			</script>
		<?php	
		
}
?>
<header>
			<h1>Rutas de Transporte Institucional</h1>
			<nav>
				<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosLinea" data-opcion="listaRecorridosInstitucionales" data-destino="listadoItems" href="#">Actualizar</a>
				<a id="_seleccionar" data-rutaaplicacion="serviciosLinea" data-opcion="listaRecorridosInstitucionales" data-destino="listadoItems" href="#"><div id="cantidadItemsSeleccionados">0</div>Seleccionar</a>
			</nav>
</header>

<header>
	<nav>
		<form id="nuevoFiltroRutasTransporte" data-rutaAplicacion="serviciosLinea" data-opcion="listaRecorridosInstitucionales" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcionPaso" id="opcionPaso" />
			<input type="hidden" name="filtro" id="filtro" value="1" />
			<input type="hidden" name="opcion"	value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<td align="left">Provincia:</td>
						<td><select id="provinciaH" name="provinciaH" style="width:250px">
						<option value="">Seleccione...</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia)
								echo '<option value="'.$provincia['codigo'].'">' . $provincia['nombre'] . '</option>';
						?>
						</select></td>
					</tr>
					<tr>
						<td align="left">Cantón:</td>
						<td colspan="3" id="resultadoCantonesH">
							<select id="cantonH" name="cantonH" style="width:250px" >
							<option value="">Seleccione...</option>
							</select>
						</td>		
					</tr>
					
					<tr>
						<td align="left">Oficina:</td>
						<td colspan="3" id="resultadoOficinasH">
							<select id="oficinaH" name="oficinaH" style="width:250px" >
							<option value="">Seleccione...</option>
							</select>
						</td>		
					</tr>
					<tr>
						<td colspan="4"><button>Buscar</button></td>
					</tr>
					<tr>
						<td colspan="4" style='text-align: center' id="mensajeError">	
					</tr>
				</tbody>
			</table>
		</form>
	</nav>

</header>
<div id="listado">	
	<div class="elementos"></div>
</div>
<script>		
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para revisarlo.</div>');								
	});
	
	$("#nuevoFiltroRutasTransporte").submit(function(event){


		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#provinciaH").val())){
			error = true;
			$("#provinciaH").addClass("alertaCombo");
		}

		if(!$.trim($("#cantonH").val())){
			error = true;
			$("#cantonH").addClass("alertaCombo");
		}

		if(!$.trim($("#oficinaH").val())){
			error = true;
			$("#oficinaH").addClass("alertaCombo");
		}

		if(!error){ 
			$('#nuevoFiltroRutasTransporte').attr('data-destino','areaTrabajo #listadoItems');
			$('#nuevoFiltroRutasTransporte').attr('data-opcion','listaRecorridosInstitucionales')
			abrir($('#nuevoFiltroRutasTransporte'),event,false);  
		}else{
			$("#mensajeError").html("Por favor seleccione todos los campos").addClass('alerta');	
		}
	
	});

	$("#provinciaH").change(function(event){
		if($("#provinciaH").val()!=0){
			$('#nuevoFiltroRutasTransporte').attr('data-destino','resultadoCantonesH');
			$('#nuevoFiltroRutasTransporte').attr('data-opcion','accionesServiciosLinea');
		    $('#opcionPaso').val('listaCantonesH');		
			abrir($("#nuevoFiltroRutasTransporte"),event,false); 		
		}
	 });
</script>