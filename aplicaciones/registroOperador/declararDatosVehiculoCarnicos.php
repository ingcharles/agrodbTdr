<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

	
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	$cac = new ControladorAdministrarCatalogos();
	
	$datos=explode('@', $_POST['id']);
	$idSitio = $datos[0];
	$idOperacion = $datos[1];
	
	$qUnidadMedida = $cc->listarUnidadesMedida($conexion);
	
	
	$qSitio = $cro -> abrirSitio($conexion, $idSitio);
	$sitio = pg_fetch_result($qSitio, 0, 'nombre_lugar');
	
	$idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
	$nombreArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'nombre_area');
	//data-accionEnExito="ACTUALIZAR"
	$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
    $operacion = pg_fetch_assoc($qOperacion);

	//obtener datos vehivulo cargar vista
	$qDatosVehiculo = $cro->obtenerDatosVehiculoXIdOperadorTipoOperacionPorEstado($conexion, $operacion['id_operador_tipo_operacion'], 'activo');
    $datosVehiculo = pg_fetch_assoc($qDatosVehiculo);


    $registroContenedorVehiculo = $datosVehiculo['registro_contenedor_vehiculo'];
    $placa = $datosVehiculo['placa_vehiculo'];
	$marcaVehiculo = $datosVehiculo['nombre_marca_vehiculo'];
	$modeloVehiculo = $datosVehiculo['nombre_modelo_vehiculo'];
	$claseVehiculo = $datosVehiculo['nombre_clase_vehiculo'];
	$colorVehiculo = $datosVehiculo['nombre_color_vehiculo'];
	$tipoVehiculo = $datosVehiculo['nombre_tipo_vehiculo'];
	$anioVehiculo = $datosVehiculo['anio_vehiculo'];
	$servicioVehiculo = $datosVehiculo['servicio'];
	$unidadMedidaVehiculo = $datosVehiculo['codigo_unidad_medida'];
	$capacidadInstaladaVehiculo = $datosVehiculo['capacidad_vehiculo'];
	$tipoContendor = $datosVehiculo['tipo_contenedor'];
	$caracteristicaContendor = $datosVehiculo['caracteristica_contenedor'];

?>

<header>
	<h1>Declarar Datos del Vehículo</h1>
</header>

<div id="estado"></div>

