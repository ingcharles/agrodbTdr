<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div id="registro">
	<fieldset>
    		<legend>Valijas</legend>				
    
    		<div data-linea="1">
    			<label for="numero_valija">Registro del Trámite: </label>
    			<?php echo $this->modeloValijas->getNumeroValija(); ?>
    		</div>				
    
    		<div data-linea="1">
    			<label for="id_ventanilla">Ventanilla: </label>
    			<?php echo $this->modeloValijas->getNombreVentanilla(); ?>
    		</div>				
    
    		<div data-linea="2">
    			<label for="identificador">Responsable: </label>
    			<?php echo $this->modeloValijas->getNombreEmpleado(); ?>
    		</div>				
    
    		<hr />
    
    		<div data-linea="3">
    			<label for="guia_correo">Código Guía Correo: </label>
    			<?php echo $this->modeloValijas->getGuiaCorreo(); ?>
    		</div>				
    		
    		<div data-linea="4">
    			<label for="id_unidad_origen">Unidad de Origen: </label>
    			<?php echo $this->modeloValijas->getUnidadOrigen(); ?>			
    		</div>
    		
    		<div data-linea="5">
    			<label for="remitente">Remitente: </label>
    			<?php echo $this->modeloValijas->getRemitente(); ?>
    		</div>
    
    		<div data-linea="6">
    			<label for="destinatario">Destinatario: </label>
    			<?php echo $this->modeloValijas->getDestinatario(); ?>
    		</div>				
    
    		<div data-linea="7">
    			<label for="direccion">Dirección: </label>
    			<?php echo $this->modeloValijas->getDireccion(); ?>
    		</div>				
    
    		<div data-linea="8">
    			<label for="telefono">Teléfono: </label>
    			<?php echo $this->modeloValijas->getTelefono(); ?>
    		</div>				
    
    		<div data-linea="9">
    			<label for="id_pais">País: </label>
    			<?php echo $this->modeloValijas->getPais(); ?>
    		</div>				
    
    		<div data-linea="10">
    			<label for="id_provincia">Provincia: </label>
    			<?php echo $this->modeloValijas->getProvincia(); ?>
    		</div>				
    
    		<div data-linea="11">
    			<label for="id_canton">Cantón: </label>
    			<?php echo $this->modeloValijas->getCanton(); ?>
    		</div>				
    
    		<div data-linea="12">
    			<label for="referencia">Referencia: </label>
    			<?php echo $this->modeloValijas->getReferencia(); ?>
    		</div>				
    
    		<div data-linea="13">
    			<label for="email">Email: </label>
    			<?php echo $this->modeloValijas->getEmail(); ?>
    		</div>				
    
    		<div data-linea="14">
    			<label for="descripcion">Descripción: </label>
    			<?php echo $this->modeloValijas->getDescripcion(); ?>
    		</div>				
    		
    		<div data-linea="15">
    			<label for="estado_entrega">Estado: </label>
    			<?php echo $this->modeloValijas->getEstadoEntrega(); ?>
    		</div>
    		
    		<div data-linea="16" class="entrega">
    			<label for="descripcion">Nombre persona que recibe: </label>
    			<?php echo $this->modeloValijas->getNombreEntrega(); ?>
    		</div>
    		
    		<div data-linea="17" class="entrega">
    			<label for="fecha_entrega">Fecha entrega: </label>
    			<?php echo ($this->modeloValijas->getFechaEntrega()!=null?date('Y-m-d',strtotime($this->modeloValijas->getFechaEntrega())):''); ?>
    		</div>
    		
    		<div data-linea="18" class="entrega">
    			<label for="observaciones">Observaciones: </label>
    			<?php echo $this->modeloValijas->getObservaciones(); ?>
    		</div>
    	</fieldset >
</div>

