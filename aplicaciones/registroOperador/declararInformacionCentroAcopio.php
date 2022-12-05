<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();	
	$cac = new ControladorAdministrarCatalogos();
	 	
	$datos=explode('@', $_POST['id']);
	$idSitio = $datos[0];
	$idOperacion = $datos[1];
		
	$qUnidadMedida = $cc -> listarUnidadesMedida($conexion);
	
	$qSitio = $cro -> abrirSitio($conexion, $idSitio);
	$sitio = pg_fetch_result($qSitio, 0, 'nombre_lugar');
	
	$idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
	$nombreArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'nombre_area');

	$qLaboratoriosLeche = $cac -> listarItemsPorCodigo($conexion, 'COD-LABOR-IA', '1');
	
	$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
	$operacion = pg_fetch_assoc($qOperacion);
	
	$qDatosCentroAcopio = $cro->obtenerDatosCentroAcopioXIdOperadorTipoOperacionPorEstado($conexion, $operacion['id_operador_tipo_operacion'], 'activo');
	$datosCentroAcopio = pg_fetch_assoc($qDatosCentroAcopio);	
	$capacidadCentroAcopio = $datosCentroAcopio['capacidad_instalada'];
	$unidadMedidaCentroAcopio = $datosCentroAcopio['codigo_unidad_medida'];
	$numeroTrabajadoresCentroAcopio = $datosCentroAcopio['numero_trabajadores'];
	$laboratorioCentroAcopio = $datosCentroAcopio['id_laboratorio_leche'];
	$numeroProveedoresCentroAcopio = $datosCentroAcopio['numero_proveedores'];
	$perteneceMagCentroAcopio = $datosCentroAcopio['pertenece_mag'];
	$horaRecoleccionManianaCentroAcopio = $datosCentroAcopio['hora_recoleccion_maniana'];
	$horaRecoleccionTardeCentroAcopio = $datosCentroAcopio['hora_recoleccion_tarde'];
		
?>

<header>
	<h1>Declarar Información del Centro de Acopio</h1>
</header>

<div id="estado"></div>

<form id="declararInformacionCentroAcopio" data-rutaAplicacion="registroOperador" data-opcion="guardarDeclararInformacionCentroAcopio" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" class="idArea" name="idArea" value="<?php echo $idArea;?>" />
	<input type="hidden" class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
	
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
			<label>*Capacidad instalada: </label><input type="text" id="capacidadInstalada" name="capacidadInstalada" onkeypress="ValidaSoloNumeros()" value="<?php echo $capacidadCentroAcopio; ?>" />
		</div>
		<div data-linea="2">
			<label for="unidadMedida">*Unidad: </label>			
            <select id="unidadMedida" name="unidadMedida">
            <option value="">Seleccione...</option>
                <?php
                    while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                        echo '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                    }
                ?>
            </select>
		</div>
		<div data-linea="3">			
			<label>*Número de trabajadores: </label><input type="text" id="numeroTrabajadores" name="numeroTrabajadores" onkeypress="ValidaSoloNumeros()"  value="<?php echo $numeroTrabajadoresCentroAcopio; ?>" />
		</div>
		<div data-linea="4">			
			<label for="laboratorio">*Laboratorio legalmente constituido: </label>
            <select id="laboratorio" name="laboratorio">
            <option value="">Seleccione...</option>
                <?php
                    while ($laboratorio = pg_fetch_assoc($qLaboratoriosLeche)) {
                        echo '<option value="' . $laboratorio['id_item'] . '">' . $laboratorio['nombre'] . '</option>';
                    }
                ?>
            </select>
		</div>
		<div data-linea="5">			
			<label>*Número de proveedores: </label><input type="text" id="numeroProveedores" name="numeroProveedores"onkeypress="ValidaSoloNumeros()"  value="<?php echo $numeroProveedoresCentroAcopio; ?>" />
		</div>
		<div data-linea="5">
		<label for="perteneceMag">*Pertenece al MAG: </label>
            <select id="perteneceMag" name="perteneceMag">
            <option value="">Seleccione...</option>
               <option value="SI">SI</option>
                <option value="NO">NO</option>
            </select>
		</div>
		<div data-linea="6">
			<label>Horario de recepción matutina:</label> <input type="text" id="horaRecoleccionManiana" name="horaRecoleccionManiana" placeholder="06:30" data-inputmask="'mask': '99:99'" value="<?php echo $horaRecoleccionManianaCentroAcopio; ?>" <?php ($horaRecoleccionManianaCentroAcopio == "") ? 'disabled="disabled"' : ""; ?> />
		</div>	
		<div data-linea="6">
			<input type="checkbox" name="validarManiana" id="validarManiana" value="">
		</div>
		<div data-linea="7">
			<label>Horario de recepción vespertina:</label> <input type="text" id="horaRecoleccionTarde" name="horaRecoleccionTarde" placeholder="17:30" data-inputmask="'mask': '99:99'" value="<?php echo $horaRecoleccionTardeCentroAcopio; ?>" <?php ($horaRecoleccionTardeCentroAcopio == "") ? 'disabled="disabled"' : ""; ?> />
		</div>	
		<div data-linea="7">
			<input type="checkbox" name="validarTarde" id="validarTarde" value="">
		</div>
		</fieldset>
	<button type="submit" class="guardar">Guardar</button>
	
