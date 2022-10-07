<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$academico_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();

$res = $ce->obtenerDatosCapacitacion($conexion, $_SESSION['usuario'], $academico_seleccionado);
$capacitacion = pg_fetch_assoc($res);

$qPais = $cc->listarLocalizacion($conexion, 'PAIS');

$identificador=$_SESSION['usuario'];
?>

<header>
	<h1>Modificar Datos Capacitación</h1>
</header>

<form id="datosAcademicos" data-rutaAplicacion="uath" data-opcion="guardarDatosCapacitacion"> 
	<input type="hidden" id="usuario" name="usuario" value="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
	<input type="hidden" id="academico_seleccionado" value="<?php echo $academico_seleccionado;?>" name="academico_seleccionado" /> 
	

	<p>
		<button id="modificar" type="button" class="editar" <?php echo ($capacitacion['estado']=='Aceptado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizar" type="submit" class="guardar"
			disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Capacitación</legend>
					<div data-linea="1">
						<label>Título de capacitación</label> 
							<input type="text" id="titulo" name="titulo" value="<?php echo $capacitacion['titulo_capacitacion']; ?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$" />
					</div>
					<div data-linea="2">
						<label>Auspiciante</label> 
							<input type="text" id="auspiciante" name="auspiciante" value="<?php echo $capacitacion['auspiciante']; ?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü/0-9 ]+$" maxlength=128/>
					</div>
					<div data-linea="3">
						<label>Tipo certificado</label> 
				     <select name="tipo_certificado" id="tipo_certificado">
							<option value="" >Seleccione....</option>
							<option value="Aprobación">Aprobación</option>
							<option value="Asistencia">Asistencia</option>
						</select>	</div>
					<div data-linea="4">
						<label>Institución</label> 
							<input type="text" id="institucion" name="institucion" value="<?php echo $capacitacion['institucion']; ?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
					</div>
					
					<div data-linea="5">
						<label>País</label> <select name="pais" id="pais">
							<option value="" >Seleccione....</option>
							<?php
								while($pais = pg_fetch_assoc($qPais)){
									$pais_Select=($pais['nombre']==$capacitacion['pais'])?' selected="selected"':''; // tener en cuenta la otra manera de buscar un elemento en un select
									echo '<option value="'.$pais['nombre'].'"'.$pais_Select.'>'.$pais['nombre'].'</option>';
								}
							?>
						</select>
					</div>
											
					<div data-linea="5">
						<label>Horas</label> 
							<input type="text" name="horas" id="horas" value="<?php echo $capacitacion['horas']; ?>"
							disabled="disabled" placeholder="Ej. 9999" data-inputmask="'mask': '9[9999999999]'" data-er="[0-9]{1,10}" title="99" />
					</div>
					
					<div data-linea="6">		
			<label>Fecha Inicio</label>
				<input type="text"	id="fecha_inicio" name="fecha_inicio" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio'])); ?>" disabled="disabled" required="required" readonly="readonly" />
		</div>
		
		<div data-linea="6">
			<label>Fecha Fin</label>
				<input type="text"	id="fecha_fin" name="fecha_fin"	 value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_fin'])); ?>" disabled="disabled" required="required" readonly="readonly" />
		</div>					
					
					<div data-linea="7">
						<label>Archivo Capacitación</label> <?php echo ($capacitacion['archivo_capacitacion']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo_capacitacion'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					</div>
					<div data-linea="8">
						<!-- input type="file" name="archivo_capacitacion" id='archivo_capacitacion' accept="application/msword | application/pdf | image/*" /-->
						<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $capacitacion['archivo_capacitacion'];?>" />
						<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosCapacitacion" >Subir archivo</button>
						<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">

	$("#datosAcademicos").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	/*$('#archivo_capacitacion').change(function(event){

		$("#estado").html('');
		var archivo = $("#archivo_capacitacion").val();
		var extension = archivo.split('.');
		$("#actualizar").attr("disabled","disabled");
		var numero = Math.floor(Math.random()*100000000);
		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			var x = document.getElementById("archivo_capacitacion");
			var file = x.files[0];
			if(file.size<3145728){
			  subirArchivo('archivo_capacitacion','< ?php echo $_SESSION['usuario'].'_';?>'+numero,'aplicaciones/uath/archivosCapacitacion', 'archivo');
			  $("#actualizar").removeAttr("disabled");
			}else{
				$("#estado").html('El peso del archivo es mayor a 3MB!').addClass("alerta");
				$("#actualizar").attr("disabled","disabled");
				}
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#archivo_capacitacion').val('');
		}
	});*/

	 var usuario = <?php echo json_encode($_SESSION['usuario']);?>;
	 
		$('button.subirArchivo').click(function (event) {

			numero = Math.floor(Math.random()*100000000);		
			
	        var boton = $(this);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");

	        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

	            subirArchivo(
	                archivo
	                , usuario +"_id_Capacitacion_"+numero
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
	        } else { 
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("");
	        }
	    });
    	
	$("#modificar").click(function(){
		$("#datosAcademicos input").removeAttr("disabled");
		$("#datosAcademicos select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#horas").removeAttr("disabled","disabled");
		$("#titulo").removeAttr("disabled","disabled");
		$("#institucion").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		
	});

	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

			if(!$.trim($("#pais").val())){
				error = true;
				$("#pais").addClass("alertaCombo");
				
			}
			if(!$.trim($("#institucion").val()) || !esCampoValido("#institucion")){
				error = true;
				$("#institucion").addClass("alertaCombo");
				
			}
			
			if(!$.trim($("#titulo").val()) || !esCampoValido("#titulo")){
				error = true;
				$("#titulo").addClass("alertaCombo");
				
			}

			if(!$.trim($("#horas").val()) || !esCampoValido("#horas")){
					error = true;
					$("#horas").addClass("alertaCombo");
					
			}
			/*if($("#archivo").val() == 0){
			error = true;
			$("#archivo_capacitacion").addClass("alertaCombo");
		}*/
			
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente')
				$('#_actualizar').click();
		}
	}

	$(document).ready(function(){
		cargarValorDefecto("tipo_certificado","<?php echo $capacitacion['tipo_certificado']?>");
		$("input").attr("disabled","disabled");
		$("select").attr("disabled","disabled");

		$( "#fecha_inicio" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      yearRange: '-100:+0'
		    });
		$( "#fecha_fin" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      yearRange: '-100:+0'
		    });

		construirValidador();
		distribuirLineas();
	});


</script>

