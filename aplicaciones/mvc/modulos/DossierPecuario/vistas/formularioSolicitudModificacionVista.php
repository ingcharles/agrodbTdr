<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_provincia_operador" name="id_provincia_operador" />
	<input type="hidden" id="id_provincia_revision" name="id_provincia_revision" />
	<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="Modificacion"/>
	<input type="hidden" id="id" name="id" />
	
	<fieldset>
		<legend>Indicaciones para modificación de Registro de Productos Veterinarios</legend>				

		<div data-linea="1">
			<p>En esta sección se puede solicitar la modificación del registro de un producto ya aprobado.</p>
			<p>Puede realizar a través del sistema el cambio de tipo de producto entre las siguientes categorías:</p>
			<p>- Alimentos y suplementos alimenticios, Alimentos medicados y suplementos medicados, Sales minerales, premezclas y núcleos, Aditivos alimentarios, Snacks o golosinas.</p>
			<p>- Cosméticos, Antisépticos, desinfectantes, sanitizantes y plaguicidas de uso veterinario.</p>
			<p>En caso que su producto sufra un cambio entre otras categorías autorizadas en la normativa vigente deberá solicitar la cancelación del registro actual a través de oficio 
			dirigido a la Coordinación General de Registros y crear una nueva solicitud de registro de producto a través del Sistema Dossier Pecuario.</p>
			<p>No puede realizar el cambio entre categorías no autorizadas en la normativa vigente (Ej: Alimento por Farmacológico).</p>
			<p>En caso de tener alguna duda puede contactarse al correo productosveterinarios@agrocalidad.gob.ec</p>
			<p class="nota">Se recuerda que al final de la solicitud deberá cargar un archivo adjunto donde especifique qué información fue modificada.</p>
		</div>				

		<div data-linea="2">
			<label for="idSolicitud">Producto a Modificar: </label>
			<select id="idSolicitud" name="idSolicitud" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProductosModificacion();
                ?>
            </select>
		</div>
	</fieldset>
	
	<div data-linea="4">
		<button type="submit" class="guardar">Guardar</button>
	</div>

</form >

<script type ="text/javascript">
var identificadorUsuario = <?php echo json_encode($_SESSION['usuario']); ?>;
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		//Obtiene la información del usuario de su registro de operador
		fn_buscarDatosOperador();
		
		construirValidador();
		distribuirLineas();
	 });

	//Función para mostrar los datos del operador
    function fn_buscarDatosOperador() {
    	var identificador = identificadorUsuario;
        
        if (identificador !== "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperador",
               {
                identificador : identificadorUsuario
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");       		
				}else{
					fn_cargarDatosOperador(data);
				}
            }, 'json');
        }
    } 

  //Función para mostrar los datos obtenidos del operador
    function fn_cargarDatosOperador(data) {
    	$("#id_provincia_operador").val(data.id_provincia);
    	$("#id_provincia_revision").val(data.id_provincia);		
    } 

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			
	        if (respuesta.estado == 'exito'){
	        	$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");

	        	if(bandera === 'modificacion'){
    	        	$("#id").val(respuesta.contenido);
    	       		$("#formulario").attr('data-opcion', 'Solicitud/editar');
					abrir($("#formulario"),event,false);
	        	}else{
	        		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		        	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	        	}
	        }
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>