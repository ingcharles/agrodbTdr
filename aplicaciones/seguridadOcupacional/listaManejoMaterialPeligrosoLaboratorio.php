<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$so = new ControladorSeguridadOcupacional();
$cc = new ControladorCatalogos();

$contador = 0;
$itemsFiltrados[] = array();

function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹"," ");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}

$res = $so->listaManejoMaterialesPeligrosos($conexion, $_POST['laboratorio'],quitar_tildes($_POST['nombreProducto']), $_POST['numeroUn'], $_POST['numeroCas'],'porLaboratorio');

$laboratorios=$so->listaSubTipoLaboratoriosMaterialesPeligrosos($conexion);

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_manejo_material_peligroso'].'"
								class="item"
								data-rutaAplicacion="seguridadOcupacional"
								data-opcion="abrirManejoMaterialPeligroso"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['nombre_laboratorio'].'</td>
								<td>'.$fila['nombre_material_peligroso'].'</td>
								<td>'.$fila['numero_guia_material_peligroso'].'</td>
							</tr>');
}

?>
<header>
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
	<h1>Asignar Material Peligroso a Laboratorio</h1>
	
	<nav>
	<form id="nuevoBuscarMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="listaManejoMaterialPeligrosoLaboratorio" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
	
		<table class="filtro" style='width: 350px;'>
			<tbody>
				<tr>
					<th colspan="4">Buscar material peligroso:</th>
				</tr>
				
				<tr>
					<td style='text-align: left;'>Coordinación laboratorio:</td>
					<td>
					<select id="coordinacion" name="coordinacion" style="width:89%" >
						<option value="">Seleccione...</option>
						<?php
							$qLaboratoriosMaterialesPeligrosos = $cc->listaLaboratoriosMaterialesPeligrosos($conexion);
							while ($fila = pg_fetch_assoc($qLaboratoriosMaterialesPeligrosos)){
							    	echo '<option  value="' . $fila['id_laboratorio'] . '">' . $fila['nombre_laboratorio'] . '</option>';
							}		    
						?>
					</select>
					</td>
				</tr>
				
				<tr>
					<td style='text-align: left;'>Nombre laboratorio:</td>
					<td>
			
			<select id="laboratorio" name="laboratorio" style="width:89%">
			<option value="">Seleccione...</option>
			</select> 				
			</td>
				</tr>
				<tr>	
					<td style='text-align: left;'>Nombre químico:</td>
					<td><input id="nombreProducto" name="nombreProducto" type="text"  maxlength="1024" style="width:89%"/></td>
				</tr>
				
				<tr>
					<td style='text-align: left;'>Número UN:</td>
					<td><input id="numeroUn" name="numeroUn" type="text"  maxlength="32" style="width:89%" onkeypress='ValidaSoloNumeros()' data-er="^[0-9]+$" /></td>		
				
				</tr>
				
				<tr>
					<td style='text-align: left;'>Número CAS:</td>
					<td><input id="numeroCas" name="numeroCas" type="text"  maxlength="32" style="width:89%"/></td>		
				</tr>		
				
				<tr>
					<td colspan="4" style='text-align:center'><button>Filtrar</button></td>	
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
			<th>Laboratorio</th>
			<th>Químico</th>
			<th>Número Guía</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	

	var array_laboratorio = <?php echo json_encode($laboratorios); ?>;
	$(document).ready(function(){
		construirValidador();
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);				
	});

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#coordinacion").change(function(){	
		
		if($("#coordinacion").val() != 0){
			sLaboratorio='';
			sLaboratorio = '<option value="">Seleccione...</option>';
			
			for(var i=0;i<array_laboratorio.length;i++){
				if ($("#coordinacion").val()==array_laboratorio[i]['idLaboratorioPadre'])	   
					sLaboratorio += '<option value="'+array_laboratorio[i]['idLaboratorio']+'"> '+ array_laboratorio[i]['nombreLaboratorio']+'</option>';
			}	   		    
			$('#laboratorio').html(sLaboratorio);
			$("#laboratorio").removeAttr("disabled");	  			
		}		 
	});
	
	$("#nuevoBuscarMaterialPeligroso").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#coordinacion").val()=="" ){
			error = true;	
			$("#coordinacion").addClass("alertaCombo");	
		}

		if($("#laboratorio").val()=="" ){
			error = true;	
			$("#laboratorio").addClass("alertaCombo");	
		}

		if($.trim($("#numeroUn").val()) ){
			if(!esCampoValido("#numeroUn") ){
				error = true;
				$("#numeroUn").addClass("alertaCombo");
			}
		}
		
		if(!error){
			abrir($(this),event,false);
		}else{
			$("#mensajeError").html("Ingresar información en campos obligatorios.").addClass('alerta');
			
		}	
		
	});
</script>