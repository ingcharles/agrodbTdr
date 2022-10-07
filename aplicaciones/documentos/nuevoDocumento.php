<?php 
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cd = new ControladorDocumentos();
$cu = new controladorUsuarios();
$ca = new ControladorAreas();

$conexion->verificarSesion();

$identificador =  $_SESSION['usuario'];
$area = pg_fetch_assoc($cu->obtenerAreaUsuarioIE($conexion,$identificador));

if($area['categoria_area'] == '5'){
	$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area_padre']);

	while ($fila = pg_fetch_assoc($areaSubproceso)){
		$areaBusqueda .= "'".$fila['id_area']."',";
	}
	$areaBusqueda .=  "'".$area['id_area_padre']."',";
	$areaBusqueda = "(".rtrim($areaBusqueda,',').")";

}else if($area['categoria_area'] == '4'){
	$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area']);

	while ($fila = pg_fetch_assoc($areaSubproceso)){
		$areaBusqueda .= "'".$fila['id_area']."',";
	}
	$areaBusqueda .=  "'".$area['id_area']."',";
	$areaBusqueda = "(".rtrim($areaBusqueda,',').")";
}else{
	$areaBusqueda = "('No definido')";
	$advertencia = true;
}

?>

<header>
	<h1>Nuevo documento</h1>
</header>

<div id="estado"></div>

<form id="nuevoDocumento" data-rutaAplicacion="documentos" data-opcion="guardarNuevoDocumento" data-destino="detalleItem">
	<input id="archivoPlantilla" name="archivoPlantilla" value="" type="hidden">
	<input id="versionPlantilla" name="versionPlantilla" value="" type="hidden">
	<fieldset id="codigoPlantilla">
		<legend>Plantilla</legend>
		
		<div data-linea="1">
		
			<select name="codigoPlantilla">
			<?php 
			
				$res = $cd->obtenerPlatillasDisponibles($conexion, $areaBusqueda);
				while($fila = pg_fetch_assoc($res)){
				echo '<option
				value="' . $fila['codigo_plantilla'] . '"
				data-descripcion="' . $fila['descripcion'] . '"
					data-archivoPlantilla="' . $fila['archivo'] . '"
					data-versionPlantilla="' . $fila['version_plantilla'] . '">
						' . $fila['tipo'] . '
						</option>';
			}
			?>
			</select>
		</div><div data-linea="2">
			<div class="info"></div>
		</div>
		
	</fieldset>

	<fieldset>
		<legend>Descripción del nuevo documento</legend>
		
		<div data-linea="1">
			<input name="descripcionDocumento" type="text" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Revisores</legend>
		
		<div data-linea="1">
			<select id="revisor">
				<option value="">Seleccione....</option>
				<?php 
					$cu = new ControladorUsuarios();
					$res = $cu->obtenerUsuariosActivos($conexion,"'".$_SESSION['usuario']."'");
					while($fila = pg_fetch_assoc($res)){
						echo '<option value="' . $fila['identificador'] . '">' . $fila['apellido'] . " " . $fila['nombre'] . '</option>';
					}
				?>
			</select>
			<button type="button" onclick="agregarRevisor()" class="mas">Agregar funcionario</button>
		</div>
		
		<div>
			<table>
				<thead>
					<tr>
						<th colspan="2">Funcionarios asignados</th>
					<tr>
				
				</thead>
				<tbody id="revisores">
				</tbody>
			</table>
		</div>
		
	</fieldset>
	
	<button type="submit" class="guardar">Generar documento</button>
</form>
<script type="text/javascript">

	var advertencia = <?php echo json_encode($advertencia);?>

	$("document").ready(function(){
		distribuirLineas();
		$("#codigoPlantilla div.info").html($("#codigoPlantilla  option:selected").attr("data-descripcion"));
		$("#archivoPlantilla").val($("#codigoPlantilla  option:selected").attr("data-archivoPlantilla"));
		$("#versionPlantilla").val($("#codigoPlantilla  option:selected").attr("data-versionPlantilla"));

		if(advertencia){
			$('#estado').html('Por favor comunicarse con la dirección de Tecnologias de Información para la actualización de datos.').addClass('alerta');
		}else{
			$('#estado').html('');
		}
	});
	
	function agregarRevisor(){
		if($("#revisor").val()!=''){
			if($("#revisores #r_"+$("#revisor").val()).length==0){
			//alert("hola" + $("#revisor").val()+$("#revisor  option:selected").text());
			$("#revisores").append("<tr id='r_"+$("#revisor").val()+"'><td><button type='button' onclick='quitarRevisor(\"#r_"+$("#revisor").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#revisor  option:selected").text()+"<input id='registrador_id' name='registrador_id[]' value='"+$("#revisor").val()+"' type='hidden'><input name='registrador_nombre[]' value='"+$("#revisor  option:selected").text()+"' type='hidden'></td></tr>");
			}
		}
	}

	function quitarRevisor(fila){
		$("#revisores tr").eq($(fila).index()).remove();
	}

	$("#codigoPlantilla").change(function(){
		$("#codigoPlantilla div.info").html($("#codigoPlantilla  option:selected").attr("data-descripcion"));
		$("#archivoPlantilla").val($("#codigoPlantilla  option:selected").attr("data-archivoPlantilla"));
		$("#versionPlantilla").val($("#codigoPlantilla  option:selected").attr("data-versionPlantilla"));
	});

	$("#nuevoDocumento").submit(function(event){
		event.preventDefault();
		if($("#revisores tr").length == 0){
			event.preventDefault();
			$("#estado").html("Por favor seleccione uno o mas revisores.").addClass("alerta");
		}else{
			abrir($(this),event,false);
		}
	    
	});
</script>
