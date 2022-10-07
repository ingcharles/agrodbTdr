<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();;
	$cc = new ControladorCatalogos();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Nuevo Material Peligroso</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoMaterialPeligroso" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
			
		<fieldset>
			<legend>Datos Material Peligroso</legend>	
			
			<div data-linea="1">			
				<label>Nombre químico:</label> 
				<input type="text" id="nombreProductoUno" name="nombreProductoUno" placeholder="Ej: Dióxido de carbono" maxlength="512" />	
			</div>
				
			<div data-linea="2">			
				<label>Número UN:</label> 
				<input type="text" id="numeroUnUno" name="numeroUnUno" onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2841" maxlength="10" data-er="^[0-9]+$"   />	
			</div>
			
			<div data-linea="2">			
				<label>Número CAS:</label> 
				<input type="text" id="numeroCasUno" name="numeroCasUno" placeholder="Ej: 202-546" maxlength="16"  />	
			</div>	
			
			<div data-linea="3">
				<label>Nombre guía:</label> 
				<select name="guia" id="guia" >
					<option value="" >Seleccione...</option>
					<?php
						$qGuiasMaterialesPeligrosos = $cc->listaGuiasMaterialesPeligrosos($conexion);
						while ($fila = pg_fetch_assoc($qGuiasMaterialesPeligrosos)){
						    	echo '<option  value="' . $fila['id_guia_material_peligroso'] . '">'.$fila['numero_guia_material_peligroso'].' - '. $fila['nombre_guia_material_peligroso'] . '</option>';
						}		    
					?>
				</select>
			</div>
		
			<div data-linea="5">
				<label>MSDS:</label>
				<input type="hidden" class="rutaArchivo" id="archivo" name="archivo" value="0" />
				<input type="file" id="informe" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/msds" >Subir archivo</button>
			</div>
			
			<div data-linea="6">			
				<label>Descripción:</label> 
			</div>
	
			<div data-linea="7">			
				<textarea id="descripcion" name="descripcion"  placeholder="Ej: Descripción..." rows="5" ></textarea>
			</div>
			
		</fieldset>
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" > Guardar </button>
	
	</form>
</body>

	<input type="hidden" name="abrir" data-rutaAplicacion="seguridadOcupacional" data-opcion="abrirMaterialPeligroso" data-destino="detalleItem"/>
	

<script>

	$('document').ready(function(){
		distribuirLineas();
		construirValidador();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
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
	
	$("#nuevoMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorNumero = false;
			if(!$.trim($("#nombreProductoUno").val())){
				error = true;
				$("#nombreProductoUno").addClass("alertaCombo");
			}
			
			if(!$.trim($("#numeroCasUno").val()) ){
				error = true;
				$("#numeroCasUno").addClass("alertaCombo");
			}

			if($.trim($("#numeroUnUno").val()) ){
				if(!esCampoValido("#numeroUnUno") ){
					error = true;
					errorNumero = true;
					$("#numeroUnUno").addClass("alertaCombo");
				}
			}
			
			if(!$.trim($("#descripcion").val())){
					error = true;
					$("#descripcion").addClass("alertaCombo");
			}

			if(!$.trim($("#guia").val())){
				error = true;
				$("#guia").addClass("alertaCombo");
			}
			
			if($("#archivo").val() == 0){
				error = true;
				$("#informe").addClass("alertaCombo");
			}
			
			if (error){
					$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
				if (errorNumero){
					$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}
			}else{
				ejecutarJson("#nuevoMaterialPeligroso", new exitoMaterialPeligroso());
			}
	});

	function exitoMaterialPeligroso() {
        this.ejecutar = function (msg) {
            //mostrarMensaje("Los datos han sido ingresados satisfactoriamente", "EXITO");
           	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
        	var id=msg.idMaterialPeligroso;
			$("input[name='abrir']").attr('id',id);
    		abrir($("#"+id),null,true); 
			
        }
    }
    
</script>
</html>