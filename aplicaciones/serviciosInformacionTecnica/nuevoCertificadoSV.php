<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$controladorCatalogos = new ControladorCatalogos();
$controladorAdministrarCatalogos = new ControladorAdministrarCatalogos();
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Nuevo Certificado</h1>
</header>

<div id="estado"></div>


<form id="nuevoCertificado" data-rutaAplicacion="serviciosInformacionTecnica" data-accionEnExito="ACTUALIZAR">	

	<input type="hidden" id="opcion" />
	<input type="hidden" id="fechaArchivo" value="<?php echo date('Y-m-d h:m:s');?>">
	<fieldset>
		<legend>Nuevo Certificado:</legend>		
		<div data-linea="1">
			<label for="pais">País:</label>
			<select id="pais" name="pais">
				<option value=>Seleccione....</option>
            	<?php $pais = $controladorCatalogos->listarLocalizacion($conexion, 'PAIS');
            	while($fila=pg_fetch_assoc($pais)){
            	   echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['nombre'] . '</option>';
            	}
            	?>
				</select>		
			</div>
			<div data-linea="2">
				<label for="certificado" >Certificados:</label>
				<select id="certificado" name="certificado">
					<option value="">Seleccione....</option>
					<?php 
					$res=$controladorAdministrarCatalogos->listarItemsPorCodigo($conexion, 'COD-TIPOCERT-SV', 1);
					while ($fila=pg_fetch_assoc($res)){
						echo '<option value="'.$fila['id_item'].'">'.$fila['nombre'].'</option>';								
					}
					?>
				</select>
			</div>
			<div data-linea="3">
				<label for="fechaIngreso" >Fecha de Ingreso:</label>
				<input type="text" id="fechaIngreso" name="fechaIngreso" maxlength="30">
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de Certificado:</legend>
		<div style="width:100%">
		    <label>Seleccionar Archivo:</label>
			<input type="hidden" class="rutaArchivo" id="rutaCertificado" name="rutaCertificado" value="0" />
			<input type="file" class="archivo" id="documentoCertificado" accept="application/pdf"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="archivoCertificado" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosInformacionTecnica/documentos" >Subir archivo</button>
		</div>
		
		<div id="visualizarPdf">
			<iframe style='width:550px; height:750px;' >
    		</iframe>
		</div>		
	</fieldset>
	
	<fieldset>
		<legend>Firmas Autorizadas:</legend>
		<div style="width: 100%;">
			<table id="tablaItems" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
				<thead>
					<tr>
						<th style="width: 15%;">Cargo</th>
						<th style="width: 15%;">Nombre Funcionario</th>
						<th style="width: 60%;">Firma</th>
						<th style="width: 10%;">Estado</th>										
					</tr>
				</thead>
				<tbody>				
				</tbody>
			</table>
		<button type="button" style="" id="agregarFila" name="agregarFila" class="mas"></button>
		</div>		
	</fieldset>
	
	<button type="submit" id="btnGuardar" class="guardar" >Guardar</button>	
	
</form>

<script type="text/javascript">



$("document").ready(function(event){
	distribuirLineas();

	$("#visualizarPdf").hide();

	$("#fechaIngreso").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#fechaIngreso").attr("readOnly","readOnly");
		
});

$('#archivoCertificado').click(function (event) {
	$("#fechaArchivo").val(obtenerFecha());
	var usuario = <?php echo $usuario?>;
	cargarCertificado('#archivoCertificado',$("#fechaArchivo").val(),'_certificado_');
});	

$('#pais').change(function (event) {
	$("#pais").removeClass("alertaCombo");
	$("#resultadoCertificado").html("");	
});	

function cargarCertificado(button,numero,documento){
	var numero = numero;
	var usuario = <?php echo $usuario?>;

    var boton = $(button);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
    		subirArchivo(
	                archivo
	                , usuario+documento+numero
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo	                
	                , new carga(estado, archivo, $("#vacio"), "#visualizarPdf")
	            );     
    		$(archivo).removeClass("alertaCombo");    		    		
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }   
}

