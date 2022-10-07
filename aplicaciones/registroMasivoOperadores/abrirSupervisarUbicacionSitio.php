<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$datos=explode('-', $_POST['id']);
$identificador = $datos[0];
$idSitio = $datos[1];

$datosOperador = pg_fetch_assoc($cro->buscarOperador($conexion, $identificador));
$datosSitio = pg_fetch_assoc($cro->abrirSitio($conexion, $idSitio));

?>

<header>
	<h1>Datos del Operador</h1>
</header>
<form id="datosOperadorSitio" data-rutaAplicacion="registroMasivoOperadores" > <!-- data-accionEnExito="ACTUALIZAR" -->
	<div id="estado"></div>
	<input value="<?php echo $datosSitio['id_sitio'];?>" name="idSitio" type="hidden" id="idSitio" />
	<fieldset>
		<legend>Información del operador</legend>
		<div data-linea="1">
				<label >Nombre del sitio: </label> 
					<input value="<?php echo $datosOperador['identificador'];?>" name="identificador" type="text" id="identificador" readonly="readonly" disabled="disabled" />
			</div>
			<div data-linea="2">
				<label for="razonSocial" >Razón social: </label> 
					<input value="<?php echo $datosOperador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" readonly="readonly" disabled="disabled" />
			</div>
			<div data-linea="3">
				<label >Nombres: </label> 
					<input value="<?php echo $datosOperador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres"  readonly="readonly" disabled="disabled" />
			</div>
			<div data-linea="3"> 
			<label >Apellidos: </label> 
					<input value="<?php echo $datosOperador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" readonly="readonly" disabled="disabled" />
			</div>
	</fieldset>	
	
	<fieldset>
		<legend>Información del sitio</legend>
		<div data-linea="1">
		<label >Nombre del sitio: </label> 
			<input value="<?php echo $datosSitio['nombre_lugar'];?>" name="nombreSitio" type="text" id="nombreSitio" readonly="readonly" disabled="disabled" />
		</div>
		<div data-linea="1">
		<label >Supervisar ubicación: </label> 
			<input type="checkbox" name="supervisarUbicacion" id="supervisarUbicacion" value="true" <?php  echo ($datosSitio['observacion'] != "") ? 'checked="checked" disabled="disabled"' : ''; ?> >
		</div>
		<div data-linea="2">
		<label>Provincia: </label> 
			<input value="<?php echo $datosSitio['provincia'];?>" name="provinciaSitio" type="text" id="provinciaSitio" readonly="readonly" disabled="disabled" />
		</div>
		<div data-linea="2">
		<label >Cantón: </label> 
			<input value="<?php echo $datosSitio['canton'];?>" name="cantonSitio" type="text" id="cantonSitio" readonly="readonly" disabled="disabled" />
		</div>
		<div data-linea="3"> 
		<label>Parroquia: </label> 
			<input value="<?php echo $datosSitio['parroquia'];?>" name="parroquiaSitio" type="text" id="parroquiaSitio" readonly="readonly" disabled="disabled" />
		</div>
		<div data-linea="4"> 
		<label>Dirección: </label> 
			<input value="<?php echo $datosSitio['direccion'];?>" name="cantonSitio" type="text" id="cantonSitio" readonly="readonly" disabled="disabled" />
		</div>
		<hr/>			
		<div data-linea="5"> 
		<label>Latitud: </label> 
			<input value="<?php echo $datosSitio['latitud'];?>" name="latitudSitio" type="text" id="latitudSitio" <?php  echo ($datosSitio['observacion'] != "") ? 'readonly="readonly" disabled="disabled"' : ''; ?> />
		</div>
		<div data-linea="5">
		<label>Longitud: </label> 
			<input value="<?php echo $datosSitio['longitud'];?>" name="longitudSitio" type="text" id="longitudSitio" <?php  echo ($datosSitio['observacion'] != "") ? 'readonly="readonly" disabled="disabled"' : ''; ?> />
		</div>
		<div data-linea="6">
		<label>Observación: </label> 
			<input value="<?php echo $datosSitio['observacion'];?>" name="observacionSitio" type="text" id="observacionSitio" <?php  echo ($datosSitio['observacion'] != "") ? 'readonly="readonly" disabled="disabled"' : ''; ?> />
		</div>			
		<div>
		<?php  if ($datosSitio['observacion'] == ""){ ?>
			<button id="guardar" type="submit" class="guardar" disabled="disabled">Guardar</button>
		<?php } ?>
		</div>			
	</fieldset>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
	});

	$("#supervisarUbicacion").click(function(){
		if( $('#supervisarUbicacion').prop('checked') ) {
    		$("#guardar").removeAttr("disabled");
		}else{
			$("#guardar").attr("disabled","disabled");
		}
	});

	$("#datosOperadorSitio").submit(function(event){	
		event.preventDefault();
		chequearCampos(this);
		$("#guardar").attr("disabled","disabled");
	});
	
	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

			if(!$.trim($("#latitudSitio").val()) || $("#latitudSitio").val() == ""){
				error = true;
				$("#latitudSitio").addClass("alertaCombo");
			}	
			 
			if(!$.trim($("#longitudSitio").val()) || $("#longitudSitio").val() == ""){
				error = true;
				$("#longitudSitio").addClass("alertaCombo");
			}

			if( $("#supervisarUbicacion").prop('checked') ) {
		
    			if(!$.trim($("#observacionSitio").val()) || $("#observacionSitio").val() == ""){
    				error = true;
    				$("#observacionSitio").addClass("alertaCombo");
    			}

			}

		if (error){
			$("#estado").html("Por favor ingrese o revise el formato toda información.").addClass('alerta');
		}else{
			$('#datosOperadorSitio').attr('data-opcion','actualizarUbicacionSitio');
			ejecutarJson(form);	
		}	
	 }

</script>
