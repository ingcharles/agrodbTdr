<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='resultadoInspeccion/guardarNotificar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
    <?php echo $this->datosGenerales;?>
    
    <fieldset>
    	<legend>Datos de Exportación</legend>				
    		<div data-linea="1">
			<label for="producto">Producto: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getProducto();?></span>
		</div>				

		<div data-linea="1">
			<label for="marca">Marca: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getMarca();?></span>
		</div>				

		<div data-linea="2">
			<label for="tipo_produccion">Tipo producción: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getTipoProduccion();?></span>
		</div>				

		<div data-linea="2">
			<label for="viaje">Viaje: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getViaje();?></span>
		</div>				

		<div data-linea="3">
			<label for="pais_destino">País destino: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getPaisDestino();?></span>
		</div>				

		<div data-linea="3">
			<label for="puerto_embarque">Puerto de Embarque: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getPuertoEmbarque();?></span>
		</div>				

		<div data-linea="4">
			<label for="nombre_vapor">Nombre de Vapor: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getNombreVapor();?></span>
		</div>				

    </fieldset>
       <fieldset>
   		<legend>Datos de inspección</legend>				
    	<div data-linea="1">
			<label for="lugar_inspeccion">Lugar de inspección: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getLugarInspeccion();?></span>
		</div>				

		<div data-linea="1">
			<label for=nombre_inspeccion>Nombre: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getNombreInspeccion();?></span>
		</div>				

		<div data-linea="2">
			<label for="representante_tecnico">Representante técnico: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getRepresentanteTecnico();?></span>
		</div>	
		<div data-linea="2">
			<label for="celular_inspeccion">Celular: </label>
			<span><?php echo $this->modeloSolicitudInspeccion->getCelularInspeccion();?></span>
		</div>			
    </fieldset>
     <fieldset>
    	<legend>Productores</legend>				
    	<div id="listaProductores" style="width:100%">
    	<?php echo $this->listarProductores;?>
    	</div>
    </fieldset>
  
    
   <input type="hidden" id="id_solicitud_inspeccion" name="id_solicitud_inspeccion" value="<?php  echo $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion();?>"/>
<input type="hidden" name="identificador" id="identificador" value="<?php  echo $this->modeloSolicitudInspeccion->getIdentificador();?>">
        <div id="cargarMensajeTemporal"></div>
		<div data-linea="15" id="perfil">
			<button type="submit" class="guardar">Notificar</button>
		</div>
</form >
<script type ="text/javascript">
    var idSolicitudInseccion = <?php echo json_decode($this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());?>;
    var totalCajas=0;
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#cantidad_aprobada").attr('disabled','disabled');
		$("#cantidad_aprobada").numeric();
		mostrarMensaje("", "FALLO");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var check =  $("input[name='check[]']").map(function(){ if($(this).prop("checked")){return 1;}}).get();
		if(check == ''){ 
			error = true;
			$("#listaProductores").addClass("alertaCombo");
			var texto = "Debe seleccionar al menos un Item de los productores..!!.";
		}
		
		if (!error) {
			mostrarMensaje('Notificación enviada','EXITO');
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				abrir($("#formulario"), event, false);
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			}, 250);
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});

	  
</script>
