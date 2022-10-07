<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$experiencia_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->modificarExperienciaLaboral($conexion,$experiencia_seleccionado);
$experiencia = pg_fetch_assoc($res);
$identificador=$_SESSION['usuario'];

$qExperiencia = $ce->obtenerExperienciaLaboral($conexion, $_SESSION['usuario']);

while($fila = pg_fetch_assoc($qExperiencia)){
	if($fila['id_experiencia_laboral']!=$experiencia_seleccionado){
		if($fila['fecha_salida']!=''){
				$experienciaLaboral[]= array(institucion=>$fila['institucion'], puesto=>$fila['puesto'],
				ingreso=>date_format (DateTime::createFromFormat('Y-m-d',$fila['fecha_ingreso']),'d/m/Y'),
				salida=>date_format (DateTime::createFromFormat('Y-m-d',$fila['fecha_salida']),'d/m/Y'));
		}else{
			$experienciaLaboral[]= array(institucion=>$fila['institucion'], puesto=>$fila['puesto'],
				ingreso=>date_format (DateTime::createFromFormat('Y-m-d',$fila['fecha_ingreso']),'d/m/Y'),
				salida=>$fila['fecha_salida']);
			
		}
		
	}
}

?>

<header>
	<h1>Modificar Experiencia Laboral</h1>
</header>

