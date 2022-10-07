<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$academico_seleccionado=$_POST['id'];
$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
$res = $ce->obtenerCursoAcademico($conexion, $_SESSION['usuario'], $academico_seleccionado);
$academico = pg_fetch_assoc($res);
$qPais = $cc->listarLocalizacion($conexion, 'PAIS');
$identificador=$_SESSION['usuario'];
$res2 = $cc->listarTitulosOCarrera($conexion, 'TITULOS');
$res3 = $cc->listarTitulosOCarrera($conexion, 'CARRERAS');
?>

<header>
	<h1>Modificar Datos Academicos</h1>
</header>

<form id="datosAcademicos" data-rutaAplicacion="uath" data-opcion="guardarDatosAcademicos"> 
	<input type="hidden" id="usuario" name="usuario" value="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
	<input type="hidden" id="academico_seleccionado" value="<?php echo $academico_seleccionado;?>" name="academico_seleccionado" /> 
	<p>
		<button id="modificar" type="button" class="editar" <?php echo ($academico['estado']=='Aceptado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizar" type="submit" class="guardar"
			disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Académica</legend>
					<div data-linea="1">
						<label>Nivel de Instrucción</label> 
							<select name="nivel_instruccion" id="nivel_instruccion" disabled="disabled">
								<option value="" >Seleccione....</option>
								<option value="Bachillerato" data-tipo="Ninguna">Bachillerato</option>
								<option value="Cuarto Nivel-Diplomado" data-tipo="Obligatorio">Cuarto Nivel-Diplomado</option>
								<option value="Cuarto Nivel-Especialidad" data-tipo="Obligatorio">Cuarto Nivel-Especialidad</option>
								<option value="Cuarto Nivel-Maestría" data-tipo="Obligatorio">Cuarto Nivel-Maestría</option>
								<option value="Cuarto Nivel-Doctorado" data-tipo="Obligatorio">Cuarto Nivel-Doctorado</option>
								<option value="Educación Básica" data-tipo="Ninguna">Educación Básica</option>
								<option value="Estudiante Universitario" data-tipo="Ninguna">Estudiante Universitario</option>
								<option value="Primaria" data-tipo="Ninguna">Primaria</option>
								<option value="Secundaria" data-tipo="Ninguna">Secundaria</option>
								<option value="Sin instrucción" data-tipo="Ninguna">Sin instrucción</option>
								<option value="Técnico Superior" data-tipo="Obligatorio">Técnico Superior</option>
								<option value="Tecnología" data-tipo="Obligatorio">Tecnología</option>
								<option value="Tercer Nivel" data-tipo="Obligatorio">Tercer Nivel</option>	
							</select>
					</div>
					<div data-linea="2">
						<label>País</label> <select name="pais" id="pais">
							<option value="" >Seleccione....</option>
							<?php
								while($pais = pg_fetch_assoc($qPais)){
									$pais_Select=($pais['nombre']==$academico['pais'])?' selected="selected"':''; // tener en cuenta la otra manera de buscar un elemento en un select
									echo '<option value="'.$pais['nombre'].'"'.$pais_Select.'>'.$pais['nombre'].'</option>';
								}
							?>
						</select>
					</div>
					
					<div data-linea="2">
						<label>Institución</label> 
							<input type="text" id="institucion" name="institucion" value="<?php echo $academico['institucion']; ?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
					</div>
					
					<div data-linea="3">
						<label>Título</label> 
							<select name="titulo" id="titulo">
								<option value="" >Seleccione....</option>
								<?php
									while($titulo = pg_fetch_assoc($res2)){
										echo '<option value="'.$titulo['titulo_carrera'].'">'.$titulo['titulo_carrera'].'</option>';
									}
								?>
							</select>
					</div>
					
					<div data-linea="3">
						<label>Carrera</label> 
						<select name="carrera" id="carrera">
						<option value="" >Seleccione....</option>
							<?php
									while($carrera = pg_fetch_assoc($res3)){
										echo '<option value="'.$carrera['titulo_carrera'].'">'.$carrera['titulo_carrera'].'</option>';
									}
								?>	
					   </select>				
					</div>
					
					<div data-linea="4">
						<label>N° Certificado</label> 
							<input type="text" name="num_certificado" id="num_certificado" value="<?php echo $academico['num_certificado']; ?>"
							disabled="disabled" placeholder="Ej. 9999-99-999999"
							 />
					</div>
					
					<div data-linea="4">
						<label>Años de Estudio</label> 
							<input type="text" name="años_estudio" id="años_estudio" value="<?php echo $academico['anios_estudio']; ?>" 
							disabled="disabled" placeholder="Ej. 9999" data-inputmask="'mask': '9[9]'" data-er="[0-9]{1,2}" title="99" />
					</div>
					<div data-linea="5">
						<label>Egresado</label> 
							<input type="checkbox" name="egresado" id="egresado" value="Si"/>
					</div>		
					<div data-linea="6">
						<label>Archivo Académico</label> <?php echo ($academico['archivo_academico']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$academico['archivo_academico'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					</div>
					<div data-linea="7">
						<!-- input type="file" name="archivo_academico" id='archivo_academico' accept="application/msword | application/pdf | image/*" /-->
						<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $academico['archivo_academico'];?>" />
						<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosAcademicos" >Subir archivo</button>
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

	/*$('#archivo_academico').change(function(event){

		$("#estado").html('');
		var archivo = $("#archivo_academico").val();
		var extension = archivo.split('.');
		$("#actualizar").removeAttr("disabled","disabled");
		var numero = $("#num_certificado").val();
			
		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			var x = document.getElementById("archivo_academico");
			var file = x.files[0];
			if(file.size<3145728){
				if(numero== ""){
					numero = Math.floor(Math.random()*100000000);		
				}
			  	subirArchivo('archivo_academico','< ?php echo $_SESSION['usuario'].'_id_Certificado_';?>'+numero,'aplicaciones/uath/archivosAcademicos', 'archivo');
		
		}else{
			$("#estado").html('El peso del archivo es mayor a 3MB!').addClass("alerta");
			$("#actualizar").attr("disabled","disabled");
			}
	}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#archivo_academico').val('');
		}
	});*/

	
	
	var usuario = <?php echo json_encode($_SESSION['usuario']);?>;
	 
	$('button.subirArchivo').click(function (event) {

		var numero = $("#num_certificado").val();
		if(numero== ""){
			numero = Math.floor(Math.random()*100000000);		
		}
		
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , usuario +"_id_Certificado_"+numero
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
		$(this).attr("disabled","disabled");

		if($("#nivel_instruccion option:selected").attr("data-tipo")=="Capacitacion"){
			$("#num_certificado").attr("disabled","disabled");
			$("#años_estudio").attr("disabled","disabled");
		}

		if($("#nivel_instruccion option:selected").attr("data-tipo")!="Capacitacion"){
			$("#num_certificado").removeAttr("disabled");
			$("#años_estudio").removeAttr("disabled");
		}
	});

	$('#nivel_instruccion').change(function(event){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		if($("#nivel_instruccion option:selected").val()=="Sin instrucción"){
			$("#pais").attr("disabled","disabled");
			$("#pais").val("");
			$("#institucion").attr("disabled","disabled");
			$("#institucion").val("");
			$("#titulo").attr("disabled","disabled");
			$("#titulo").val("");
			$("#carrera").attr("disabled","disabled");
			$("#carrera").val("");
			$("#num_certificado").attr("disabled","disabled");
			$("#num_certificado").val("");
			$("#años_estudio").attr("disabled","disabled");
			$("#años_estudio").val("");
			$("#archivo_academico").attr("disabled","disabled");
			
		}
		else if($("#nivel_instruccion option:selected").val()=="Primaria"){
			$("#titulo").attr("disabled","disabled");
			$("#titulo").val("");
			$("#carrera").attr("disabled","disabled");
			$("#carrera").val("");
			$("#num_certificado").attr("disabled","disabled");
			$("#num_certificado").val("");
			$("#archivo_academico").attr("disabled","disabled");
		}
		else if($("#nivel_instruccion option:selected").val()=="Secundaria"){
			$("#num_certificado").attr("disabled","disabled");
			$("#num_certificado").val("");
		}
		
	});
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nivel_instruccion").val())){
			error = true;
			$("#nivel_instruccion").addClass("alertaCombo");
		}
		else if($("#nivel_instruccion option:selected").val()!="Sin instrucción"){
			if(!$.trim($("#pais").val())){
				error = true;
				$("#pais").addClass("alertaCombo");
			}
	
			if(!$.trim($("#institucion").val()) || !esCampoValido("#institucion")){
				error = true;
				$("#institucion").addClass("alertaCombo");
			}
			
			if(!$.trim($("#titulo").val()) && (!$.trim($("#carrera").val())) && ($("#nivel_instruccion option:selected").val()!="Primaria")){
				error = true;
				$("#titulo").addClass("alertaCombo");
				$("#carrera").addClass("alertaCombo");
			}
			
			if($("#nivel_instruccion option:selected").attr("data-tipo")!="Capacitacion"){
				if(!$.trim($("#años_estudio").val()) || !esCampoValido("#años_estudio")){
					error = true;
					$("#años_estudio").addClass("alertaCombo");
				}
			}

			if($("#nivel_instruccion option:selected").attr("data-tipo")=="Obligatorio"){
				if(!$.trim($("#num_certificado").val()) || !esCampoValido("#num_certificado")){
					error = true;
					$("#num_certificado").addClass("alertaCombo");
			      }
			}
			/*if($("#archivo").val() == 0){
			error = true;
			$("#archivo_academico").addClass("alertaCombo");
		}*/	
			
		}
		

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente')
				$('#_actualizar').click();
		}
	}
	

	$(document).ready(function(){
		cargarValorDefecto("nivel_instruccion","<?php echo $academico['nivel_instruccion']?>");
		$("input").attr("disabled","disabled");
		$("select").attr("disabled","disabled");
		cargarValorDefecto("titulo","<?php echo $academico['titulo']?>");
		cargarValorDefecto("carrera","<?php echo $academico['carrera']?>");

		construirValidador();
		distribuirLineas();
	});


</script>