<form id='declararDatosVehiculo' data-rutaAplicacion='registroOperador' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" class="idArea" name="idArea" value="<?php echo $idArea;?>" />
	<input type="hidden" class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
	<input type="hidden" name="carnicos" id="carnicos" value="ok"/>
	<input type="hidden" name="opcion" id="opcion" />
	<fieldset>
		<legend>Datos del medio de transporte</legend>		
		<div data-linea="1">			
			<label>Sitio: </label><?php echo $sitio; ?>
		</div>
		<div data-linea="1">			
			<label>Área: </label><?php echo $nombreArea; ?>
		</div>
		<hr/>
		<div data-linea="2">
		<label >Registro de contenedor incluido la placa del vehículo:</label>
             <select name="registroContenedorVehiculo" id="registroContenedorVehiculo">
	            <option value="">Seleccione...</option>
	            <option value="SI">SI</option>
	            <option value="NO">NO</option>
	           
            </select>
		</div>	
		<div data-linea="3">			
			<label id="lbplaca">*Placa: </label><input type="text" id="placa" name="placa" placeholder="Ej: AAA-0000" data-er="[A-Za-z]{3}-[0-9]{3,4}" data-inputmask="'mask': 'aaa-9999'"  value="<?php echo $placa; ?>"/>
		</div>
		<div data-linea="3">				
			<label id="lbanio">Año: </label><input	type="text" id="anio" name="anio" readonly  value="<?php echo $anioVehiculo; ?>"/>
		</div>	
		<div data-linea="4">				
			<label id="lbmarca">Marca: </label><input	type="text" id="marca" name="marca" readonly  value="<?php echo $marcaVehiculo; ?>"/>
		</div>
		<div data-linea="4">				
			<label id="lbmodelo">Modelo: </label><input	type="text" id="modelo" name="modelo" readonly  value="<?php echo $modeloVehiculo; ?>"/>
		</div>
		<div data-linea="5">				
			<label id="lbcolor">Color: </label><input	type="text" id="color" name="color" readonly  value="<?php echo $colorVehiculo; ?>"/>
		</div>
		<div data-linea="5">				
			<label id="lbtipo">Tipo: </label><input	type="text" id="tipo" name="tipo" readonly  value="<?php echo $tipoVehiculo; ?>"/>
		</div>
		<div data-linea="6">				
			<label id="lbclase">Clase: </label><input	type="text" id="clase" name="clase" readonly value="<?php echo $claseVehiculo; ?>" />
		</div>
		
		<div data-linea="6">
			<label id="lbservicio" for="servicio">*Servicio: </label>			
            <select id="servicio" name="servicio">
            <option value="">Seleccione...</option>
				<option value="USO PUBLICO">USO PUBLICO</option>
                <option value="TRANSP. PUBLICO">TRANSP. PUBLICO</option>
                <option value="USO EN GADS Y MANCOMUNIDADES">USO EN GADS Y MANCOMUNIDADES</option>
                <option value="USO ESTATAL">USO ESTATAL</option>
                <option value="TRANSPORTE PUBLICO">TRANSPORTE PUBLICO</option>
				<option value="USO PARTICULAR">USO PARTICULAR</option>
                <option value="PARTICULAR">PARTICULAR</option>
            </select>
		</div>
		
		<div data-linea="7">				
			<label>*Capacidad instalada: </label><input	type="text" id="capacidadInstalada" name="capacidadInstalada" value="<?php echo $capacidadInstaladaVehiculo; ?>" />
		</div>
		<div data-linea="7">
			<label for="unidadMedida">*Unidad: </label>			
            <select id="unidadMedida" name="unidadMedida">
            <option value="">Seleccione...</option>
                <?php
                    while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                        echo '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                    }
                ?>
            </select>
		</div>	
		<div data-linea="8">
			<label>*Tipo de contenedor:</label> <input type="text" id="tipoContenedor" name="tipoContenedor" value="<?php echo $tipoContendor; ?>"/>
		</div>	
		<div data-linea="9">
			<label>*Características del contenedor:</label> <input type="text" id="caracteristicaContenedor" name="caracteristicaContenedor" value="<?php echo $caracteristicaContendor; ?>"/>
		</div>
	</fieldset>
	<fieldset>
		<legend>Centros de faenamiento donde se brindará el servicio</legend>		
		
		<div data-linea="2">			
			<label for="rucCF">RUC Centro Faenamiento: </label>
            <input type="text" id="rucCF" name="rucCF" data-er="[0-9]{13}" maxlength="13"/>
		</div>
		<!-- <div data-linea="3">			
			<label for="razonSocial">Razón Social Centro Faenamiento: </label>
            <input type="text" id="razonSocial" name="razonSocial" />
		</div> -->
		<div data-linea="4">
			<label for="provinciaCF">Provincia: </label>
            <select id="provinciaCF" name="provinciaCF">
            <option value="">Seleccione...</option>
                <?php 	
				$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
				foreach ($provincias as $provincia){
					echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
				}
                ?>
            </select>
		</div>
		<div data-linea="5">		
			<button type="button" id="buscarCentroFaenamiento">Buscar</button>
		</div>
		<hr>
		<div data-linea="6">
			<label for="sitioCF">Sitio: </label>
            <select id="sitioCF" name="sitioCF" disabled>
            <option value="">Seleccione...</option>
            </select>
		</div>
		<div data-linea="7">
			<label for="areaCF">Área: </label>
            <select id="areaCF" name="areaCF" disabled>
            <option value="">Seleccione...</option>
            </select>
		</div>
		        <div data-linea="9">		
					<button id="agregarCf" type="button" class="mas">Agregar</button>
				</div>
	</fieldset>
	<fieldset id="cfIngresados">
		<legend>Centros de faenamiento agregados</legend>		
			<?php 
			$arrayParametros = array('id_operacion' => $idOperacion,'id_area' =>$idArea);
			$consulta = $cro->consultarCentroFaenamienTransporte($conexion,$arrayParametros);
			if(pg_num_rows($consulta) > 0){
			echo '<table id="listadoCF" value="si" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>RUC</th>
									<th>Razón social</th>
									<th>Provincia</th>
									<th>Sitio</th>
								    <th>Área</th>
                                    <th></th>
								<tr>
							</thead>
							<tbody id="areas">';
			$contadorProducto = 0;
			while ($fila = pg_fetch_assoc($consulta)) {
			    $arrayParametros = array('id_centro_faenamiento' => $fila['id_centro_faenamiento']);
			    $dato = pg_fetch_assoc($cro->buscarCentroFaenamientoXid($conexion,$arrayParametros));
			    echo '<tr><td>'.++$contadorProducto.'</td>
                          <td>'.$dato['identificador_operador'].'</td>
                          <td>'.$dato['razon_social'].'</td>
                          <td>'.$dato['provincia'].'</td>
                          <td>'.$dato['nombre_lugar'].'</td>
						  <td>'.$dato['nombre_area'].'</td>
                          <td><button type="button" class="menos" onclick="eliminarCF('.$fila['id_centros_faenamiento_transporte'].'); return false; ">Quitar</button></td></tr>';
			}
			echo '</tbody>
					</table>';
			}else{
			    echo '<table id="listadoCF" value="no" style="width:100%"></table>';
			}
			
			?>		
	</fieldset>
	<button type="submit" class="guardar">Guardar</button>
	
