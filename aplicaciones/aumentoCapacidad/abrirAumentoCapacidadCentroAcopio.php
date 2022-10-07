<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

 	
$datos=explode('@', $_POST['id']);
$idSitio = $datos[0];
$idOperacion = $datos[1];
$idOperadorTipoOperacion = $datos[2];

$qUnidadMedida = $cc -> listarUnidadesMedida($conexion);

$qSitio = $cro -> abrirSitio($conexion, $idSitio);
$sitio = pg_fetch_result($qSitio, 0, 'nombre_lugar');

$idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
$nombreArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'nombre_area');

$qDatosCentroAcopio = $cro->obtenerDatosCentroAcopioXIdArea($conexion, $idArea);
$datosCentroAcopio = pg_fetch_assoc($qDatosCentroAcopio);

?>

<header>
	<h1>Solicitud aumento de capacidad</h1>
</header>

<div id="estado"></div>

<form id="aumentoCapacidadCentroAcopio" data-rutaAplicacion="aumentoCapacidad" data-opcion="guardarAumentoCapacidadCentroAcopio" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type=hidden class="idArea" name="idArea" value="<?php echo $idArea;?>" />
	<input type=hidden class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
	<input type=hidden class="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>" />
	
	<fieldset>
		<legend>Información del Centro de Acopio</legend>		
		<div data-linea="1">			
			<label>Sitio: </label><?php echo $sitio; ?>
		</div>
		<div data-linea="1">			
			<label>Área: </label><?php echo $nombreArea; ?>
		</div>
		<hr/>
		<div data-linea="2">			
			<label>*Capacidad Instalada: </label><input type="text" id="capacidadInstalada" name="capacidadInstalada" value="<?php echo $datosCentroAcopio['capacidad_instalada']?>" disabled />
		</div>
		<div data-linea="2">
			<label for="unidadMedida">*Unidad: </label>			
            <select id="unidadMedida" name="unidadMedida" disabled >
            <option value="">Seleccione...</option>
                <?php
                    while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                        echo '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                    }
                ?>
            </select>        
		</div>
	</fieldset>
			<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
</form>

<script type="text/javascript">
       
	$(document).ready(function(){
		distribuirLineas();		
		cargarValorDefecto("unidadMedida","<?php echo $datosCentroAcopio['codigo_unidad_medida']?>");
		$("#capacidadInstalada").numeric();			
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	$("#aumentoCapacidadCentroAcopio").submit(function(event){
		
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		
		if($("#capacidadInstalada").val() == "" || $("#capacidadInstalada").val() == 0){	
			error = true;		
			$("#capacidadInstalada").addClass("alertaCombo");
		}

		if($("#unidadMedida").val() == ""){	
			error = true;		
			$("#unidadMedida").addClass("alertaCombo");
		}

		if (!error){
			
			ejecutarJson(this);
			
		}else{
			
			$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
			
		}
		
	});
	
</script>