</form>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		cargarValorDefecto("laboratorio","<?php echo $laboratorioCentroAcopio; ?>");
		cargarValorDefecto("perteneceMag","<?php echo $perteneceMagCentroAcopio; ?>");
		cargarValorDefecto("unidadMedida","<?php echo ($unidadMedidaCentroAcopio) != "" ? $unidadMedidaCentroAcopio : "L"; ?>");

		var horarioManiana= <?php echo json_encode($horaRecoleccionManianaCentroAcopio); ?>;
		var horarioTarde= <?php echo json_encode($horaRecoleccionTardeCentroAcopio); ?>;

		if(horarioManiana != ""){
			$("#validarManiana").prop('checked', true);
		}

		if(horarioTarde != ""){
			$("#validarTarde").prop('checked', true);
		}
		
		$("#validarManiana").click(function() {  
			if($("#validarManiana").prop('checked')) {
				$("#horaRecoleccionManiana").prop('disabled', false);
			}else{
				$("#horaRecoleccionManiana").val("");
				$("#horaRecoleccionManiana").prop('disabled', true);
			}
	    });  

		$("#validarTarde").click(function() {  
			if($("#validarTarde").prop('checked')) {
				$("#horaRecoleccionTarde").prop('disabled', false);
			}else{
				$("#horaRecoleccionTarde").val("");
				$("#horaRecoleccionTarde").prop('disabled', true);
			}
	    });  

		$("#capacidadInstalada").numeric();
		$("#numeroTrabajadores").numeric();
		$("#numeroProveedores").numeric();		
		
	});
	
	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}

	$("#horaRecoleccionManiana").change(function(){

		$("#horaRecoleccionManiana").removeClass('alertaCombo');
			
			var horaNueva = $("#horaRecoleccionManiana").val().replace(/\_/g, "0");
			$("#horaRecoleccionManiana").val(horaNueva);
			
			var hora = $("#horaRecoleccionManiana").val().substring(0,2);
			var minuto = $("#horaRecoleccionManiana").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaRecoleccionManiana").val('24:00');
					}			

				}else{
					$("#horaRecoleccionManiana").addClass('alertaCombo');
					$("#horaRecoleccionManiana").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaRecoleccionManiana").addClass('alertaCombo');
				$("#horaRecoleccionManiana").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});

	$("#horaRecoleccionTarde").change(function(){

		$("#horaRecoleccionTarde").removeClass('alertaCombo');
			
			var horaNueva = $("#horaRecoleccionTarde").val().replace(/\_/g, "0");
			$("#horaRecoleccionTarde").val(horaNueva);
			
			var hora = $("#horaRecoleccionTarde").val().substring(0,2);
			var minuto = $("#horaRecoleccionTarde").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaFinRecoleccion").val('24:00');
					}			

				}else{
					$("#horaRecoleccionTarde").addClass('alertaCombo');
					$("#horaRecoleccionTarde").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaRecoleccionTarde").addClass('alertaCombo');
				$("#horaRecoleccionTarde").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});
		
	$("#declararInformacionCentroAcopio").submit(function(event){
		
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorTipo = false;
		
		if($("#capacidadInstalada").val() == "" || $("#capacidadInstalada").val() == 0){	
			error = true;		
			$("#capacidadInstalada").addClass("alertaCombo");
		}

		if($("#unidadMedida").val() == ""){	
			error = true;		
			$("#unidadMedida").addClass("alertaCombo");
		}

		if($("#numeroTrabajadores").val() == 0 || $("#numeroTrabajadores").val() == ""){	
			error = true;		
			$("#numeroTrabajadores").addClass("alertaCombo");
		}

		if($("#laboratorio").val() == ""){	
			error = true;		
			$("#laboratorio").addClass("alertaCombo");
		}

		if($("#numeroProveedores").val() == "" || $("#numeroProveedores").val() == 0){	
			error = true;		
			$("#numeroProveedores").addClass("alertaCombo");
		}

		if($("#perteneceMag").val() == "" || $("#perteneceMag").val() == 0){	
			error = true;		
			$("#perteneceMag").addClass("alertaCombo");
		}

		if($("#horaRecoleccionManiana").prop('disabled') && $("#horaRecoleccionTarde").prop('disabled')) {
			error = true;				
			errorTipo = true; 	
			$("#horaRecoleccionManiana").addClass("alertaCombo");
			$("#horaRecoleccionTarde").addClass("alertaCombo");
			$("#validarManiana").addClass("alertaCombo");
			$("#validarTarde").addClass("alertaCombo");
		}

		if($("#validarManiana").prop('checked')){
			if($("#horaRecoleccionManiana").val() == "" || $("#horaRecoleccionManiana").val() == 0){	
				error = true;	
				$("#horaRecoleccionManiana").addClass("alertaCombo");
			}
		}

		if($("#validarTarde").prop('checked')){
			if($("#horaRecoleccionTarde").val() == "" || $("#horaRecoleccionTarde").val() == 0){	
				error = true;	
				$("#horaRecoleccionTarde").addClass("alertaCombo");
			}
		}
		
		if (!error){
			ejecutarJson(this);
			$(".guardar").prop('disabled',false);
		}else{

			if (errorTipo){
				$("#estado").html("Por favor registre al menos un horario.").addClass("alerta");
			}else{
				$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
			}
		}
		
	});
</script>