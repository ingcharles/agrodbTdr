<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Cagar Descuentos Funcionarios</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoGenerarRolPagos" data-rutaAplicacion="vacacionesPermisos" data-opcion="generarDescuentosFuncionarios" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
			
		<fieldset>
			<legend>Seleccionar informacion</legend>							
			<div data-linea="1">
				<label>Mes:</label> 
				<select name="mes" id="mes" >
					<option value="" >Mes...</option>
					<?php				
					$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
							'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
						for ($i=0; $i<sizeof($meses); $i++){
							echo '<option value="'.$meses[$i].'">'. $meses[$i] . '</option>';
						}
					?>
				</select>
			</div>
			
			<div data-linea="1">
				<label>Año:</label> 
				<select name="ano" id="ano" >
					<option value="" >Año...</option>
					<?php
					for($i=2016;$i<=2050;$i++){
					   	echo '<option  value="' . $i . '">'.$i. '</option>';
					}		    
					?>
				</select>
			</div>
			
			<div data-linea="2">
				<label>Formato del archivo excel (.XLSX):</label>
				<input type="hidden" class="rutaArchivo" name="archivo"  value="0" />
				<input type="file"  class="archivo" name="informe" accept="application/msexcel"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/vacacionesPermisos/archivosDescuentos" >Subir archivo</button>
			
			</div>
			
		
			
		</fieldset>
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" disabled="disabled" >Cargar Descuentos </button>
	
	</form>
</body>

<script>

	$('document').ready(function(){
		distribuirLineas();
	});
	
	$('button.subirArchivo').click(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if(!$.trim($("#mes").val())){
			error = true;
			$("#mes").addClass("alertaCombo");
		}
		if(!$.trim($("#ano").val())){
			error = true;
			$("#ano").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor seleccione todos los campos.").addClass('alerta');
		}else{
		
		var mes=$("#mes").val();
		var ano=$("#ano").val();
	       
		var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");
        
        if (extension[extension.length - 1].toUpperCase() == 'XLSX') {
    		$("#btnGuardar").removeAttr("disabled","disabled");
            subirArchivo(
                archivo
                , 'Descuento'+'_'+mes+'_'+ano
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato XLSX');
            archivo.val("");
       }    	
	}
        
    });
	
	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	
	$("#nuevoGenerarRolPagos").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

			if(!$.trim($("#mes").val())){
				error = true;
				$("#mes").addClass("alertaCombo");
			}
			
			if(!$.trim($("#ano").val())){
				error = true;
				$("#ano").addClass("alertaCombo");
			}
		
			if (error){
				$("#estado").html("Por favor seleccione todos los campos.").addClass('alerta');
			}else{
				$("#estado").html("");
				$("#btnGuardar").attr("disabled","disabled");

				ejecutarJson("#nuevoGenerarRolPagos");
				if( $('#estado').html()=='Los datos han sido ingresados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});
</script>
</html>
