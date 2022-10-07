<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProtocolos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idProtocoloComercio = $_POST['idProtocoloComercio'];
	
	$conexion = new Conexion();
	$cp = new ControladorProtocolos();	
	$cc = new ControladorCatalogos();
	
	$protocolosComercio = pg_fetch_assoc($cp->abrirProtocolosComercio($conexion, $idProtocoloComercio));

	$protocolosAsignados = $cp->listarprotocolosAsignados($conexion, $idProtocoloComercio);
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de protocolos comercialización</h1>
	</header>
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="regresar" data-rutaAplicacion="administracionRequisitos" data-opcion="abrirProductoPaisProtocolo" data-destino="detalleItem">
					<input type="hidden" name="id" value="<?php echo $protocolosComercio['id_producto'];?>"/>
					<button class="regresar">Volver a Países</button>
				</form>
	
				<form id="actualizarProtocoloComercializacion" data-rutaAplicacion="administracionRequisitos" data-opcion="modificarProtocoloPais" >
					<input type="hidden" id="idProtocoloComercio" name="idProtocoloComercio" value="<?php echo $protocolosComercio['id_protocolo_comercio'];?>">
					
					
					<fieldset>
						<legend>Información general</legend>	
						<div data-linea="1">
							<label for="pais">País</label>
							<input name="nombrePais" id="nombrePais" type="text" readonly="readonly" value="<?php echo $protocolosComercio['nombre_pais'];?>"/>
						</div>
						
						<div data-linea="1">	
							<label>Fecha</label> 
								<input type="text" id="fecha" name="fecha" placeholder="Ej: 01/01/2014" value="<?php echo $protocolosComercio['fecha'];?>" />
						</div>
						
						<div data-linea="2">	
							<label>Declaración</label> 
								<input type="text" id="declaracion" name="declaracion" placeholder="Ej: Declaración" value="<?php echo $protocolosComercio['declaracion'];?>" />
						</div>
						
						<div data-linea="2">		
							<label>Num resolución</label> 
								<input type="text" id="numeroResolucion" name="numeroResolucion" placeholder="Ej: 001" value="<?php echo $protocolosComercio['numero_resolucion'];?>" />
						</div>
						
						<div data-linea="3">		
							<label>Observación</label> 
								<input type="text" name="observacion" value="<?php echo $protocolosComercio['observacion'];?>"/>	
						</div>
						
						<div data-linea="4">
							<label>Documento</label>
								<?php echo ($protocolosComercio['ruta_archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$protocolosComercio['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
								<!-- input type="file" name="archivoRequisito" id="archivoRequisito" accept="application/pdf"/-->
								<input type="file" class="archivo" name="informe" accept="application/pdf"/>
								<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $protocolosComercio['ruta_archivo'];?>"/>
								<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
								<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionRequisitos/requisitosComercio" >Subir archivo</button>
								<button type="submit" class="guardar">Actualizar</button>
						</div>
							
						
									
						

					</fieldset>
				</form>	
				
				<form id="nuevoProtocolo" data-rutaAplicacion="administracionRequisitos" data-opcion="asignarProtocolo" >
					<input type="hidden" id="idProtocoloComercio" name="idProtocoloComercio" value="<?php echo $protocolosComercio['id_protocolo_comercio'];?>">
							
					<fieldset>
						<legend>Protocolos</legend>	
						
						<div data-linea="2">
							<label for="protocolo">Protocolos</label>
								<select id="protocolo" name="protocolo">
									<option value="0">Seleccione...</option>
										<?php 
											$protocolos= $cp-> listarProtocolos($conexion);
											
											while ($fila = pg_fetch_assoc($protocolos)){
									    		echo '<option value="'.$fila['id_protocolo']. '">'. $fila['nombre_protocolo'] .'</option>';
									    	}
										?>		
								</select>
							<input type="hidden" id="nombreProtocolo" name="nombreProtocolo" />
						</div>
						
						<div>
							<button type="submit" class="mas">Añadir protocolo</button>
						</div>
					</fieldset>
				</form>
				
				<fieldset>
					<legend>Protocolos asignados</legend>
					<table id="protocolos">
						<?php
							while ($protocolo = pg_fetch_assoc($protocolosAsignados)){
							
								echo $cp->imprimirLineaProtocolo($protocolo['id_protocolo_comercio'], $protocolo['id_protocolo'], $protocolo['nombre_protocolo'], $protocolo['estado']);
								
							}
					
							?>	
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</body>

<script type="text/javascript">
//var array_protocolo= <?php echo json_encode($protocolos); ?>;

	$('document').ready(function(){

		$("#fecha").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });

		
		actualizarBotonesOrdenamiento();
		acciones("#nuevoProtocolo", "#protocolos");
		distribuirLineas();
		
	});

	/*$("#tipoProtocolo").change(function(){
		sprotocolo ='0';
		sprotocolo = '<option value="">Seleccione....</option>';
		
		for(var i=0; i<array_requisito.length; i++){
		    if ($("#tipoRequisito option:selected").val() == array_requisito[i]['idTipo']){
		    	srequisito += '<option value="'+array_requisito[i]['idRequisito']+'">'+(array_requisito[i]['codigo']==null?'':array_requisito[i]['codigo']+' - ')+array_requisito[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#requisito').html(srequisito);
	});*/

	$("#protocolo").change(function(){
		$('#nombreProtocolo').val($('#protocolo option:selected').text());
		distribuirLineas();
	});

	/*$('#archivoRequisito').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#archivoRequisito").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			if($("#idRequisitoComercio").val() !="" && $("#fecha").val() !=""){
		  		subirArchivo('archivoRequisito',$("#idRequisitoComercio").val()+'_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/administracionRequisitos/requisitosComercio', 'archivo');
			}else {
				$("#estado").html('Ingrese la fecha de resolución del requisito de comercio.').addClass("alerta");
				$('#archivoRequisito').val('');
			}
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#archivoRequisito').val('');
		}
		
	});*/

	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        	if($("#idProtocoloComercio").val() !="" && $("#fecha").val() !=""){

       		 subirArchivo(
        	                archivo
        	                , $("#idProtocoloComercio").val()+'_'+$("#fecha").val().replace(/[_\W]+/g, "-")
        	                , boton.attr("data-rutaCarga")
        	                , rutaArchivo
        	                , new carga(estado, archivo, boton)
        	            );
            }else {
				estado.html('Ingrese la fecha de resolución del protocolo de comercio.');
				archivo.val("");
			}
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });


	$("#actualizarProtocoloComercializacion").submit(function(event){

		event.preventDefault();

		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#fecha").val()==""){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if($.trim($("#declaracion").val())=="" ){
			error = true;
			$("#declaracion").addClass("alertaCombo");
		}

		if($.trim($("#numeroResolucion").val())=="" ){
			error = true;
			$("#numeroResolucion").addClass("alertaCombo");
		}
		
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
	});
</script>
</html>