</form>
<div id="cargarMensajeTemporal"></div>
	
<!--  $arrayParametros = array('identificador_operador' => $identificadorOperador, 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE'); -->
       
<script type="text/javascript">

$(document).ready(function(){

	distribuirLineas();
	construirValidador();
	cargarValorDefecto("registroContenedorVehiculo","<?php echo $registroContenedorVehiculo; ?>");
	cargarValorDefecto("servicio","<?php echo $servicioVehiculo; ?>");
	cargarValorDefecto("unidadMedida","<?php echo $unidadMedidaVehiculo; ?>");
	$("#capacidadInstalada").numeric();
	// alert("entro al principio");

	 if($("#registroContenedorVehiculo").val()=='NO'){
		$('#placa').hide();
		$('#marca').hide();
		$('#lbplaca').hide();
		$('#lbmarca').hide();
		$('#modelo').hide();
		$('#lbmodelo').hide();
		$('#color').hide();
		$('#lbcolor').hide();
		$('#anio').hide();
		$('#lbanio').hide();
		$('#tipo').hide();
		$('#lbtipo').hide();
		$('#clase').hide();
		$('#lbclase').hide();
		$('#servicio').hide();
		$('#lbservicio').hide();
 	}
	
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
                


$("#declararDatosVehiculo").submit(function(event){
	
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#registroContenedorVehiculo").val() == ""){	
		error = true;		
		$("#registroContenedorVehiculo").addClass("alertaCombo");
	}
	
	if($("#registroContenedorVehiculo").val()=='SI'){
		if($("#placa").val() == ""){	
			error = true;		
			$("#placa").addClass("alertaCombo");
		}
		if(!esCampoValido("#placa")){
			error = true;
			$("#placa").addClass("alertaCombo");
		}
		if($("#servicio").val() == ""){	
		error = true;		
		$("#servicio").addClass("alertaCombo");
	    }
	}

	
	

	if($("#capacidadInstalada").val() == "" || $("#capacidadInstalada").val() == 0){	
		error = true;		
		$("#capacidadInstalada").addClass("alertaCombo");
	}

	if($("#unidadMedida").val() == ""){	
		error = true;		
		$("#unidadMedida").addClass("alertaCombo");
	}

	if($("#tipoContenedor").val() == ""){	
		error = true;		
		$("#tipoContenedor").addClass("alertaCombo");
	}
	if($("#caracteristicaContenedor").val() == ""){	
		error = true;		
		$("#caracteristicaContenedor").addClass("alertaCombo");
	}
	var rowCount = $('#listadoCF tr').length; 
	if(rowCount <= 0){
		error = true;
		$('#cfIngresados').addClass("alertaCombo");		
	}
	
	if (!error){
		$('#declararDatosVehiculo').attr('data-opcion','guardarDeclararDatosVehiculo');
		ejecutarJson(this);
		$(".guardar").prop('disabled',false);
	}else{
		
		$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
		
	}
});
$("#buscarCentroFaenamiento").click(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#rucCF").val())){
		if((!$.trim($("#razonSocial").val())) ){
			error = true;
			$("#rucCF").addClass("alertaCombo");
			$("#razonSocial").addClass("alertaCombo");
		}
	}else if(!esCampoValido("#rucCF")){
        	error = true;
        	$("#rucCF").addClass("alertaCombo");
        	}

	if((!$.trim($("#razonSocial").val())) ){
		if(!$.trim($("#rucCF").val()) ){
			error = true;
			$("#rucCF").addClass("alertaCombo");
			$("#razonSocial").addClass("alertaCombo");
		}
	}

	if(!$.trim($("#provinciaCF").val()) || !esCampoValido("#provinciaCF")){
		error = true;
		$("#provinciaCF").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		$("#estado").html("").removeClass('alerta');

		$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
    	$.post("aplicaciones/registroOperador/buscarCentroFaenamiento.php", 
                {
        	       ruc:$("#rucCF").val(),
        	   
        	       provincia: $("#provinciaCF option:selected").text()
    	  		         		  		     
                }, function (data) {
                	$("#cargarMensajeTemporal").html("");
                	if (data.estado === 'EXITO') {
                		    $("#sitioCF").html(data.contenido);
                		    $("#sitioCF").attr('disabled', false);
                		    $("#areaCF").html('<option value="" >Seleccione...</option>');
                		    $("#areaCF").attr('disabled', false);
    	                    mostrarMensaje(data.mensaje, data.estado);
    	                    distribuirLineas();
                        } else {
                        	$("#sitioCF").html(data.contenido);
                        	$("#areaCF").html(data.contenido);
                        	$("#areaCF").attr('disabled', true);
                        	$("#sitioCF").attr('disabled', true);
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');
	}
});


$("#sitioCF").change(function(event){
	event.stopImmediatePropagation();
	$(".alertaCombo").removeClass("alertaCombo");
	if( $("#sitioCF").val() != ""){
		$("#estado").html("").removeClass('alerta');

		$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
    	$.post("aplicaciones/registroOperador/buscarAreaCentroFaenamiento.php", 
                {
        	       sitio:$("#sitioCF").val()
    	  		         		  		     
                }, function (data) {
                $("#cargarMensajeTemporal").html("");
                	if (data.estado === 'EXITO') {
                		    $("#areaCF").html(data.contenido);
                		    $("#areaCF").attr('disabled', false);
    	                    mostrarMensaje(data.mensaje, data.estado);
    	                    distribuirLineas();
                        } else {
                        	$("#areaCF").html(data.contenido);
                        	$("#areaCF").attr('disabled', true);
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');
	}else{
		$("#areaCF").html('<option value="" >Seleccione...</option>');
		$("#areaCF").attr('disabled', true);
	}
});

$("#agregarCf").click(function(event){
	event.stopImmediatePropagation();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = true;

	if(!$.trim($("#sitioCF").val())  ){
		error = false;
		$("#sitioCF").addClass("alertaCombo");
	}
   if(!$.trim($("#areaCF").val())  ){
	    error = false;
	   $("#areaCF").addClass("alertaCombo");
	}
	
	if (error){
		$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
    	$.post("aplicaciones/registroOperador/agregarCentroFaenamiento.php", 
                {
            		sitio:$("#sitioCF").val(),
            		area:$("#areaCF").val(),
            		idArea:$(".idArea").val(),
            		idOperacion:$(".idOperacion").val()
    	  		         		  		     
                }, function (data) {
                	$("#cargarMensajeTemporal").html("");
                	    if (data.estado === 'EXITO') {
                 		    $("#listadoCF").html(data.contenido);
    	                    mostrarMensaje(data.mensaje, data.estado);
    	                    distribuirLineas();
                        } else {
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');
	}else{

		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}
});
/***
 * eliminar un centro de faenamiento
 */
 function eliminarCF(id){
	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
 	$.post("aplicaciones/registroOperador/eliminarCentroFaenamiento.php", 
             {
 		        idCentroFaenamientoTransporte: id,
         		idArea:$(".idArea").val(),
         		idOperacion:$(".idOperacion").val()
             }, function (data) {
             	$("#cargarMensajeTemporal").html("");
             	    if (data.estado === 'EXITO') {
              		    $("#listadoCF").html(data.contenido);
 	                    mostrarMensaje(data.mensaje, data.estado);
 	                    distribuirLineas();
                     } else {
                     	mostrarMensaje(data.mensaje, "FALLO");
                     }
         }, 'json');

}

 /***
  * validar placa vehicular
  */
  $("#placa").blur(function(event){

	    event.stopImmediatePropagation();
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje('', "FALLO");
		var error = true;
		
	   if(!$.trim($("#placa").val())  ){
		    error = false;
		//    $('#servicio').val('');
		}
		if (error){
			var placa = $("#placa").val().replace('-','');
			placa = placa.toUpperCase();
         	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
          	$.post("aplicaciones/general/consultaWebServices.php", 
                      {
          		        clasificacion:"AntMatriculaLicencia",
                  		numero:placa
                      }, function (data) {
                      	$("#cargarMensajeTemporal").html("");
                      	    if (data.estado === 'exito') {
                      	    	//$("#servicio").val(data.valores.tipo_Servicio);
								  llenarCamposVehiculo(data);
                              } else {
                              	mostrarMensaje("No se encontraron datos para la PLACA..!!", "FALLO");
								  $('#marca').val('');
							      $('#modelo').val('');
								  $('#color').val('');
							      $('#tipo').val('');
							      $('#clase').val('');
							      $('#anio').val('');
                              	$('#servicio').val('');
                              	$("#placa").addClass("alertaCombo");
                		    	$('#placa').attr('placeholder',$("#placa").val());
                		    	$("#placa").val('');
                              }
                  }, 'json');
		}
 });

 function llenarCamposVehiculo(datos){
	$('#marca').val(datos.valores.marca);
	$('#modelo').val(datos.valores.modelo);
	$('#color').val(datos.valores.color);
	$('#tipo').val(datos.valores.tipo);
	$('#clase').val(datos.valores.clase);
	$('#anio').val(datos.valores.anio);

 }
 
 $("#registroContenedorVehiculo").change(function() {
	//alert("entro al final");
 	if($("#registroContenedorVehiculo").val()=='SI'){
		$('#placa').show();
		$('#lbplaca').show();
		$('#marca').show();
		$('#lbmarca').show();
		$('#modelo').show();
		$('#lbmodelo').show();
		$('#color').show();
		$('#lbcolor').show();
		$('#anio').show();
		$('#lbanio').show();
		$('#tipo').show();
		$('#lbtipo').show();
		$('#clase').show();
		$('#lbclase').show();
		$('#servicio').show();
		$('#lbservicio').show();
 	}else if($("#registroContenedorVehiculo").val()=='NO'){
		
		$('#placa').hide();
		$('#marca').hide();
		$('#lbplaca').hide();
		$('#lbmarca').hide();
		$('#modelo').hide();
		$('#lbmodelo').hide();
		$('#color').hide();
		$('#lbcolor').hide();
		$('#anio').hide();
		$('#lbanio').hide();
		$('#tipo').hide();
		$('#lbtipo').hide();
		$('#clase').hide();
		$('#lbclase').hide();
		$('#servicio').hide();
		$('#lbservicio').hide();
 	}
	 if($("#registroContenedorVehiculo").val()=='NO'){
	
		$('#placa').val('');
		$('#marca').val('');
		$('#modelo').val('');
		$('#color').val('');
		$('#anio').val('');
		$('#tipo').val('');
		$('#clase').val('');
		$('#servicio').val('');
		//
	 }
});

</script>