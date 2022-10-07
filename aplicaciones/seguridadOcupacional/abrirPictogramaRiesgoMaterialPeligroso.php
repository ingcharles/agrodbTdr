<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	
	$idClasificacionRiesgoMaterialPeligroso = $_POST['id'];
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	
	$qClasificacionRiesgoMaterialPeligroso=$so->buscarClasificacionRiesgoMaterialPeligroso($conexion, $idClasificacionRiesgoMaterialPeligroso);
	$filaClasificacionRiesgoMaterialPeligroso=pg_fetch_assoc($qClasificacionRiesgoMaterialPeligroso);
	

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Registro Pictograma de Riesgo</h1>
	</header>
		<form id="actualizarPictogramaRiesgoMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoPictogramaRiesgoMaterialPeligroso" >
			<div id="estado"></div>
			
			<div>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>
			
			<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
			<input type="hidden" id="idPictogramaRiesgoMaterialPeligroso" name="idPictogramaRiesgoMaterialPeligroso" value="<?php echo $filaClasificacionRiesgoMaterialPeligroso['id_clasificacion_riesgo_material_peligroso'];?>">
				
			<fieldset>
				<legend>Datos Pictograma de Riesgo</legend>	
				
				<div data-linea="1">			
					<label>Nombre pictograma:</label> 
					<input type="text" id="nombrePictogramaUno" name="nombrePictogramaUno" value="<?php echo $filaClasificacionRiesgoMaterialPeligroso['nombre_clasificacion_riesgo_material_peligroso'];?>" disabled="disabled"/>	
				</div>
				
				<div data-linea="2" >
					<label>Pictograma:</label>
				</div>
	
				<div data-linea="3" >
					<?php echo	"<img src='".$filaClasificacionRiesgoMaterialPeligroso['ruta_img_clasificacion_riesgo_material_peligroso']."' style='no-repeat; width: 150px;' />"; ?>
				</div>
	
				<div data-linea="4">
					<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="<?php echo $filaClasificacionRiesgoMaterialPeligroso['ruta_img_clasificacion_riesgo_material_peligroso'];?>" />
					<input type="file" id="informe" class="archivo" name="informe" accept="image/png" disabled="disabled" />
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
					<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/img/pictogramas" disabled="disabled">Subir archivo</button>
				</div>
			</fieldset>
		</form>
</body>
<script>

	$('document').ready(function(){
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$(".subirArchivo").removeAttr("disabled");
	});

	var normalize = (function() {
		  var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
		      to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
		      mapping = {};
		 
		  for(var i = 0, j = from.length; i < j; i++ )
		      mapping[ from.charAt( i ) ] = to.charAt( i );
		 
		  return function( str ) {
		      var ret = [];
		      for( var i = 0, j = str.length; i < j; i++ ) {
		          var c = str.charAt( i );
		          if( mapping.hasOwnProperty( str.charAt( i ) ) )
		              ret.push( mapping[ c ] );
		          else
		              ret.push( c );
		      }      
		      return ret.join( '' );
		  }; 
	})();
	
	$('button.subirArchivo').click(function (event) {
		numero = Math.floor(Math.random()*100000000);	
	
		var fileName = $("#informe")[0].files[0].name;
		var nombreArchivo =  fileName.substring(0, fileName.lastIndexOf("."));
		nombreArchivo=normalize(nombreArchivo);
		
		var boton = $(this);
		$("#archivo").val(nombreArchivo.replace(" ", "_"));
		nombreArchivo=nombreArchivo.replace(" ", "_");
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PNG') {
            subirArchivo(
                archivo
                , nombreArchivo+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato PNG');
            archivo.val("");
        }
    });

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	
	$("#actualizarPictogramaRiesgoMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

			if(!$.trim($("#nombrePictogramaUno").val())){
				error = true;
				$("#nombrePictogramaUno").addClass("alertaCombo");
			}

			if (error){
				$("#estado").html("Por favor seleccione todos los campos.").addClass('alerta');
			}else{
				ejecutarJson("#actualizarPictogramaRiesgoMaterialPeligroso");
				if( $('#estado').html()=='Los datos han sido actualizados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});
</script>
</html>