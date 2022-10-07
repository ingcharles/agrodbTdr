<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	require_once '../../clases/ControladorCatalogos.php';

	$idMaterialPeligroso = $_POST['id'];
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	$cc = new ControladorCatalogos();
	
	$qMaterialPeligroso=$so->buscarMaterialPeligroso($conexion, $idMaterialPeligroso);
	$filaMaterialPeligroso=pg_fetch_assoc($qMaterialPeligroso);
	
	$qBuscarGuiaMaterialPeligroso=$so->buscarGuiaMaterialPeligroso($conexion, $filaMaterialPeligroso['id_guia_material_peligroso']);
	$filaGuiaMaterialPeligroso=pg_fetch_assoc($qBuscarGuiaMaterialPeligroso);
	

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Registro Material Peligroso</h1>
	</header>
	<div id="estado"></div>
		<form id="actualizarMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoMaterialPeligroso" >
			
			<div>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>
			
			<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
			<input type="hidden" id="idMaterialPeligroso" name="idMaterialPeligroso" value="<?php echo $filaMaterialPeligroso['id_material_peligroso'];?>">
				
				<fieldset>
					<legend>Datos Material Peligroso</legend>	
					<div data-linea="1">			
						<label>Nombre químico:</label> 
						<input type="text" id="nombreProductoUno" name="nombreProductoUno" value="<?php echo $filaMaterialPeligroso['nombre_material_peligroso'];?>" disabled="disabled"/>	
					</div>
						
					<div data-linea="2">			
						<label>Número UN:</label> 
						<input type="text" id="numeroUnUno" name="numeroUnUno" onkeypress='ValidaSoloNumeros()' value="<?php echo $filaMaterialPeligroso['numero_un_material_peligroso'];?>" disabled="disabled" data-er="^[0-9]+$"  />	
					</div>
					
					<div data-linea="2">			
						<label>Número CAS:</label> 
						<input type="text" id="numeroCasUno" name="numeroCasUno" value="<?php echo $filaMaterialPeligroso['numero_cas_material_peligroso'];?>" disabled="disabled" />	
					</div>	
					
					<div data-linea="4" id="lineaGuia">
						<label>Ver guía:</label>
						<?php echo ($filaGuiaMaterialPeligroso['ruta_guia_material_peligroso']==''? '<span class="alerta">No ha cargado ninguna guía</span>':'<a class="img guia" style="cursor: pointer">Clic aquí para ver la guía '.$filaGuiaMaterialPeligroso['numero_guia_material_peligroso'].' </a>')?>
					</div>
		
					<div data-linea="3">
						<label>Nombre guía:</label> 
						<select name="guia" id="guia" disabled="disabled">
							<option value="" >Seleccione...</option>
							<?php
								$qGuiasMaterialesPeligrosos = $cc->listaGuiasMaterialesPeligrosos($conexion);
								while ($fila = pg_fetch_assoc($qGuiasMaterialesPeligrosos)){
								    	echo '<option  value="' . $fila['id_guia_material_peligroso'] . '">'.$fila['numero_guia_material_peligroso'].' - '. $fila['nombre_guia_material_peligroso'] . '</option>';
								}		    
							?>
						</select>
					</div>
			
					<div data-linea="4" id="lineaMsds">
						<label>Ver MSDS:</label>
						<?php echo ($filaMaterialPeligroso['ruta_msds_material_peligroso']==''? '<span class="alerta">No ha cargado ningún MSDS</span>':'<a class="img msds" style="cursor: pointer">Clic aquí para ver el msds </a>')?>
					</div>
					
					<div data-linea="5">
						<label>MSDS:</label>
						<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $filaMaterialPeligroso['ruta_msds_material_peligroso'];?>" />
						<input type="file" id="informe" class="archivo" name="informe" accept="application/msword | application/pdf | image/*" disabled="disabled"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/msds" disabled="disabled" >Subir archivo</button>
					</div>
					
					<div data-linea="6">			
						<label>Descripción:</label> 
					</div>
				
					<div data-linea="7">			
						<textarea id="descripcion" name="descripcion"  placeholder="Ej: Descripción..." rows="5" disabled="disabled"><?php echo $filaMaterialPeligroso['descripcion_material_peligroso'];?></textarea>
					</div>
				</fieldset>
		</form>
		
		<form id="nuevoRegistro" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoMaterialPeligrosoClasificacionRiesgo" >
			<input type="hidden" id="idMaterialPeligrosoDos" name="idMaterialPeligrosoDos" value="<?php echo $filaMaterialPeligroso['id_material_peligroso'];?>">
						
			<fieldset>
				<legend>Nuevo Pictograma de Riesgo</legend>	
				<div data-linea="2">			
					<label>Pictograma:</label> 
					<select id="clasificacionRiesgoMaterialPeligroso" name="clasificacionRiesgoMaterialPeligroso" style=" width:100%" required >
						<option value="">Seleccione....</option> 
							<?php 
							$qClasificacionRiegosMaterialesPeligrosos=$cc->listaClasificacionRiegosMaterialesPeligrosos($conexion);
							while ($fila=pg_fetch_assoc($qClasificacionRiegosMaterialesPeligrosos)){
									echo '<option data-ruta-clasificacion="'.$fila['ruta_img_clasificacion_riesgo_material_peligroso'].'" value="'.$fila['id_clasificacion_riesgo_material_peligroso'] . '">'.  $fila['nombre_clasificacion_riesgo_material_peligroso'] .'</option>';
								}
							?>
					</select>
				</div>
			
				<div>
					<button type="submit" class="mas">Añadir pictograma</button>
				</div>
			</fieldset>
		</form>
				
			<fieldset>
				<legend>Pictogramas</legend>
				<table id="registros">
					<?php
					$qAbrirClasificacionRiegosMaterialesPeligrosos=$so->abrirClasificacionRiesgoXMaterialPeligroso($conexion, $filaMaterialPeligroso['id_material_peligroso']);
						while ($fila = pg_fetch_assoc($qAbrirClasificacionRiegosMaterialesPeligrosos)){
							echo $so->imprimirLineaMaterialPeligrosoClasificacionRiesgo($fila['id_material_peligroso_clasificacion_riesgo'], $fila['nombre_clasificacion_riesgo_material_peligroso'], $fila['ruta_img_clasificacion_riesgo_material_peligroso']);
						}
					?>
				</table>
			</fieldset>
	
			<div id="pdf">
        		<iframe style='width:550px; height:750px;' >
          		</iframe>
   			</div>