<form id="datosExperiencia" data-rutaAplicacion="uath" data-opcion="guardarExperienciaLaboral">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
	<input type="hidden" id="id" value="<?php echo $experiencia_seleccionado;?>" name="id" /> 

	<p>
		<button id="modificar" type="button" class="editar" <?php echo ($experiencia['estado']=='Aceptado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizar" type="submit" class="guardar"
			disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Experiencia</legend>
					<div data-linea="1">
						<label>Tipo Institucion</label> 
							<select id="tipo_institucion" name="tipo_institucion">
							<option value="Publica">Pública</option>
							<option value="Privada">Privada</option>
						    </select>
					</div>
					<div data-linea="1">
						<label>Trabajo hasta la fecha actual</label> 
							<input type="checkbox" id="trabajoActual" name="trabajoActual" <?php echo ($experiencia['fecha_salida']==''? ' checked="true"':'')?>/>
					</div>
					
					<div data-linea="2">
						<label>Institucion</label> 
							<input type="text" name="institucion" id="institucion" value="<?php echo $experiencia['institucion']; ?>"
							disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
					</div>
					<div data-linea="3">
						<label>Unidad Administrativa</label> 
							<input type="text" id="unidad_administrativa" name="unidad_administrativa" value="<?php echo $experiencia['unidad_administrativa']; ?>"
							disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
					</div>
					<div data-linea="4">
						<label>Puesto</label>
							<input type="text" name="puesto" id="puesto" value="<?php echo $experiencia['puesto']; ?>" disabled="disabled"
							data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$" />
					</div>
					<div data-linea="5">
						<label>Fecha Ingreso</label> 
							<input type="text" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo date('d/m/Y',strtotime($experiencia['fecha_ingreso'])); ?>"
							disabled="disabled" required="required" />
					</div>
					<div data-linea="5">
						<label>Fecha Salida</label> 
							<input type="text" id="fecha_salida" name="fecha_salida" value="<?php echo $experiencia['fecha_salida']!=''?date('d/m/Y',strtotime($experiencia['fecha_salida'])):''; ?>"
							disabled="disabled"/>
					</div>
					<div data-linea="6">
						<label>Motivo Ingreso</label> 
							<input type="text" id="motivo_ingreso" name="motivo_ingreso"  value="<?php echo $experiencia['motivo_ingreso']; ?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" maxlength=1024/>
					</div>
					<div data-linea="7">
						<label>Motivo Salida</label> 
							<input type="text" id="motivo_salida" name="motivo_salida" value="<?php echo $experiencia['motivo_salida']; ?>"
							disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
					</div>
					<div data-linea="8">
						<label>Archivo Académico</label> <?php echo ($experiencia['archivo_experiencia']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$experiencia['archivo_experiencia'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					</div>
					<div data-linea="9">
						<!-- >input type="file" name="archivo_experiencia"  id='archivo_experiencia' accept="application/msword | application/pdf | image/*" /-->
						<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $experiencia['archivo_experiencia'];?>" />
						<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosExperiencia" >Subir archivo</button>
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">

var array_Experiencia= <?php echo json_encode($experienciaLaboral); ?>;

$('#trabajoActual').click(function(event){
	 if($('#trabajoActual').is(':checked')){
   	  $('#fecha_salida').attr("disabled","disabled");
   	  $('#fecha_salida').val('');
   	  $('#motivo_salida').attr("disabled","disabled");
   	  $("#motivo_salida").val('');
      }
     else{
   	  $('#fecha_salida').removeAttr("disabled");
   	  $('#motivo_salida').removeAttr("disabled");
         }

});

	$(document).ready(function(){
		cargarValorDefecto("tipo_institucion","<?php echo $experiencia['tipo_institucion']?>");
		$("#datosExperiencia input").attr("disabled","disabled");
		$("#datosExperiencia select").attr("disabled","disabled");
				
		$( "#fecha_ingreso" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      yearRange: '-100:+0'
		});
		$( "#fecha_salida" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      yearRange: '-100:+0'
		});
		construirValidador();
		distribuirLineas();
		
	});
	
	$("#datosExperiencia #modificar").click(function(){
		$("#datosExperiencia input").removeAttr("disabled");
		$("#datosExperiencia select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		if($('#trabajoActual').is(':checked')){
		   	  $('#fecha_salida').attr("disabled","disabled");
		   	  $('#fecha_salida').val('');
		   	  $('#motivo_salida').attr("disabled","disabled");
		   	  $("#motivo_salida").val('');
		}
		
	});

	$("#datosExperiencia").submit(function(event){
		var validar = 0;
		
		event.preventDefault();

		if(array_Experiencia != null){
			for(var i=0; i<array_Experiencia.length; i++){
				if (array_Experiencia[i]['institucion'].replace(/ /g,'').toUpperCase() == $("#institucion").val().replace(/ /g,'').toUpperCase() && array_Experiencia[i]['puesto'].replace(/ /g,'').toUpperCase() == $("#puesto").val().replace(/ /g,'').toUpperCase() && array_Experiencia[i]['ingreso'] == $("#fecha_ingreso").val()){
					alert('La información ingresado ya ha sido registrada.');
					validar=1;
					break;
				}
			}
		}

		if(validar==0){
			chequearCampos(this);
		}
  	});


	/*$('#archivo_experiencia').change(function(event){

		  $("#estado").html('');
			var archivo = $("#archivo_experiencia").val();
			var extension = archivo.split('.');
			$("#actualizar").attr("disabled","disabled");
			if(extension[extension.length-1].toUpperCase() == 'PDF' ){
				var numero = Math.floor(Math.random()*100000000);
				var x = document.getElementById("archivo_experiencia");
				var file = x.files[0];
				if(file.size<3145728){
				  if($("#fecha_ingreso").val() !=""){
					 subirArchivo('archivo_experiencia','< ?php echo $_SESSION['usuario'];?>'+numero+'_'+$("#fecha_ingreso").val().replace(/[_\W]+/g, "-"),'aplicaciones/uath/archivosExperiencia', 'archivo');
					$("#actualizar").removeAttr("disabled");}
				  else{
					 alert("Debe seleccionar una fecha de ingreso!");
					 $("#archivo_experiencia").val("");
					 
				  }
				}else{
					$("#estado").html('El peso del archivo es mayor a 3MB!').addClass("alerta");
					$("#actualizar").attr("disabled","disabled");
					}
			}else{
				$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
				$('#archivo_experiencia').val('');
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

	        	if($("#fecha_ingreso").val() !=""){
		        	
	        		subirArchivo(
	    	                archivo
	    	                , usuario+'_'+numero+"_"+$("#fecha_ingreso").val().replace(/[_\W]+/g, "-")
	    	                , boton.attr("data-rutaCarga")
	    	                , rutaArchivo
	    	                , new carga(estado, archivo, boton)
	    	            );
    	            
				}else{
					 alert("Debe seleccionar una fecha de ingreso!");
					 archivo.val("");
				} 
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("");
	        }
	    });

	  function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}

		function chequearCampos(form){
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			if(!$.trim($("#tipo_institucion").val())){
				error = true;
				$("#tipo_institucion").addClass("alertaCombo");
			}

			if(!$.trim($("#institucion").val()) || !esCampoValido("#institucion")){
				error = true;
				$("#institucion").addClass("alertaCombo");
			}

			if(!$.trim($("#unidad_administrativa").val()) || !esCampoValido("#unidad_administrativa")){
				error = true;
				$("#unidad_administrativa").addClass("alertaCombo");
			}

			if(!$.trim($("#puesto").val()) || !esCampoValido("#puesto")){
				error = true;
				$("#puesto").addClass("alertaCombo");
			}

			if((!$.trim($("#motivo_salida").val()) || !esCampoValido("#motivo_salida"))&&(!$('#trabajoActual').is(':checked'))){
				error = true;
				$("#motivo_salida").addClass("alertaCombo");
			}
			/*if($("#archivo").val() == 0){
			error = true;
			$("#archivo_experiencia").addClass("alertaCombo");
		}*/

			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				ejecutarJson(form);
				if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente')
					$('#_actualizar').click();
			}
		}
</script>
