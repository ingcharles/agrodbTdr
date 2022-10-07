<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();

$qProvincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');

//Obtener usuarios por provincia de acuerdo al perfil

?>

<header>
	<h1>Revisión Documental</h1>
	<nav>
	<form id="listaRevisionFinanciera" data-rutaAplicacion="revisionFormularios" data-opcion="listaRevisionFinancieraFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Solicitud</th>
			
					<td>
						<select id="solicitudes" name="solicitudes">
							<option value="" >Seleccione....</option>
							<option value="Operadores" >Registro Operador</option>
							<option value="Importación" >Importación</option>
							<option value="Fitosanitario" >Fitosanitario</option>
							<option value="Zoosanitario" >Zoosanitario</option>
							<option value="CLV" >Certificado de Libre Venta</option>
							<option value="certificadoCalidad" >Certificado de calidad</option>
						</select>	
					</td>
					
					<th>Provincia </th>

						<td>
							<select id="provincia" name="provincia">
									<?php 
										while ($fila = pg_fetch_assoc($qProvincias)){
											if($_SESSION['nombreProvincia'] == $fila['nombre']){
												echo '<option value="'.$fila['nombre'].'" selected="selected">'.$fila['nombre'].'</option>';
											}else{
												echo '<option value="'.$fila['nombre'].'">'.$fila['nombre'].'</option>';
											}
										}
									?>
				
							</select>
		
						</td>
							
				</tr>
				
				<tr>
					
					<th>Estado </th>

						<td>
							<select id="estados" name="estados">
									<option value="" >Seleccione....</option>
									<option value="pago" >Asignar monto pago</option> <!-- Usar estado pago para reemplazar el aprobado de la revision del inspector -->
									<option value="verificacion" >Verificar pago</option>
									<!-- <option value="confirmado" >Pago confirmado</option> -->
							</select>
							
							<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
							<input type="hidden" id="estadoActual" name="estadoActual" value="financiero">
						</td>
				
				<td id="operador" colspan="2"></td>
				</tr>
				
				<tr >	
					
					<td colspan="5"><button>Filtrar lista</button></td>
				</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>
<script>

	
							
	$("#listaRevisionFinanciera").submit(function(e){
		if($("#solicitudes").val() == 'Operadores'){
			$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionFinancieraFiltradoOperador');
			$("#listaRevisionFinanciera").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}else if ($("#solicitudes").val() == 'certificadoCalidad'){
			$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionGrupoCcalidad');
			$("#listaRevisionFinanciera").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}else{
			$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionFinancieraFiltrado');
			 $("#listaRevisionFinanciera").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}

	});

	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	});

	$("#estados").change(function (event) {
		if($("#solicitudes").val() == 'Operadores' || $("#solicitudes").val() == 'certificadoCalidad'){
			$("#listaRevisionFinanciera").attr('data-opcion', 'combosOperador');
	    	$("#listaRevisionFinanciera").attr('data-destino', 'operador');
	    	abrir($("#listaRevisionFinanciera"), event, false); 
		}else{
			$("#operador").hide();
		}
	});

	$("#solicitudes").change(function (event) {
		cargarValorDefecto("estados","");
		$("#operador").html('');
	});

	$("#provincia").change(function (event) {
		cargarValorDefecto("estados","");
		$("#operador").html('');
	});
	

	
</script>
