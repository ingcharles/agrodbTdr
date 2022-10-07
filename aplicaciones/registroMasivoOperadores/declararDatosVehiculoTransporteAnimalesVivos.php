<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorRegistroOperador.php';
    require_once '../../clases/ControladorAdministrarCatalogos.php';
	
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();	
	
	$datos = explode('-', $_POST['id']);
	$idSitio = $datos[0];
	$idOperacion = $datos[1];
	$contenido = "";

	$qSitio = pg_fetch_assoc($cro->abrirSitio($conexion, $idSitio));
	$identificadorPropietario = $qSitio['identificador_operador'];
	
	$datosOperador = pg_fetch_assoc($cro->buscarOperador($conexion, $identificadorPropietario));
	
	$qDatosOperacion = $cro->abrirOperacion($conexion, $identificadorPropietario, $idOperacion);
	$verificarProcesoModificacion = $qDatosOperacion[0]['procesoModificacion'];

	if($verificarProcesoModificacion == "t"){
	    $idOperadorTipoOperacion = $qDatosOperacion[0]['idOperadorTipoOperacion'];
	    $qDatosMedioTrasporteAnimalesVivos = $cro->obtenerDatosMedioTrasporteAnimalesVivosPorIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
	    $qDatosMedioTrasporteAnimalesVivos = pg_fetch_assoc($qDatosMedioTrasporteAnimalesVivos);
	    $placa = $qDatosMedioTrasporteAnimalesVivos['placa_vehiculo'];
	    $idPropietario = $qDatosMedioTrasporteAnimalesVivos['identificador_propietario_vehiculo'];
	    $marca = $qDatosMedioTrasporteAnimalesVivos['marca_vehiculo'];
	    $modelo = $qDatosMedioTrasporteAnimalesVivos['modelo_vehiculo'];
	    $anio = $qDatosMedioTrasporteAnimalesVivos['anio_vehiculo'];
	    $color = $qDatosMedioTrasporteAnimalesVivos['color_vehiculo'];
	    $clase = $qDatosMedioTrasporteAnimalesVivos['clase_vehiculo'];
	    $tipo = $qDatosMedioTrasporteAnimalesVivos['tipo_vehiculo'];
	    $tamanioContenedor = $qDatosMedioTrasporteAnimalesVivos['tamanio_contenedor_vehiculo'];
	    $caracteristicaContenedor = $qDatosMedioTrasporteAnimalesVivos['caracteristica_contenedor_vehiculo'];
	    
	    $contenido = '<div data-linea="1">
    			<label>Sitio: </label>' . $qDatosOperacion[0]['nombreSitio'] . '
        		</div>
        		<div data-linea="1">
        			<label>Área: </label>' . $qDatosOperacion[0]['nombreArea'] . '
        		</div>
        		<hr/>
                <div data-linea="2" >
                    <label for="placa">Placa: </label>
                    ' . $placa . '
                </div>
        		<div data-linea="2">
        			<label for="identificadorPropietario">ID Propietario: </label>
        			' . $idPropietario . '
        		</div>
        		<div data-linea="3">
        			<label for="marca">Marca: </label>
                    '. $marca .'
        		</div>
        		<div data-linea="3">
        			<label for="modelo">Modelo: </label>
                    ' . $modelo . '
        		</div>
        		<div data-linea="4">
        			<label for="anio">Año: </label>
                    ' . $anio . '
        		</div>
        		<div data-linea="4">
        			<label for="color">Color: </label>
                    ' . $color . '
        		</div>
        		<div id="resultadoModelo" data-linea="5">
        			<label for="clase">Clase: </label>
                    ' . $clase . '
        		</div>
        		<div data-linea="5">
        			<label for="tipo">Tipo: </label>
        			' . $tipo . '
        		</div>
        		<div data-linea="6">
        			<label for="tamanioContenedor">*Tamaño del contenedor (m2): </label>
        			<input	type="text" id="tamanioContenedor" name="tamanioContenedor" value="' . $tamanioContenedor . '" maxlength="6" />
        		</div>
        		<div data-linea="7">
        			<label for="caracteristicaContenedor">*Características del contenedor: </label>
        		</div>
        		<div data-linea="8">
        			<textarea id="caracteristicaContenedor" name="caracteristicaContenedor" maxlength="500">' . $caracteristicaContenedor . '</textarea>
        		</div>';
	    
	}else{
	    
	    $contenido = '<div data-linea="1">
    			<label>Sitio: </label>' . $qDatosOperacion[0]['nombreSitio'] . '
        		</div>
        		<div data-linea="1">
        			<label>Área: </label>' . $qDatosOperacion[0]['nombreArea'] . '
        		</div>
        		<hr/>
        		<div data-linea="2" >
        			<label for="placa">*Placa: </label>
        			<input type="text" id="placa" name="placa" placeholder="Ej: AAA-0000" data-er="[A-Za-z]{3}-[0-9]{3,4}" data-inputmask="'."'mask'".':'. "'aaa-9999'". '" style="text-transform:uppercase;" />
        		</div>
        		<div data-linea="2">
        			<label for="identificadorPropietario">ID Propietario: </label>
        			<input type="text" id="identificadorPropietario" name="identificadorPropietario" readonly="readonly" />
        		</div>
        		<div data-linea="3">
        			<label for="marca">Marca: </label>
                    <input type="text" id="marca" name="marca" readonly="readonly" />
        		</div>
        		<div data-linea="3">
        			<label for="modelo">Modelo: </label>
                    <input type="text" id="modelo" name="modelo" readonly="readonly" />
        		</div>
        		<div data-linea="4">
        			<label for="anio">Año: </label>
                    <input type="text" id="anio" name="anio" readonly="readonly" />
        		</div>
        		<div data-linea="4">
        			<label for="color">Color: </label>
                    <input type="text" id="color" name="color" readonly="readonly" />
        		</div>
        		<div id="resultadoModelo" data-linea="5">
        			<label for="clase">Clase: </label>
                    <input type="text" id="clase" name="clase" readonly="readonly" />
        		</div>
        		<div data-linea="5">
        			<label for="tipo">Tipo: </label>
        			<input	type="text" id="tipo" name="tipo" readonly/>
        		</div>
        		<div data-linea="6">
        			<label for="tamanioContenedor">*Tamaño del contenedor (m2): </label>
        			<input	type="text" id="tamanioContenedor" name="tamanioContenedor" />
        		</div>
        		<div data-linea="7">
        			<label for="caracteristicaContenedor">*Características del contenedor: </label>
        		</div>
        		<div data-linea="8">
        			<textarea id="caracteristicaContenedor" name="caracteristicaContenedor"></textarea>
        		</div>';
	}

