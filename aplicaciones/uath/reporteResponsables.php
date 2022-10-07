<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatastro.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	
	$identificador = $_SESSION['usuario'];	
?>
<header>
	<h1>Reporte Responsables</h1>
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
	</nav>	</header>	<header>
	<nav>	
	<form id="administrarResponsable" data-rutaAplicacion="uath" data-opcion="abrirReporteFuncionario" data-destino="tabla">
	<?php echo '
						<table class="filtro" style="width: 400px;" >
					<tbody>
					<tr>
						<th colspan="3">Buscar Funcionario:</th>
						</tr>
					<tr>
						<td>Número de Cédula:</td>
						<td> <input id="identificador" type="text" name="identificador" maxlength="10" value="">	</td>
					</tr>		
					<tr>
						<td>Apellido:</td>
						<td> <input id="apellido" type="text" name="apellido" maxlength="128" value="">	</td>
					</tr>
					<tr>
						<td>Nombre:</td>
						<td> <input id="nombre" type="text" name="nombre" maxlength="128" value="">	</td>
					</tr>
			        <tr>
						<td>Área:</td>
					<td style=" width:200px">';
			
			?>
			<select id="seleccionCategoria" name="seleccionCategoria" style="width: 100%;">
									<option value="" selected="selected">Seleccione....</option>
									<?php 
									$areaProceso =$cc->buscarDivisionEstruc($conexion, 'DE');
										while($fila = pg_fetch_assoc($areaProceso)){
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
										}			
									?>
								</select>
								<input type="hidden" id="categoria1" name="categoria1" />
								<input type="hidden" id="nivel" name="nivel" />
			
			<div data-linea="2" id="comboCategoria2"></div>			
			<div data-linea="3" id="comboCategoria3"></div>
			<div data-linea="4" id="comboCategoria4"></div>			
			<div data-linea="5" id="comboCategoria5"></div>
			<div data-linea="6" id="comboCategoria6"></div>		
			
			<?php 
			echo  '</td></tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5"> <button id="buscar">Buscar</button>	</td>
					</tr>
			
					</tbody>
					</table>';
		?>		
	</form>		
	</nav>
</header>
<div id="tabla"></div>
<script type="text/javascript">

$(document).ready(function(){
	$("#detalleItem").html('');		
});							

$("#administrarResponsable").submit(function(event){
	$("#administrarResponsable").attr('data-opcion', 'listarReporteResponsable');
    $("#administrarResponsable").attr('data-destino', 'tabla');
	event.preventDefault();
	abrir($(this),event,false);	
	
});
$("#seleccionCategoria").change(function(event){
	$('#categoria1').val($("#seleccionCategoria option:selected").text());
	$('#nivel').val(1);
	$('#comboCategoria2').html('');	
	$('#comboCategoria3').html('');	
	$('#comboCategoria4').html('');	
	$('#comboCategoria5').html('');
	$('#comboCategoria6').html('');	

	$("#administrarResponsable").attr('data-opcion', 'combosEstructuraAreas');
    $("#administrarResponsable").attr('data-destino', 'comboCategoria2');
    abrir($("#administrarResponsable"), event, false); 
});

</script>	

	
