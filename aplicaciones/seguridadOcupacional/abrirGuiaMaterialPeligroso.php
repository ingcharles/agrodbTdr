<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	
	$idGuiaMaterialPeligroso = $_POST['id'];
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	
	$qGuiaMaterialPeligroso=$so->buscarGuiaMaterialPeligroso($conexion, $idGuiaMaterialPeligroso);
	$filaGuiaMaterialPeligroso=pg_fetch_assoc($qGuiaMaterialPeligroso);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Registro Guía (GRE)</h1>
	</header>
	
	<form id="actualizarGuiaMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoGuiaMaterialPeligroso" >
		<div id="estado"></div>
		<div>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
		
		<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
		<input type="hidden" id="idGuiaMaterialPeligroso" name="idGuiaMaterialPeligroso" value="<?php echo $filaGuiaMaterialPeligroso['id_guia_material_peligroso'];?>">
			
		<fieldset>
			<legend>Datos Guía (GRE)</legend>	
				<div data-linea="1">			
					<label>Nombre guía:</label> 
					<input type="text" id="nombreGuiaUno" name="nombreGuiaUno" value="<?php echo $filaGuiaMaterialPeligroso['nombre_guia_material_peligroso'];?>" disabled="disabled" />	
				</div>
					
				<div data-linea="2">			
					<label>Número guía:</label> 
					<input type="text" id="numeroGuiaUno" name="numeroGuiaUno" onkeypress='ValidaSoloNumeros()' value="<?php echo $filaGuiaMaterialPeligroso['numero_guia_material_peligroso'];?>" disabled="disabled" data-er="^[0-9]+$"  />	
				</div>
				
				<div data-linea="4" id="lineaGuia">
					<label>Ver guía:</label>
					<?php echo ($filaGuiaMaterialPeligroso['ruta_guia_material_peligroso']==''? '<span class="alerta">No ha cargado ninguna guía</span>':'<a class="img guia" style="cursor: pointer">Clic aquí para ver la guía '.$filaGuiaMaterialPeligroso['numero_guia_material_peligroso'].' </a>')?>
				</div>
	
				<div data-linea="5">
					<label>Guía:</label>
					<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $filaGuiaMaterialPeligroso['ruta_guia_material_peligroso'];?>" />
					<input type="file" id="informe" class="archivo" name="informe" accept="application/msword | application/pdf | image/*" disabled="disabled"/>
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
					<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/guias" disabled="disabled">Subir archivo</button>
				</div>
		</fieldset>
	</form>
	<div id="pdf">
		<iframe style='width:550px; height:750px;' >
    	</iframe>
   </div>
</body>
<script>

var guia= <?php echo json_encode($filaGuiaMaterialPeligroso['ruta_guia_material_peligroso']); ?>;

	$('document').ready(function(){
		acciones();
		distribuirLineas();
		$("#pdf").hide();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$(".subirArchivo").removeAttr("disabled");
	});

	$(".img").click(function(){
		$("#pdf").show();
		$("#pdf iframe").attr("src",guia);
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
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
            subirArchivo(
                archivo
                , nombreArchivo+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	
	$("#actualizarGuiaMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorNumero = false;
			if(!$.trim($("#nombreGuiaUno").val())){
				error = true;
				$("#nombreGuiaUno").addClass("alertaCombo");
			}
			
			if(!$.trim($("#numeroGuiaUno").val()) ){
				error = true;
				$("#numeroGuiaUno").addClass("alertaCombo");
			}

			if($.trim($("#numeroGuiaUno").val()) ){
				if(!esCampoValido("#numeroGuiaUno") ){
					error = true;
					errorNumero = true;
					$("#numeroGuiaUno").addClass("alertaCombo");
				}
			}
			
			if (error){
				$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
				if (errorNumero){
					$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}
			}else{
				ejecutarJson("#actualizarGuiaMaterialPeligroso");
				if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});
</script>
</html>