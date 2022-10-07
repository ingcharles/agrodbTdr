<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAuditoria.php';
	
	$idRequisitoComercio = $_POST['idRequisitoComercio'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$ca = new ControladorAuditoria();
	
	$requisitosComercio = pg_fetch_assoc($cr->abrirRequisitosComercio($conexion, $idRequisitoComercio));

	$qRequisitos = $cr->listarRequisitosArea($conexion, $requisitosComercio['tipo']);
	
	while($fila = pg_fetch_assoc($qRequisitos)){
		$requisitos[]= array(idRequisito=>$fila['id_requisito'], nombre=>$fila['nombre'], idTipo=>$fila['tipo'], codigo =>$fila['codigo']);
	}
	/*echo '<pre>';
	print_r($requisitos);
	echo '</pre>';*/
	
	$requisitosAsignados = $cr->listarRequisitosAsignados($conexion, $idRequisitoComercio, "'Importación', 'Exportación', 'Tránsito'");
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de requisitos comercialización</h1>
	</header>
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="regresar" data-rutaAplicacion="administracionRequisitos" data-opcion="abrirProductoPais" data-destino="detalleItem">
					<input type="hidden" name="id" value="<?php echo $requisitosComercio['id_producto'];?>"/>
					<button class="regresar">Volver a Países</button>
				</form>
	
				<form id="actualizarRegistroComercializacion" data-rutaAplicacion="administracionRequisitos" data-opcion="modificarProductoPais" >
					<input type="hidden" id="idRequisitoComercio" name="idRequisitoComercio" value="<?php echo $requisitosComercio['id_requisito_comercio'];?>">
					
					
					<fieldset>
						<legend>Información general</legend>	
						<div data-linea="1">
							<label for="pais">País</label>
							<input name="nombrePais" id="nombrePais" type="text" readonly="readonly" value="<?php echo $requisitosComercio['nombre_pais'];?>"/>
						</div>
						
						<div data-linea="1">	
							<label>Fecha</label> 
								<input type="text" id="fecha" name="fecha" placeholder="Ej: 01/01/2014" value="<?php echo $requisitosComercio['fecha'];?>" />
						</div>
						
						<div data-linea="2">	
							<label>Declaración</label> 
								<input type="text" id="declaracion" name="declaracion" placeholder="Ej: Declaración" value="<?php echo $requisitosComercio['declaracion'];?>" />
						</div>
						
						<div data-linea="2">		
							<label>Num resolución</label> 
								<input type="text" id="numeroResolucion" name="numeroResolucion" placeholder="Ej: 001" value="<?php echo $requisitosComercio['numero_resolucion'];?>" />
						</div>
						
						<div data-linea="3">		
							<label>Observación</label> 
								<input type="text" name="observacion" value="<?php echo $requisitosComercio['observacion'];?>"/>	
						</div>
						
						<div data-linea="4">
							<label>Documento</label>
								<?php echo ($requisitosComercio['ruta_archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$requisitosComercio['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
								<!-- input type="file" name="archivoRequisito" id="archivoRequisito" accept="application/pdf"/-->
								<input type="file" class="archivo" name="informe" accept="application/pdf"/>
								<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $requisitosComercio['ruta_archivo'];?>"/>
								<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
								<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionRequisitos/requisitosComercio" >Subir archivo</button>
								<button type="submit" class="guardar">Actualizar</button>
						</div>
							
						
									
						

					</fieldset>
				</form>	
				
				<form id="nuevoRequisito" data-rutaAplicacion="administracionRequisitos" data-opcion="asignarRequisito" >
					<input type="hidden" id="idRequisitoComercio" name="idRequisitoComercio" value="<?php echo $requisitosComercio['id_requisito_comercio'];?>">
							
					<fieldset>
						<legend>Requisitos</legend>	
						<div data-linea="1">
							<label for="tipoRequisito">Tipo</label>
							<select id="tipoRequisito" name="tipoRequisito" required>
								<option value="">Seleccione....</option> 
								<option value="Importación">Importación</option>
								<option value="Exportación">Exportación</option>
								<option value="Tránsito">Tránsito</option>
							</select>
						</div>
						
						<div data-linea="2">
							<label for="requisito">Requisitos</label>
							<select id="requisito" name="requisito" required>
								<option value="">Seleccione....</option>
							</select>
							
							<input type="hidden" id="nombreRequisito" name="nombreRequisito" />
						</div>
						
						<div>
							<button type="submit" class="mas">Añadir requisito</button>
						</div>
					</fieldset>
				</form>
				
				<fieldset>
					<legend>Requisitos asignados</legend>
					<table id="requisitos">
						<?php
							while ($requisito = pg_fetch_assoc($requisitosAsignados)){
								echo $cr->imprimirLineaRequisito($requisito['id_requisito_comercio'], $requisito['requisito'], $requisito['nombre'], $requisito['tipo'], $requisito['estado']);
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	
	<fieldset>
		<legend>Historial de Cambios</legend>
			
			<button type="button" id='mostrarHistorial'>Mostrar/Ocultar</button>   
				<table id="historial">
			   		<thead>
			   			<tr>
			   				<th colspan =2>Primera modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     		$qHistorial = $ca->listaHistorial($conexion, $requisitosComercio['id_requisito_comercio'], $_SESSION['idAplicacion'], 'ASC', 1);
						     	
				      			while($historial = pg_fetch_assoc($qHistorial)){
							        echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
			  	</table>
			  	
			  	<table id="historial1">
			  	<thead>
			   			<tr>
			   				<th colspan =2>Última modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     		$qHistorial = '';
					     		$historial = '';
					     		
					     		$qHistorial = $ca->listaHistorial($conexion, $requisitosComercio['id_requisito_comercio'], $_SESSION['idAplicacion'], 'DESC', 1);
							    while($historial = pg_fetch_assoc($qHistorial)){
							    	echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
			  	</table>
 	</fieldset>	
</body>

<script type="text/javascript">
var array_requisito= <?php echo json_encode($requisitos); ?>;

	$('document').ready(function(){

		$("#fecha").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });

		
		actualizarBotonesOrdenamiento();
		acciones("#nuevoRequisito", "#requisitos");
		distribuirLineas();
		
	});

	$("#tipoRequisito").change(function(){
		srequisito ='0';
		srequisito = '<option value="">Seleccione....</option>';
		
		for(var i=0; i<array_requisito.length; i++){
		    if ($("#tipoRequisito option:selected").val() == array_requisito[i]['idTipo']){
		    	srequisito += '<option value="'+array_requisito[i]['idRequisito']+'">'+(array_requisito[i]['codigo']==null?'':array_requisito[i]['codigo']+' - ')+array_requisito[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#requisito').html(srequisito);
	});

	$("#requisito").change(function(){
		$('#nombreRequisito').val($('#requisito option:selected').text());
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
        	if($("#idRequisitoComercio").val() !="" && $("#fecha").val() !=""){

       		 subirArchivo(
        	                archivo
        	                , $("#idRequisitoComercio").val()+'_'+$("#fecha").val().replace(/[_\W]+/g, "-")
        	                , boton.attr("data-rutaCarga")
        	                , rutaArchivo
        	                , new carga(estado, archivo, boton)
        	            );
            }else {
				estado.html('Ingrese la fecha de resolución del requisito de comercio.');
				archivo.val("");
			}
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });


	$("#actualizarRegistroComercializacion").submit(function(event){

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

	$("#mostrarHistorial").click(function(){
		 $("#historial").slideToggle();
		 $("#historial1").slideToggle();			 
	});
</script>
</html>