</body>
<script>
var msds= <?php echo json_encode($filaMaterialPeligroso['ruta_msds_material_peligroso']); ?>;
var guia= <?php echo json_encode($filaGuiaMaterialPeligroso['ruta_guia_material_peligroso']); ?>;
var idGuia= <?php echo json_encode($filaMaterialPeligroso['id_guia_material_peligroso']); ?>;

	$('document').ready(function(){
		cargarValorDefecto("guia","<?php echo $filaMaterialPeligroso['id_guia_material_peligroso']?>");
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
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$(".subirArchivo").removeAttr("disabled");
	});

	$(".img").click(function(){
		$("#pdf").show();
		if($(this).hasClass("msds"))
			$("#pdf iframe").attr("src",msds);
		else
			$("#pdf iframe").attr("src",guia);
	});
	
	$("#guia").change(function(){
		if($("#guia").val()==idGuia)
			$("#lineaGuia").show();
		else
			$("#lineaGuia").hide();
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
		$("#lineaMsds").hide();
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
	
	$("#actualizarMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorNumero = false;
		
			if(!$.trim($("#nombreProductoUno").val())){
				error = true;
				$("#nombreProductoUno").addClass("alertaCombo");
			}

			if($.trim($("#numeroUnUno").val()) ){
				if(!esCampoValido("#numeroUnUno") ){
					error = true;
					errorNumero = true;
					$("#numeroUnUno").addClass("alertaCombo");
				}
			}
			
			if(!$.trim($("#numeroCasUno").val())){
				error = true;
				$("#numeroCasUno").addClass("alertaCombo");
			}

			if(!$.trim($("#descripcion").val())){
				error = true;
				$("#descripcion").addClass("alertaCombo");		
			}
			
			if (error){
				$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
				if (errorNumero){
					$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}
			}else{
				ejecutarJson("#actualizarMaterialPeligroso");
				if( $('#estado').html()=='Los datos han sido actualizados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});


</script>
</html>