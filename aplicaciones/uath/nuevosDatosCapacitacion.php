<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
$res = $cc->listarLocalizacion($conexion, 'PAIS');


$identificador=$_SESSION['usuario'];
?>

<header>
	<h1>Nuevos Datos Capacitaciones</h1>
</header>

<form id="datosCapacitacion" data-rutaAplicacion="uath" data-opcion="guardarDatosCapacitacion">
	<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="Nuevo" name="opcion" />
	
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Capacitación</legend>
					<div data-linea="1">
						<label>Título de capacitación</label> 
							<input type="text" id="titulo" name="titulo" value="<?php echo $academico['titulo']; ?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü/0-9 ]+$" />
					</div>
					<div data-linea="2">
						<label>Auspiciante</label> 
							<input type="text" id="auspiciante" name="auspiciante" value="<?php echo $academico['auspiciante']; ?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü/0-9 ]+$" maxlength=128/>
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
							<input type="text" id="institucion" name="institucion" value="<?php echo $academico['institucion']; ?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
					</div>
					
					<div data-linea="5">
						<label>País</label> 
							<select name="pais" id="pais">
								<option value="" >Seleccione....</option>
								<?php
									while($pais = pg_fetch_assoc($res)){
										echo '<option value="'.$pais['nombre'].'">'.$pais['nombre'].'</option>';
									}
								?>
							</select>
					</div>
											
					<div data-linea="5">
						<label>Horas</label> 
							<input type="text" name="horas" id="horas" value="<?php echo $academico['horas']; ?>"
							placeholder="Ej. 999" data-inputmask="'mask': '9[999]'" data-er="[0-9]{1,10}" title="99" />
					</div>
					
					<div data-linea="6">		
			<label>Fecha Inicio</label>
				<input type="text"	id="fecha_inicio" name="fecha_inicio" value="<?php echo $contrato['fecha_inicio']; ?>" required="required" readonly="readonly" />
		</div>
		
		<div data-linea="6">
			<label>Fecha Fin</label>
				<input type="text"	id="fecha_fin" name="fecha_fin"	 value="<?php echo  $contrato['fecha_fin']; ?>" required="required" readonly="readonly" />
		</div>					
					
					<div data-linea="7">
						<label>Archivo Capacitación</label> 
							<!-- input type="file" name="archivo_capacitacion" id='archivo_capacitacion' accept="application/msword | application/pdf | image/*" /-->
							
							<input type="hidden" class="rutaArchivo" name="archivo" value="0" /> 
						<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosCapacitacion" >Subir archivo</button>
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
		
	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
</form>

<script type="text/javascript">
	$(document).ready(function(){
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

	/*$('#archivo_capacitacion').change(function(event){

		$("#estado").html('');
		var archivo = $("#archivo_capacitacion").val();
		var extension = archivo.split('.');
		$("#actualizar").attr("disabled","disabled");
		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			var x = document.getElementById("archivo_capacitacion");
			var numero = Math.floor(Math.random()*100000000);
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
    	                , usuario+'_'+numero
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, boton)
    	            );
	            
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#datosCapacitacion").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
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
			if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizar').click();
		}
	}
</script>