<div id="">
    <form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='valijas/guardar' data-destino="detalleItem" method="post">
    
    	<input type="hidden" id="id_valija" name="id_valija" value="<?php echo $this->modeloValijas->getIdValija(); ?>" />
    	<input type="hidden" id="id_unidad_destino" name="id_unidad_destino" value="<?php echo $datosUsuario['idUnidadDestino']; ?>" />
    	<input type="hidden" id="codigo_ventanilla" name="codigo_ventanilla" value="<?php echo $datosUsuario['codigoVentanilla']; ?>" />
    	
    	<fieldset>
    		<legend>Valijas</legend>				
    
    		<div data-linea="1">
    			<label for="numero_valija">Registro del Trámite </label>
    			<input type="text" id="numero_valija" name="numero_valija" readonly="readonly" value="<?php 
    			echo $this->modeloValijas->getNumeroValija(); ?>" />
    		</div>				
    
    		<div data-linea="1">
    			<label for="id_ventanilla">Ventanilla: </label>
    			<input type="hidden" id="id_ventanilla" name="id_ventanilla" readonly="readonly" value="<?php 
    			echo $this->modeloValijas->getIdVentanilla(); ?>"  />
    			
    			<input type="text" readonly="readonly" value="<?php 
    			echo $this->modeloValijas->getNombreVentanilla(); ?>"  />
    		</div>				
    
    		<div data-linea="2">
    			<label for="identificador">Responsable: </label>
    			<input type="hidden" id="identificador" name="identificador" readonly="readonly" value="<?php 
    			echo $this->modeloValijas->getIdentificador(); ?>" />
    			
    			<input type="text" readonly="readonly" value="<?php 
    			echo $this->modeloValijas->getNombreEmpleado(); ?>"  />
    		</div>				
    
    		<hr />
    
    		<div data-linea="3">
    			<label for="guia_correo">Código Guía Correo: </label>
    			<input type="text" id="guia_correo" name="guia_correo" value="<?php echo $this->modeloValijas->getGuiaCorreo(); ?>"
    			placeholder="Código único del envío de valija provisto por Correos del Ecuador o el medio de envío del paquete." 
    			required="required" maxlength="32" />
    		</div>		
    		
    		<div data-linea="4">
    			<label for="id_unidad_origen">Unidad de Origen: </label>
    			<select id="id_unidad_origen" name="id_unidad_origen">
    				<option>Seleccione....</option>
    				<?php
    				    echo $this->comboAreasCategoriaNacional($this->modeloValijas->getIdUnidadOrigen());
                    ?>
                </select>
            
            	<input type="hidden" id="unidad_origen" name="unidad_origen" value="<?php echo $this->modeloValijas->getUnidadOrigen(); ?>" maxlength="256" />
    		</div>		
    
    		<div data-linea="5">
    			<label for="remitente">Remitente: </label>
    			<input type="text" id="remitente" name="remitente" value="<?php echo $this->modeloValijas->getRemitente(); ?>" required maxlength="256" />
    		</div>		
    
    		<div data-linea="6">
    			<label for="destinatario">Destinatario: </label>
    			<input type="text" id="destinatario" name="destinatario" value="<?php echo $this->modeloValijas->getDestinatario(); ?>"
    			placeholder="Nombre de la persona a la que está dirigida la valija" required="required" maxlength="512" />
    		</div>				
    
    		<div data-linea="7">
    			<label for="direccion">Dirección: </label>
    			<input type="text" id="direccion" name="direccion" value="<?php echo $this->modeloValijas->getDireccion(); ?>"
    			placeholder="Dirección a la que se envía la valija" required="required" maxlength="1024" />
    		</div>				
    
    		<div data-linea="8">
    			<label for="telefono">Teléfono: </label>
    			<input type="text" id="telefono" name="telefono" value="<?php echo $this->modeloValijas->getTelefono(); ?>"
    			placeholder="Número telefónico de contacto del destinatario" required="required" maxlength="16" />
    		</div>				
    
    		<div data-linea="9">
    			<label for="id_pais">País: </label>
    			<select id="id_pais" name="id_pais">
    				<option value="">Seleccionar....</option>
                    <?php 
                        echo $this->comboPaises($this->modeloValijas->getIdPais());
                    ?>
                </select>
    			<input type="hidden" id="pais" name="pais" />
    		</div>				
    
    		<div data-linea="10">
    			<label for="id_provincia">Provincia: </label>
    			<select id="id_provincia" name="id_provincia">
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboProvinciasEc($this->modeloValijas->getIdProvincia());
                    ?>
                </select>
    			
    			<input type="hidden" id="provincia" name="provincia" value="<?php echo $this->modeloValijas->getProvincia(); ?>" />
    		</div>				
    
    		<div data-linea="11">
    			<label for="id_canton">Cantón: </label>
    			<select id="id_canton" name="id_canton" >
                    <option value="">Seleccionar....</option>
                </select>
    			
    			<input type="hidden" id="canton" name="canton" value="<?php echo $this->modeloValijas->getCanton(); ?>" />
    		</div>				
    
    		<div data-linea="12">
    			<label for="referencia">Referencia: </label>
    			<input type="text" id="referencia" name="referencia" value="<?php echo $this->modeloValijas->getReferencia(); ?>"
    			placeholder="Información de referencia del envío" required="required" maxlength="128" />
    		</div>				
    
    		<div data-linea="13">
    			<label for="email">Email: </label>
    			<input type="text" id="email" name="email" value="<?php echo $this->modeloValijas->getEmail(); ?>"
    			placeholder="Correo electrónico del destinatario de la valija" maxlength="128" />
    		</div>				
    
    		<div data-linea="14">
    			<label for="descripcion">Descripción: </label>
    			<input type="text" id="descripcion" name="descripcion" value="<?php echo $this->modeloValijas->getDescripcion(); ?>"
    			placeholder="Descripción del contenido de la valija" maxlength="1024" />
    		</div>				
    		
    		
    
    		<div data-linea="15">
    			<label for="estado_entrega">Estado: </label>
    			<select id="estado_entrega" name="estado_entrega" style="width: 100%;" required> 
                <?php 
                    echo $this->comboEnviadoCerrado($this->modeloValijas->getEstadoEntrega());
                ?>
                </select>
    		</div>
    		
    		<div data-linea="16" class="entrega">
    			<label for="descripcion">Nombre persona que recibe: </label>
    			<input type="text" id="nombre_entrega" name="nombre_entrega" value="<?php echo $this->modeloValijas->getNombreEntrega(); ?>"
    			placeholder="Nombre de la persona que recibe la valija" maxlength="512" disabled='disabled' required='required' />
    		</div>
    		
    		<div data-linea="17" class="entrega">
    			<label for="fecha_entrega">Fecha entrega: </label>
    			<input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo ($this->modeloValijas->getFechaEntrega()!=null?date('Y-m-d',strtotime($this->modeloValijas->getFechaEntrega())):''); ?>"
    				maxlength="8" max="<?php echo date('Y-m-d'); ?>" disabled='disabled' required='required' />
    		</div>
    		
    		<div data-linea="18" class="entrega">
    			<label for="observaciones">Observaciones: </label>
    			<input type="text" id="observaciones" name="observaciones" value="<?php echo $this->modeloValijas->getObservaciones(); ?>"
    			placeholder="Observaciones del proceso" maxlength="1024" disabled='disabled' required='required' />
    		</div>
    
    		<div data-linea="19">
    			<button type="submit" class="guardar">Guardar</button>
    		</div>
    	</fieldset >
    </form >
