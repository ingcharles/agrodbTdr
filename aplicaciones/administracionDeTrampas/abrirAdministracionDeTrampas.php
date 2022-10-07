<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';
	
$conexion = new Conexion();	
$cc = new ControladorCatalogos();
$cat = new ControladorAdministracionDeTrampas();

$idAdministracionTrampa = $_POST['id'];

$qAdministracionTrampa = $cat->obtenerAdministracionTrampaPorIdAdministracion($conexion, $idAdministracionTrampa);
$tipoAtrayente = $cat->listarTipoAtrayente($conexion, 'activo');

$administracionTrampa = pg_fetch_assoc($qAdministracionTrampa);

$qLocalizacion = $cc->obtenerLocalizacion($conexion, $administracionTrampa['id_provincia']);
$provincia = pg_fetch_assoc($qLocalizacion);

$qLocalizacion = $cc->obtenerLocalizacion($conexion, $administracionTrampa['id_canton']);
$canton = pg_fetch_assoc($qLocalizacion);

$qLocalizacion = $cc->obtenerLocalizacion($conexion, $administracionTrampa['id_parroquia']);
$parroquia = pg_fetch_assoc($qLocalizacion);

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

?>

<header>
	<h1>Administración de Trampas</h1>
</header>

<div id="estado"></div>
	
	<form id="abrirAdministracionDeTrampas" data-rutaAplicacion="administracionDeTrampas" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="idAdministracionTrampa" name="idAdministracionTrampa" value="<?php echo $idAdministracionTrampa; ?>" readonly="readonly" >
		<div>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
		</div>
		<fieldset id="datosGenerales">
			<legend>Datos Generales</legend>
			<div data-linea="1">
				<label>Nombre del área: </label><?php echo $administracionTrampa['nombre_area_trampa']?>			
			</div>
			<div data-linea="2">
				<label>Etapa trampa: </label><?php echo $administracionTrampa['etapa_trampa']?>
			</div>
			<div data-linea="3">
				<label>Fecha de instalación: </label><?php echo $administracionTrampa['fecha_instalacion_trampa'];?>
			</div>
		</fieldset>
		
		<fieldset id="datosInstalacion">
		<legend>Datos Instalación</legend>
			<div data-linea="3">
				<label>Código de trampa: </label><?php echo $administracionTrampa['codigo_trampa'];?>
			</div>
			<div data-linea="4">
				<label>Provincia: </label><?php echo $provincia['nombre']; ?>
			</div>
			<div data-linea="5">
				<label>Cantón: </label><?php echo $canton['nombre']; ?>	
			</div>
			<div data-linea="6">
				<label>Parroquia:</label><?php echo $parroquia['nombre']; ?>
			</div>
			<hr>
			<div data-linea="7">
				<label>Georeferenciación UTM:</label>
			</div>
			<div data-linea="8">
				<label>X: </label>
				<input type="text" id="coordenadax" name="coordenadax" value="<?php echo $administracionTrampa['coordenadax'];?>" disabled >
			</div>
			<div data-linea="8">
				<label>Y: </label>
				<input type="text" id="coordenaday" name="coordenaday" value="<?php echo $administracionTrampa['coordenaday'];?>" disabled >
			</div>
			<div data-linea="8">
				<label>Z: </label>
				<input type="text" id="coordenadaz" name="coordenadaz" value="<?php echo $administracionTrampa['coordenadaz'];?>" disabled >
			</div>
			<hr>
			<div data-linea="9">
				<label>Lugar de instalación: </label><?php echo $administracionTrampa['nombre_lugar_instalacion'];?>
			</div>
			<div data-linea="10">
				<label>Número de lugar de instalación: </label><?php echo $administracionTrampa['numero_lugar_instalacion'];?>
			</div>			
		</fieldset>
		<fieldset  id="datosTrampa">
		<legend>Datos Trampa</legend>
			<div data-linea="11">
				<label>Plaga monitoreada: </label><?php echo $administracionTrampa['nombre_plaga'];?>
			</div>
			<div data-linea="12">
				<label>Tipo de trampa: </label><?php echo $administracionTrampa['nombre_tipo_trampa'];?>
			</div>
			<div data-linea="13">
				<label>Tipo de atrayente: </label>
				<select id="id_tipo_atrayente" name="id_tipo_atrayente" disabled >
					<option value="">Seleccione....</option>
					<?php 
						foreach ($tipoAtrayente as $item){
							if($item['id_tipo_atrayente'] == $administracionTrampa['id_tipo_atrayente']){
								echo '<option value="' . $item['id_tipo_atrayente'] . '" selected="selected">' . $item['nombre_tipo_atrayente'] . '</option>';
							}else{
								echo '<option value="' . $item['id_tipo_atrayente'] . '">' . $item['nombre_tipo_atrayente'] . '</option>';
							}
						}
					?>
				</select>		
			</div>
			<div data-linea="14">
				<label>Estado de la trampa: </label>
				<select id="estadoTrampa" name="estadoTrampa" disabled >
					<option value="">Seleccione...</option>
					<option value="activo">Activa</option>
					<option value="inactivo">Inactiva</option>
				</select>
			</div>
			<div data-linea="15">
				<label>Observación: </label>
				<input type="text" id="observacion" name="observacion" value="<?php echo $administracionTrampa['observacion'];?>" disabled >
			</div>
		</fieldset>
	
	</form>
	
<script type="text/javascript">			

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

					
    $(document).ready(function(){	
    	distribuirLineas();	    	
    	cargarValorDefecto("estadoTrampa","<?php echo $administracionTrampa['estado_trampa'];?>");
    	$('<option value="<?php echo $administracionTrampa['id_localizacion'];?>"><?php echo $canton['nombre'];?></option>').appendTo('#canton');
		$('<option value="<?php echo $administracionTrampa['id_localizacion'];?>"><?php echo $parroquia['nombre'];?></option>').appendTo('#parroquia');	
		construirValidador();	
    });
    
    $("#modificar").click(function(){
		$("#observacion").removeAttr("disabled");
		$("#estadoTrampa").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#id_tipo_atrayente").removeAttr("disabled");
		$("#coordenadaz").removeAttr("disabled");
		$("#coordenaday").removeAttr("disabled");
		$("#coordenadax").removeAttr("disabled");
		
		$(this).attr("disabled","disabled");
	});

    $("#abrirAdministracionDeTrampas").submit(function(event){
        
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#estadoTrampa").val())){
			error = true;
			$("#estadoTrampa").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val())){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			 $('#abrirAdministracionDeTrampas').attr('data-opcion','modificarAdministracionDeTrampas');
			ejecutarJson($(this));
		}
		
     });
    
</script>