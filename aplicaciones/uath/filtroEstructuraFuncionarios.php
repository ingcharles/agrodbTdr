<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatastro.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cc = new ControladorCatastro();
	
	$identificador = $_SESSION['usuario'];	
?>
<header>
	<h1>Responsables</h1>
	<nav>	
	<form id="administrarResponsable" data-rutaAplicacion="uath" data-opcion="listarEstructuraFuncionarios" data-destino="tabla">
		
		<table class="filtro">	
			<tr>
				<th>Cédula:</th>
					<td>
						<input id="identificador" name="identificador" type="text"  style="width: 100%;"/>
					</td>
					
				<th>Responsable:</th>
					<td>
						<select id="responsable" name="responsable" style="width: 100%;">
							<option value="" selected="selected">Selecione....</option>
							<option value="Activo">Activo</option>												
						</select>
					</td>	
			</tr>		
			<tr>
				<th>Apellidos:</th>
					<td>
						<input id="apellidoUsuario" name="apellidoUsuario" type="text"  style="width: 100%;"/>
					</td>
				<th>Nombres:</th>
					<td>
						<input id="nombreUsuario" name="nombreUsuario" type="text"  style="width: 100%;"/>
					</td>		
			</tr>														
			<tr>
				<th>Área:</th>					
			</tr>								
		</table>
		<div data-linea="1" id="comboCategoria1">
					<label id='lCanton'></label>
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
			
			</div>
			<div data-linea="2" id="comboCategoria2"></div>			
			<div data-linea="3" id="comboCategoria3"></div>
			<div data-linea="4" id="comboCategoria4"></div>			
			<div data-linea="5" id="comboCategoria5"></div>
			<div data-linea="6" id="comboCategoria6"></div>		
				<button>Filtrar</button>
	</form>		
	</nav>
</header>

<div id="tabla"></div>

<script type="text/javascript">

$(document).ready(function(){
	$("#detalleItem").html('');		
});							

$("#administrarResponsable").submit(function(event){

	$("#administrarResponsable").attr('data-opcion', 'listarEstructuraFuncionarios');
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

	
