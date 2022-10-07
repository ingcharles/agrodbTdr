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
		<h1>Cagar Rol de Pagos</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoGenerarRolPagos" data-rutaAplicacion="uath" data-opcion="generarNuevoRolPagos" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
			
		<fieldset>
			<legend>Seleccionar informacion</legend>	
		
			<div data-linea="1">
				<label>Área:</label> 
				<select name="area" id="area" >
					<option value="" >Área...</option>
					<?php
						$area = array('Planta Central','Zona 1','Zona 2','Zona 3','Zona 4','Zona 5','Zona 6','Zona 7');										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   
					
					?>
				</select>
			</div>	
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
			<br>
				<label>Descripción:</label> 
				<select name="descrip" id="descrip" >
					<option value="" >seleccione...</option>
					<?php				
					$descrip = array('Nombramiento','Contrato','Codigo de trabajo','Mosca de la fruta','Foc R4T','Inocuidad en cadenas agroalimentarias','Prozec','Prolab','Todos');
						for ($i=0; $i<sizeof($descrip); $i++){
							echo '<option value="'.$descrip[$i].'">'. $descrip[$i] . '</option>';
						}
					?>
				</select>
			</div>
			<div data-linea="3">
				<br>
				<label>Formato del archivo excel (.XLS):</label>
				<input type="hidden" class="rutaArchivo" name="archivo"  value="0" />
				<input type="file"  class="archivo" name="informe" accept="application/msexcel"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosRolPagos/excel" >Subir archivo</button>
			
			</div>
			
		
			
		</fieldset>
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" disabled="disabled" >Cargar Rol Pagos </button>
	
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
		if(!$.trim($("#area").val())){
			error = true;
			$("#area").addClass("alertaCombo");
		}
		if(!$.trim($("#mes").val())){
			error = true;
			$("#mes").addClass("alertaCombo");
		}
		if(!$.trim($("#ano").val())){
			error = true;
			$("#ano").addClass("alertaCombo");
		}
		if(!$.trim($("#descrip").val())){
			error = true;
			$("#descrip").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor seleccione todos los campos.").addClass('alerta');
		}else{

		var area=$("#area").val();
		var mes=$("#mes").val();
		var ano=$("#ano").val();
		var descrip=$("#descrip").val();
		
		descrip = descrip.replace(" ", "_");
		area = area.replace(" ", "_");
	       
		var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");
        //var numero = Math.floor(Math.random()*100000000);
        if (extension[extension.length - 1].toUpperCase() == 'XLS') {
    		$("#btnGuardar").removeAttr("disabled","disabled");
            subirArchivo(
                archivo
                , 'Rol_Pagos'+'_'+mes+'_'+ano+'_'+area+'_'+descrip
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato XLS');
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

			if(!$.trim($("#area").val())){
				error = true;
				$("#area").addClass("alertaCombo");
			}
			if(!$.trim($("#mes").val())){
				error = true;
				$("#mes").addClass("alertaCombo");
			}
			
			if(!$.trim($("#ano").val())){
				error = true;
				$("#ano").addClass("alertaCombo");
			}
			if(!$.trim($("#descrip").val())){
				error = true;
				$("#descrip").addClass("alertaCombo");
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