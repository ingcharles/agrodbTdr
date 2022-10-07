<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$csit = new ControladorServiciosInformacionTecnica();

$res=$csit->listarFiltroEnfermedadExotica($conexion,$_POST['zonaH'],$_POST['paisH'],$_POST['productoH'],$_POST['partidaH'],'activo',$_POST['fechaInicioH'],$_POST['fechaFinH'],$_POST['filtro']);
if($_POST['filtro']==1)
$registro=pg_num_rows($res);
while($fila = pg_fetch_assoc($res)){
	$producto=$fila['productos'];
	$producto = (strlen($producto)>=15?(substr($producto,0,15).'...'):$producto);
	$pais=$fila['paises'];
	$pais = (strlen($pais)>=15?(substr($pais,0,15).'...'):$pais);
	$contenido ='<article
		    		id="'.$fila['id_enfermedad_exotica'].'"
		    		class="item enfermedades"
					data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="abrirEnfermedadExoticaSA"
		    		ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
		    		<span><strong>'.$fila['nombre_enfermedad'].'</strong></span><br/>
					<span><small><strong>Producto: </strong>'.$producto.'</small></span><br/>
					<span><small><strong>País: </strong>'.$pais.'</small></span><br/>
					<aside style="padding-left: 5px;" ><small><strong>Inicio: </strong>'	.$fila['inicio_vigencia'].'<br/>
							<strong>Fin: </strong>'	.$fila['fin_vigencia'].'
					</small></aside>
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
	<h1>Información de Enfermedades Exóticas</h1>
	<nav>
		<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="listaEnfermedadExoticaSA" data-destino="listadoItems" href="#">Actualizar</a>
		<a id="_seleccionar" data-rutaaplicacion="serviciosInformacionTecnica" href="#"><?php echo '<div id="cantidadItemsSeleccionados">0</div>'; ?>Seleccionar</a>
	</nav>
</header>
<header>
	<nav>
		<form id="nuevoRegistro" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="listaEnfermedadExoticaSA"	data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" id="opcion" />
			<input type="hidden" name="filtro" id="filtro" />
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<td align="left">Zona de Origen:</td>
						<td colspan="3">
							<select name="zonaH" id="zonaH"	style="width: 100%">
								<option value="">Seleccione...</option>
								<?php
								$qListarZonas=$cc->listarZonas($conexion);
								while($fila=pg_fetch_assoc($qListarZonas)){
									echo '<option value="'.$fila['id_zona'].'">'. $fila['nombre'] . '</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Pais:</td>
						<td colspan="3" id="resultadoPaises">
							<select id="paisH" name="paisH" style="width: 100%" >
							<option value="">Seleccione...</option>
							</select>
						</td>		
					</tr>
					<tr>
						<td align="left">Producto:</td>
						<td colspan="3"><input type="text" id="productoH" name="productoH" style="width: 100%"/></td>
					</tr>
					<tr>
						<td align="left">Partida:</td>
						<td colspan="3"><input type="text" id="partidaH" name="partidaH" style="width: 100%"/></td>
					</tr>
					<tr >
						<td align="left">Fecha Inicio:</td>
						<td><input type="text" id="fechaInicioH" name="fechaInicioH" readonly="readonly" style='width: 98%;'/></td>
						<td align="left">Fecha Fin:</td>
						<td><input type="text"  id="fechaFinH" name="fechaFinH" readonly="readonly" style='width: 98%;' /></td>
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
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		var registros = <?php echo json_encode($registro);?>;
		if(registros==0)
			$("#mensajeError").html("No existen restricciones registradas para la búsqueda realizada").addClass('alerta');
	});
	
	$("#_eliminar").click(function(event){
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro a la vez").addClass('alerta');
				return false;
		}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro").addClass('alerta');
			return false;
		}
	});

	$("#fechaInicioH").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#fechaFinH").datepicker({
	      changeMonth: true,
	      changeYear: true,
	  
	});

	$("#zonaH").change(function(event){
		if($("#zonaH").val()!=0){
			$('#nuevoRegistro').attr('data-destino','resultadoPaises');
			$('#nuevoRegistro').attr('data-opcion','combosServicios');
		    $('#opcion').val('listaPaisesZonas');		
			abrir($("#nuevoRegistro"),event,false); 
		}
	 });

	$("#nuevoRegistro").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		if (error){
			$("#estadoError").html("Ingresar información en campos obligatorios.").addClass('alerta');
			event.preventDefault();
		}else{ 
			$('#filtro').val(1);
			$('#nuevoRegistro').attr('data-destino','areaTrabajo #listadoItems');
			$('#nuevoRegistro').attr('data-opcion','listaEnfermedadExoticaSA');      	
			abrir($(this),event,false);
		}
	});
</script>
