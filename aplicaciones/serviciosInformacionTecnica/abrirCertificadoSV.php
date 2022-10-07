<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$controladorInformacion= new ControladorServiciosInformacionTecnica();
$controladorAdministrarCatalogos = new ControladorAdministrarCatalogos();
$idCertificado= $_POST['id'];
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Editar Certificado</h1>

</header>

<div id="estado"></div>


<form id="editarCertificado" data-rutaAplicacion="serviciosInformacionTecnica">
	<input type="hidden" id="opcion" value="" name="opcion">	
	<input type="hidden" id="usuario" name="usuario" value=<?php echo $usuario?>> 
	<input type="hidden" id="fechaArchivo">
	<input type='hidden' name=idCertificado value="<?php echo $idCertificado;?>">
	<?php $certificado=pg_fetch_assoc($controladorInformacion->obtenerCertificadoXId($conexion, $idCertificado))?>
	<fieldset>
		<legend>Datos Generales:</legend>
		<div data-linea="1">
			<label for="pais">País:</label>				
			<input type="text" id="pais" name="pais" value="<?php echo $certificado['nombre'] ?>" disabled="disabled">	
		</div>
		<div data-linea="2">
			<label for="certificado">Certificado</label>
			<select id="certificado" name="certificado" disabled="disabled">
				<option value="">Seleccione....</option>
				<?php 
				$resultado=$controladorAdministrarCatalogos->listarItemsPorCodigo($conexion, 'COD-TIPOCERT-SV', 1);
				while ($fila=pg_fetch_assoc($resultado)){
					echo '<option value="'.$fila['id_item'].'">'.$fila['nombre'].'</option>';								
				}
				?>
			</select>
		</div>
		<div data-linea="3">
			<label for="fechaIngreso">Fecha de Ingreso:</label>				
			<input type="text" id="fechaIngreso" name="fechaIngreso" value="<?php echo date('Y-m-d',strtotime($certificado['fecha_ingreso'])) ?>" disabled="disabled">	
		</div>				
	</fieldset>
	
	<fieldset>
		<legend>Datos Certificados:</legend>
		<div style="width:100%">
			<button onclick="editarCertificado();return false;" >Modificar</button>
		</div>
		<div style="width:100%" id="cargarCertificado">
		    <label>Seleccionar Archivo:</label>
			<input type="hidden" class="rutaArchivo" id="rutaCertificado" name="rutaCertificado" value="0" />
			<input type="file" class="archivo" id="documentoCertificado" accept="application/pdf"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="archivoCertificado" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosInformacionTecnica/documentos" >Subir archivo</button>
		</div>
		<div id="visualizarPdf">
			<embed id="visor" width="550" height="620" src="<?php echo $certificado['ruta_archivo'];?>" ></embed>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Firmas Autorizadas:</legend>
		<?php $resultado=$controladorInformacion->listarFirmas($conexion, $idCertificado)?>
		<div data-liniea="1" id="contenedorTabla" style="width:100%;">
    		<table id="tablaItems" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
    			<thead>
    				<tr>
    					<th style="width: 15%;">Cargo</th>
    					<th style="width: 15%;">Nombre Funcionario</th>
    					<th style="width: 50%;">Firma</th>
    					<th style="width: 10%;">Estado</th>										
    					<th style="width: 10%;">Modificar</th>
    				</tr>
    			</thead>
    			<tbody>
    			<?php
    			while($fila=pg_fetch_assoc($resultado)){
    			    echo '<tr>
                        <td>'.$fila['cargo'].'<input type="hidden" name="idFirma[]" value="'.$fila['id_firma'].'"></td>
                        <td>'.$fila['nombre_funcionario'].'</td>
						<td>';
					if($fila['ruta_archivo']!='0'){
                        echo' <embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150"> <input type="hidden" name="rutaBaseCertificado[]" value="'.$fila['ruta_archivo'].'">';
					}                        
                    echo'</td>
						<td>'.$fila['estado'].'</td>
    			        <td class="abrir"><button class="icono" onclick="modificarFirma(this);return false"></button></td></tr>';
    			}
    			?>
    			</tbody>
    		</table>
			<button type="button" style="" id="agregarFila" name="agregarFila" class="mas"></button>
		</div>
	</fieldset>
	
	<button type="submit" id="guardar" class="guardar">Guardar</button>	
	
	<fieldset>
		<legend>Histórico de Cambios:</legend>
		<?php $resultado=$controladorInformacion->listarFirmasHistorial($conexion, $idCertificado)?>
		<div data-liniea="1" id="contenedorHistorial" style="width:100%;">
    		<table id="tablaHistorial" style="width:100%; border: 1px solid #b0b0b0; text-align:center;">
    			<thead>
    				<tr>
    					<th style="width: 15%;">Cargo</th>
    					<th style="width: 15%;">Nombre Funcionario</th>
    					<th style="width: 45%;">Firma</th>
    					<th style="width: 10%;">Estado</th>										
    					<th style="width: 15%;">Fecha y Hora Modificación</th>
    				</tr>
    			</thead>
    			<tbody>
    			<?php
    			while($fila=pg_fetch_assoc($resultado)){
    			    echo '<tr>
                        <td>'.$fila['cargo'].'</td>
                        <td>'.$fila['nombre_funcionario'].'</td>
						<td>';
					if($fila['ruta_archivo']!='0'){
                        echo' <embed id="visor" src="'. $fila['ruta_archivo'] .'" width="200" height="150">';
					}
                    echo'</td>
						<td>'.$fila['estado'].'</td>
    			        <td>'.$fila['fecha'].'</td></tr>';
    			}
    			?>
    			</tbody>
    		</table>
		</div>
	</fieldset>
	
</form>