</div>

<script type ="text/javascript">
var estado = <?php echo json_encode($this->modeloValijas->getEstadoEntrega()); ?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		fn_cargarCantones('<?php echo $this->modeloValijas->getIdCanton(); ?>');
		$("#id_provincia").attr('disabled','disabled');
		$("#id_canton").attr('disabled','disabled');

		if(estado == 'Entregado'){
			$("#formulario").hide();
			$("#registro").show();

			$("#nombre_entrega").removeAttr('disabled');
    		$("#fecha_entrega").removeAttr('disabled');
    		$("#observaciones").removeAttr('disabled');
		}else{
			$(".entrega").hide();
			$("#formulario").show();
			$("#registro").hide();
		}
	 });

	//Cuando seleccionamos un país, llenamos el combo de provincias si aplica
    $("#id_pais").change(function () {
        if ($(this).val !== "") {
        	$("#pais").val($("#id_pais option:selected").text());

        	$("#id_provincia").val('');
        	$("#provincia").val("");
        	$("#id_canton").val('');
        	$("#canton").val("");

        	if($("#id_pais option:selected").text() === 'Ecuador'){
        		$("#id_provincia").removeAttr('disabled');
        	}
        }
    });

    //Cuando seleccionamos una provincia, llenamos el combo de cantones
    $("#id_provincia").change(function () {
        if ($(this).val !== "") {
        	$("#id_canton").removeAttr('disabled');
        	$("#provincia").val($("#id_provincia option:selected").text());
            fn_cargarCantones('');
            $("#id_provincia").removeAttr('disabled');
        }
    });

  

	//Para cargar los cantones una vez seleccionada la provincia
    function fn_cargarCantones(idCanton) {
        var idProvincia = $("#id_provincia option:selected").val();
        if (idProvincia !== "") {
        	$.post("<?php echo URL ?>SeguimientoDocumental/Valijas/comboCantones/" + idProvincia, function (data) {
                $("#id_canton").html(data);
                $('#id_canton option[value="' + idCanton + '"]').prop('selected', true);
            });
        }
    } 

    $("#id_canton").change(function () {
    	if ($(this).val !== "") {
        	$("#canton").val($("#id_canton option:selected").text());
        }
    });

    $("#id_unidad_origen").change(function () {
    	if ($("#id_unidad_origen option:selected").val() != "") {
        	$("#unidad_origen").val($("#id_unidad_origen option:selected").text());
        }
    });

    $("#estado_entrega").change(function () {
    	if ($("#estado_entrega option:selected").val() == "Entregado") {
    		$(".entrega").show();
    		
    		$("#nombre_entrega").attr('required', 'required');
        	$("#fecha_entrega").attr('required', 'required');
        	$("#observaciones").attr('required', 'required');

    		$("#nombre_entrega").removeAttr('disabled');
    		$("#fecha_entrega").removeAttr('disabled');
    		$("#observaciones").removeAttr('disabled');
        }else{
        	$(".entrega").hide();
        	
        	$("#nombre_entrega").removeAttr('required');
    		$("#fecha_entrega").removeAttr('required');
    		$("#observaciones").removeAttr('required');        	

        	$("#nombre_entrega").attr('disabled', 'disabled');
        	$("#fecha_entrega").attr('disabled', 'disabled');
        	$("#observaciones").attr('disabled', 'disabled');
        }
    });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_filtrar_default();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>