?>

<header>
	<h1>Declarar información adicional</h1>
</header>

<div id="estado"></div>

<fieldset>
	<legend>Datos Operador</legend>
	<div data-linea="1">
		<label>Identificación: </label> <?php echo $datosOperador['identificador']; ?>
	</div>
	<div data-linea="1">
		<label>Razón Social: </label> <?php echo $datosOperador['razon_social']; ?>
	</div>
	<div data-linea="2">
		<label>Nombres: </label> <?php echo $datosOperador['nombre_representante']; ?>
	</div>
	<div data-linea="2">
		<label>Apellidos: </label> <?php echo $datosOperador['apellido_representante']; ?>
	</div>		
	<div data-linea="8">
		<label>Corre electrónico: </label> <?php echo $datosOperador['correo']; ?>
	</div>
</fieldset>

<fieldset>
	<legend>Datos del sitio y área</legend>
			
		<div data-linea="5">
			<label>Nombre del sitio: </label> <?php echo $qDatosOperacion[0]['nombreSitio']; ?>
		</div>
		<div data-linea="5">
			<label>Provincia: </label> <?php echo $qDatosOperacion[0]['provincia']; ?>
		</div>
		<div data-linea="6">
			<label>Cantón: </label> <?php echo $qDatosOperacion[0]['canton']; ?>
		</div>
		<div data-linea="6">
			<label>Parroquia: </label> <?php echo $qDatosOperacion[0]['parroquia']; ?>
		</div>
		<div data-linea="7">
			<label>Dirección: </label> <?php echo $qDatosOperacion[0]['direccionSitio']; ?>
		</div>
		<div data-linea="8">
			<label>Referencia: </label> <?php echo ($qDatosOperacion[0]['referencia'] != "") ? $qDatosOperacion[0]['referencia'] : "S/R"; ?>
		</div>
		<hr>
		<?php 

	$i=40;

	foreach ($qDatosOperacion as $solicitud){

		echo ($i!=40?'<hr>':'');

		echo '
				<div data-linea='.$i.'>
					<label>Nombre del área: </label> ' . $solicitud['nombreArea'] . ' 
				</div>
				<div data-linea='.$i.'>
					<label>Código del área: </label> ' . $solicitud['codificacionArea'] . ' 
				</div>
				<div data-linea='.++$i.'>
					<label>Tipo de área: </label> ' . $solicitud['tipoArea'] . ' 
				</div>
				
				<div data-linea='.$i++.'>
					<label>Superficie utilizada: </label> ' . $solicitud['superficieArea'] . ' 
				</div>';
							
			$i++;
	}	
	?>	
	</fieldset>

<form id='declararDatosVehiculoTransporteAnimalesVivos' data-rutaAplicacion='registroMasivoOperadores' data-opcion="guardarDeclararDatosVehiculoTransporteAnimalesVivos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" class="idSitio" name="idSitio" value="<?php echo $idSitio;?>" readonly="readonly" />
	<input type="hidden" class="idArea" name="idArea" value="<?php echo $qDatosOperacion[0]['idArea'];?>" readonly="readonly" />
	<input type="hidden" class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" readonly="readonly" />
	
	<fieldset>
		<legend>Datos del medio de transporte</legend>	
		<?php
		  echo $contenido;
	    ?>		
	</fieldset>

	<button type="submit" class="guardar">Guardar</button>
	