function cargarArchivos(button,numero,documento){
	var numero = numero;
	var usuario = <?php echo $usuario?>;

    var boton = $(button);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
    		subirArchivo(
	                archivo
	                , usuario+documento+numero
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo	                
	                , new carga(estado, archivo, $("#vacio"))
	            );     
    		$(archivo).removeClass("alertaCombo");    		    		
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }   
}

function obtenerFecha(){
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2);
	var mm=("00" + (fecha.getMonth()+1)).slice (-2);
	var yy=fecha.getFullYear();
	var hh=fecha.getHours();
	var mi=fecha.getMinutes();
	var ss=fecha.getSeconds();
	var fechaFinal=yy+"-"+mm+"-"+dd+"_"+hh+"-"+mi+"-"+ss;
	return fechaFinal;
}

function prepararImagen(elemento){
	$("#fechaArchivo").val(obtenerFecha());
	var usuario = <?php echo $usuario?>;
	cargarArchivos($(elemento),$("#fechaArchivo").val(),'_certificado_');
}

var contador=0;
$("#agregarFila").on("click",function(event){
	contador=contador+1;
	$("#tablaItems tbody").append('<tr><td><input type="text" name="cargo[]" id="cargo[]"></td><td><input type="text" name="funcionario[]"></td><td>'+
            '<div style="width:100%">'+
		    '<label>* Seleccionar Archivo de Certificado:</label>'+
			'<input type="hidden" class="rutaArchivo" name="rutaFirma[]" value="0" />'+
			'<input type="file"  class="archivo" accept="application/pdf">'+
			'<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>'+
			'<button type="button" id="subirArchivoTabla_'+contador+'" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosInformacionTecnica/documentos" onclick="prepararImagen(this)" >Subir archivo</button>'+
		'</div>'
			+'</td><td><input type="checkbox" name="estadoFirma[]" checked>Activo</td></tr>');
});

$("#nuevoCertificado").submit(function(event){	
	event.preventDefault();
	
    var error = false;
    $(".alertaCombo").removeClass("alertaCombo");
    
    var cargo='';
    var funcionario='';
    var rutaFirma='';
    var estadoFirma='';
    
    if ($.trim($("#pais").val()) == "" ) {
        error = true;
        $("#pais").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		     
    }

    if ($.trim($("#certificado").val()) == "" ) {
        error = true;
        $("#certificado").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		     
    }

    if ($.trim($("#fechaIngreso").val()) == "" ) {
        error = true;
        $("#fechaIngreso").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		     
    }

    if ($.trim($("#rutaCertificado").val()) == "0" ) {
        error = true;
        $("#documentoCertificado").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		     
    }

    filas=$('#tablaItems tbody tr').length;
    
	$("#tablaItems tbody tr").each(function (rows) {
		cargo = $(this).find("td").eq(0).find('input[name="cargo[]"]').val();    	
		funcionario = $(this).find("td").eq(1).find('input[name="funcionario[]"]').val();
		rutaFirma = $(this).find("td").eq(2).find('input[name="rutaFirma[]"]').val();
		
		if ($.trim(cargo) == "" ) {
			error = true;
			$(this).find("td").eq(0).find('input:text').addClass("alertaCombo");
			$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		
		}	

		if ($.trim(funcionario) == "" ) {
			error = true;
			$(this).find("td").eq(1).find('input:text').addClass("alertaCombo");
			$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');		   
		}	
		
		//Para rutaFirma igualar con 0
		
	});

	if(!error){
		$("#nuevoCertificado").attr('data-destino', 'detalleItem');    	
		$("#nuevoCertificado").attr('data-opcion', 'guardarNuevoCertificadoSV'); 			
		ejecutarJson($(this),new exitoGuardar(), new errorGuardar());
	}
    
    
});


function exitoGuardar(){
	this.ejecutar = function(msg){
		mostrarMensaje(msg.mensaje,"EXITO");		
	};
}

function errorGuardar(){
	this.ejecutar = function(msg){
		mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
		$("#pais").addClass("alertaCombo");
		mostrarMensaje(msg.mensaje,"FALLO");
		//$("#resultadoCertificado").html(msg.mensaje).addClass("alerta");
	};
}
</script>