<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
	cargarValorDefecto("certificado","<?php echo $certificado['id_item'];?>");
	$("#guardar").attr("disabled",true);
	$("#cargarCertificado").hide();

	$('#archivoCertificado').click(function (event) {	
		$("#fechaArchivo").val(obtenerFecha());		
		cargarCertificado($(this),$("#fechaArchivo").val(),'_certificado_');		
	});	
});

var contador=0;

function editarCertificado(){
	$("#cargarCertificado").show();
	$("#guardar").attr("disabled",false);
}

$("#agregarFila").on("click",function(event){
	contador=contador+1;
	$("#tablaItems tbody").append('<tr><td><input type="text" name="nuevoCargo[]" id="nuevoCargo[]" style="width:100%;"></td><td><input type="text" name="nuevoFuncionario[]" style="width:100%;"></td><td>'+
            '<div style="width:100%">'+
		    '<label>Seleccionar Archivo:</label>'+
			'<input type="hidden" class="rutaArchivo" name="nuevoRutaFirma[]" value="0" />'+
			'<input type="file"  class="archivo" accept="application/pdf">'+
			'<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>'+
			'<button type="button" id="subirArchivoTabla_'+contador+'" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosInformacionTecnica/documentos" onclick="prepararImagen(this)" >Subir archivo</button>'+
		'</div>'
			+'</td><td><input type="checkbox" name="nuevoEstadoFirma[]" checked>Activo</td></tr>');
	$("#guardar").attr("disabled",false);
});

function modificarFirma(boton){	
	var fila = $(boton).closest('tr');
	var cargo = fila.find("td").eq(0).text();
	var funcionario = fila.find("td").eq(1).text();
	var rutaBase = fila.find("td").eq(2).find('input[name="rutaBaseCertificado[]"]').val();
	var estado = fila.find("td").eq(3).text();
	var idFirma= fila.find("td").eq(0).find('input[name="idFirma[]"]').val();	

	contador=contador+1;	
	
	fila.find("td").eq(0).html("<input type='text' name='cargo[]' value='"+cargo+"' style='width:100%;'><input type='hidden' name='firma[]' value='"+idFirma+"'>" );
	fila.find("td").eq(1).html("<input type='text' name='funcionario[]' value='"+funcionario+"' style='width:100%;'>" );
	fila.find("td").eq(2).html('<div style="width:100%">'+
		    '<label>Seleccionar Archivo:</label>'+
			'<input type="hidden" class="rutaArchivo" name="rutaFirma[]" value="'+rutaBase+'" />'+
			'<input type="file"  class="archivo" accept="application/pdf">'+
			'<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>'+
			'<button type="button" id="subirArchivoTabla_'+contador+'" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosInformacionTecnica/documentos" onclick="prepararImagen(this)" >Subir archivo</button>'+
	'</div>');

	if(estado=="Activa"){
		fila.find("td").eq(3).html("</td><td><input type='checkbox' name='estadoFirma[]' checked>Activo</td></tr>'");
	} else{
		fila.find("td").eq(3).html("</td><td><input type='checkbox' name='estadoFirma[]' >Activo</td></tr>'");
	}

	$(boton).attr("disabled",true);
	$("#guardar").attr("disabled",false);
	
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

function cargarCertificado(button,numero,documento){
	var numero = numero;
	var usuario = <?php echo $usuario?>;

    var boton = $(button);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        $("#visualizarPdf").html("<iframe style='width:550px; height:750px;' ></iframe>");
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

$("#editarCertificado").submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	$("#tablaItems tbody tr").each(function (rows) {
        cargo = $(this).find("td").eq(0).find('input[name="cargo[]"]').val();    	
    	funcionario = $(this).find("td").eq(1).find('input[name="funcionario[]"]').val();
    	rutaFirma = $(this).find("td").eq(2).find('input[name="rutaFirma[]"]').val();
    	nuevoCargo = $(this).find("td").eq(0).find('input[name="nuevoCargo[]"]').val();    	
    	nuevoFuncionario = $(this).find("td").eq(1).find('input[name="nuevoFuncionario[]"]').val();
    	nuevoRutaFirma = $(this).find("td").eq(2).find('input[name="nuevoRutaFirma[]"]').val();
    	

    	if($(this).find("td").eq(0).find('input[name="cargo[]"]').length>0){
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
    	}

    	if($(this).find("td").eq(0).find('input[name="nuevoCargo[]"]').length>0){
    		if ($.trim(nuevoCargo) == "" ) {
    	        error = true;
    	        $(this).find("td").eq(0).find('input:text').addClass("alertaCombo");
    	        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');	    	        
    	    }	
    
    		if ($.trim(nuevoFuncionario) == "" ) {
    	        error = true;
    	        $(this).find("td").eq(1).find('input:text').addClass("alertaCombo");
    	        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');    	        
    	    }	
			
			//Para nuevoRutaFirma igualar con 0    
    		
    	}    	
		
	});

	if(!error){
    	$("#editarCertificado").attr('data-destino', 'detalleItem');    	
        $("#editarCertificado").attr('data-opcion', 'actualizarDetalleCertificadoSV'); 
        ejecutarJson($(this));

        $('#editarCertificado').attr('data-opcion','comboEditarCertificado');
    	$('#editarCertificado').attr('data-destino','contenedorTabla');
    	$('#opcion').val('firmas');
    	abrir($("#editarCertificado"),event,false);

    	$('#editarCertificado').attr('data-opcion','comboEditarCertificado');
    	$('#editarCertificado').attr('data-destino','contenedorHistorial');
    	$('#opcion').val('historial');
    	abrir($("#editarCertificado"),event,false);

    	$("#cargarCertificado").hide();
    	$("#rutaCertificado").val("0");
    	$("#guardar").attr("disabled",true);
	}
});
	
</script>