</form>
<div id="cargarMensajeTemporal"></div>
       
<script type="text/javascript">

	var verificarProcesoModificacion = <?php echo json_encode($verificarProcesoModificacion); ?>;
	var identificadorPropietario = <?php echo json_encode($identificadorPropietario); ?>;

    $(document).ready(function(){
    	distribuirLineas();
    	construirValidador();
    	$("#tamanioContenedor").numeric();
    	$("#estado").html("").removeClass('alerta');
    });
    
    function esCampoValido(elemento){
    	var patron = new RegExp($(elemento).attr("data-er"),"g");
    	return patron.test($(elemento).val());
    }                
    
    $("#declararDatosVehiculoTransporteAnimalesVivos").submit(function(event){
    	
    	event.preventDefault();
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    	
    	if(verificarProcesoModificacion != "t"){
    	
        	if(!$.trim($("#placa").val())){	
        		error = true;		
        		$("#placa").addClass("alertaCombo");
        	}
        	
        	if(!$.trim($("#identificadorPropietario").val())){	
        		error = true;		
        		$("#identificadorPropietario").addClass("identificadorPropietario");
        	}
        	
        	if(!$.trim($("#marca").val())){	
        		error = true;		
        		$("#marca").addClass("alertaCombo");
        	}
    
        	if(!$.trim($("#modelo").val())){	
        		error = true;		
        		$("#modelo").addClass("alertaCombo");
        	}
        
        	if(!$.trim($("#anio").val())){	
        		error = true;		
        		$("#anio").addClass("alertaCombo");
        	}
    
        	if(!$.trim($("#color").val())){	
        		error = true;		
        		$("#color").addClass("alertaCombo");
        	}
    
        	if(!$.trim($("#clase").val())){	
        		error = true;		
        		$("#clase").addClass("alertaCombo");
        	}
    
        	if(!$.trim($("#tipo").val())){	
        		error = true;		
        		$("#tipo").addClass("alertaCombo");
        	}

    	}
    	
    	if(!$.trim($("#tamanioContenedor").val())){	
    		error = true;		
    		$("#tamanioContenedor").addClass("alertaCombo");
    	}

    	if(!$.trim($("#caracteristicaContenedor").val())){	
    		error = true;		
    		$("#caracteristicaContenedor").addClass("alertaCombo");
    	}
    	
		if (!error){    		
			ejecutarJson(this);
			$(".guardar").attr('disabled', true);   		
    	}else{    		
    		$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");    		
    	}
    });

	/***
	* validar placa vehicular
	***/
    $("#placa").blur(function(event){
    
        event.stopImmediatePropagation();
    	$(".alertaCombo").removeClass("alertaCombo");
    	mostrarMensaje('', "FALLO");
    	var error = true;
    	
       if(!$.trim($("#placa").val())  ){
    	    error = false;
    	   $('#servicio').val('');
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
                            var idPropietario = data.valores.doc_Propietario.split('-')[1];
                            if(idPropietario === identificadorPropietario){
                            	$("#identificadorPropietario").val(idPropietario);
                            	$("#marca").val(data.valores.marca);
                            	$("#modelo").val(data.valores.modelo);
                            	$("#anio").val(data.valores.anio);
                            	$("#color").val(data.valores.color);
                            	$("#clase").val(data.valores.clase);
                            	$("#tipo").val(data.valores.tipo);
                            	$(".guardar").attr('disabled', false);                  	
                            }else{
                                error = true;
                                $("#identificadorPropietario").val('');
                            	$("#marca").val('');
                            	$("#modelo").val('');
                            	$("#anio").val('');
                            	$("#color").val('');
                            	$("#clase").val('');
                            	$("#tipo").val('');
                                $(".guardar").attr('disabled',true);
                                $("#estado").html("La placa no corresponde a un vehículo del operador.").addClass('alerta');
                            }                   	    	
                        } else {
                        	mostrarMensaje("No se encontraron datos para la PLACA..!!", "FALLO");
                            $('#servicio').val('');
                            $("#placa").addClass("alertaCombo");
                            $('#placa').attr('placeholder',$("#placa").val());
                            $("#placa").val('');
                        }
                  }, 'json');

    		/*$("#identificadorPropietario").val('1722551049');
        	$("#marca").val('KIA');
        	$("#modelo").val('Rio');
        	$("#anio").val('2020');
        	$("#color").val('Rojo');
        	$("#clase").val('Sedan');
        	$("#tipo").val('Sedan');*/

    	}
    });

</script>