<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$usuario = $_SESSION['usuario'];

$canton=  $cc->listarSitiosLocalizacion($conexion, 'CANTONES');
$parroquia = $cc->listarSitiosLocalizacion($conexion, 'PARROQUIAS');

?>
<header>
	<h1>Registro de asociaciones</h1>
</header>
	<div id="estado"></div>
	<div id="mensajeCargando"></div>
	
	<form id='nuevoAsociacion' data-rutaAplicacion='registroAsociacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		
	<fieldset id="registroAsociacion">
			<legend>Datos de la asociación</legend>
				<div data-linea="1">
					<label>Nombre(*):</label>
					<input type="text" id="nombreAsociacion" name="nombreAsociacion"/>
				</div>
				<div data-linea="2">
					<label>Dirección(*):</label>
					<input type="text" id="direccionAsociacion" name="direccionAsociacion"/>
				</div>	
				
				<div data-linea="3">
					<label>E-mail(*):</label>
					<input type="text" id="mailAsociacion" name="mailAsociacion"/>
				</div>		
				
				<div data-linea="4">
					<label>Teléfono:</label>
					<input type="text" id="telefonoAsociacion" name="telefonoAsociacion"  data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" />
				</div>
				<div data-linea="4">
					<label>Fecha:</label>
					<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo date('d/m/Y')?>" readonly/>
				</div>
				
				<div data-linea="5">
					<label for="provincia">Provincia(*):</label>
					<select name="provincia" id="provincia">
						<option value="">Provincia....</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion, 'PROVINCIAS');
							
							foreach ($provincias as $provincia){
								echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
							}
						?>
					</select>
				</div>
				<div id="lProvincia"></div>
				<div  data-linea="5">
					<label for="canton">Cantón(*):</label>
					<select name="canton" id="canton" disabled="disabled">
						<option value="">Cantón....</option>
					</select>
				</div>
				<div id="lCanton"></div>		
				<div  data-linea="6">
					<label for="parroquia">Parroquia(*):</label>
					<select name="parroquia" id="parroquia" disabled="disabled">
						<option value="">Parroquia....</option>
					</select>
				</div>
				<div id="lParroquia"></div>
						
				<div data-linea="7">
			      <label>Seleccione Adjunto(*):</label>
			      	<input type="hidden" class="rutaArchivo" id="rutaArchivo" name="rutaArchivo" value="0"/>
            		<input type="file"  id="estadoCarga" class="archivo" accept="application/msword | application/pdf | image/*"/>
            		<div class="estadoCarga" >En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
           			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroAsociacion/archivosAdjuntos">Subir archivo</button> 
		      	</div> 
				
	</fieldset>
	<?php //echo 'fecha'. date('d/m/Y');?>
	
	<div  data-linea="5">
					<button id="btnGuardar" type="submit" name="btnGuardar">Guardar Asociación</button>
				</div>	
		
	</form>

<script type="text/javascript">

	var canton = <?php echo json_encode($canton);?>;
	var parroquia = <?php echo json_encode($parroquia);?>;
									
	$(document).ready(function(){
		construirValidador();
		distribuirLineas();	
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#provincia").change(function(){
		scanton ='0';
		scanton = '<option value="">Cantón...</option>';
		for(var i=0;i<canton.length;i++){
		      if ($("#provincia").val()==canton[i]['padre']){
		      	scanton += '<option value="'+canton[i]['codigo']+'">'+canton[i]['nombre']+'</option>';
		      }
		}
		$('#canton').html(scanton);
		$("#canton").removeAttr("disabled");
	});
	
	$("#canton").change(function(){
		  sparroquia ='0';
		  sparroquia = '<option value="">Parroquia...</option>';
		     for(var i=0;i<parroquia.length;i++){
		      if ($("#canton").val()==parroquia[i]['padre']){
		       sparroquia += '<option value="'+parroquia[i]['codigo']+'">'+parroquia[i]['nombre']+'</option>';
		       } 
		     }
	
		  $('#parroquia').html(sparroquia);
		  $("#parroquia").removeAttr("disabled");
	});
	
	
	$("button.subirArchivo").click(function (event) {
	  	numero = Math.floor(Math.random()*100000000);
	  	  
	    var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	
	        subirArchivo(
	            archivo
	            , $("#nombreAsociacion").val()+'_'+$("#mailAsociacion").val().replace(/[_\W]+/g, "-")+'_'+numero
	            , boton.attr("data-rutaCarga")
	            , rutaArchivo
	            , new carga(estado, archivo, boton)
	            
	        );
	    } else {
	        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	        archivo.val("");        }
	} );



	$("#nuevoAsociacion").submit(function(event){
	    
	    event.preventDefault();
	
	    $(".alertaCombo").removeClass("alertaCombo");
	  	var error = false;
	
			if($("#nombreAsociacion").val()==""){	
				error = true;		
				$("#nombreAsociacion").addClass("alertaCombo");
			}
	
			if($("#direccionAsociacion").val()==""){	
				error = true;		
				$("#direccionAsociacion").addClass("alertaCombo");
			}
			
			if($("#mailAsociacion").val()==""){	
				error = true;		
				$("#mailAsociacion").addClass("alertaCombo");
			}	
	
			if($("#provincia").val()==""){	
				error = true;		
				$("#provincia").addClass("alertaCombo");
			}
	
			if($("#canton").val()==""){	
				error = true;		
				$("#canton").addClass("alertaCombo");
			}
	
			if($("#parroquia").val()==""){	
				error = true;		
				$("#parroquia").addClass("alertaCombo");
			}	

			if($("#rutaArchivo").val()=="0"){
				error = true;
				$("#estadoCarga").addClass("alertaCombo");
			}
					
			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}else{
					$('#nuevoAsociacion').attr('data-opcion','guardarNuevoAsociacion');
					 abrir($(this),event,false);                          
			}
	});

</script>
