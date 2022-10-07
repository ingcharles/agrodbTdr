<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='resultadoInspeccion/consumir' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
     <?php echo $this->datosGenerales($this->operador);?>
    
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
    	<?php echo $this->listarProductores($this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());?>
    	</div>
    </fieldset>
     
    <fieldset id="resultadoInspeccion">
   		<legend>Resultado de Inspección</legend>		
   		<div data-linea="1">
			<label for="lugar_inspeccion">Lugar de inspección: </label>
			<span><?php echo $this->modeloResultadoInspeccion->getLugarInspeccion();?></span>
		</div>			
    	<div data-linea="2">
		    <input  name="resultado[]" type="radio"  disabled id="apTotal"   value="apTotal" ><span> Aprobación total</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="resultado[]" type="radio" disabled id="apParcial" value="apParcial" ><span> Aprobación parcial</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="resultado[]" type="radio" disabled id="desapTotal" value="desapTotal" ><span> Desaprobación total</span>
		</div>				
    	<div data-linea="3">
			<label for="cantidad_aprobada">Cantidad aprobada: </label>
			<span><?php echo $this->modeloResultadoInspeccion->getCantidadAprobada();?></span>
		</div>
		<div data-linea="4">
			<label for="num_contenedores">Nro. Contenedor: </label>
		<span><?php echo $this->modeloResultadoInspeccion->getNumContenedores();?></span>
		</div>
		<div data-linea="5">
			<label for="observacion">Observación: </label>
			<span><?php echo $this->modeloResultadoInspeccion->getObservacion();?></span>
		</div>
    </fieldset>
    
     <fieldset id="archivoInspeccion">
   		<legend>Inspección Musáceas pdf</legend>		
   				 <a href="<?php echo $this->modeloSolicitudInspeccion->getRutaArchivo();?>" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar Inspección Musáceas</a>
    </fieldset>
     <fieldset id="fechaConsumo">
   		<legend>Fecha</legend>		
  <span><strong> <?php echo $this->fechaCambioEstado;?></strong>  </span>   
   </fieldset>
	    <div id="cargarMensajeTemporal"></div>
		<div id="consumida" data-linea="15">
			<button type="submit" class="guardar">Consumida</button>
		</div>
		<input type="hidden" id="id_solicitud_inspeccion" value="<?php echo $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion();?>" name="id_solicitud_inspeccion" />
</form >
<button type="button" class="generar" id="generar">Generar Reporte</button>
<script type ="text/javascript">
var perfil = <?php echo json_encode($this->perfilUsuario);?>;
var resultado = <?php echo ($this->modeloResultadoInspeccion->getResultado()!= NULL)?json_encode($this->modeloResultadoInspeccion->getResultado()):0;?>;
var archivo = <?php echo ($this->modeloSolicitudInspeccion->getRutaArchivo()!= NULL)?json_encode($this->modeloSolicitudInspeccion->getRutaArchivo()):0;?>;
var estado = <?php echo ($this->modeloSolicitudInspeccion->getEstado()!= NULL)?json_encode($this->modeloSolicitudInspeccion->getEstado()):0;?>;

   	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("", "FALLO");
		$("#consumida").hide();
		$("#generar").hide(); 
		  if($.inArray("PFL_DES_MUS", perfil) >= 0 ){
				$("#consumida").show(); 
			}
		if(resultado == 'Aprobación total'){
			$("#apTotal").attr('checked','checked');
			}else if(resultado == 'Aprobación parcial'){
				$("#apParcial").attr('checked','checked');
			}else if(resultado == 'Desaprobación total'){
				$("#desapTotal").attr('checked','checked');
			}
		if(resultado == 0){
				$("#resultadoInspeccion").hide();
			}else{
				$("#resultadoInspeccion").show();
			}
		if(archivo == 0){
			$("#archivoInspeccion").hide();
		}else{
			$("#archivoInspeccion").show();
		}
		if(estado == 'Enviada' || estado == 'Atendida'){
			    if($.inArray("PFL_TECNIC_INT", perfil) >= 0 ){
    				$("#consumida").show(); 
    			}
			$("#fechaConsumo").hide();
			}else{
				$("#consumida").hide();
				$("#fechaConsumo").show();
				}
	 });

   	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");

		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				abrir($("#formulario"), event, false);
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			}, 25);
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});
 	$("#generar").click(function () {

		event.preventDefault();
		var error = false;
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		
		$("#cargarMensajeTemporal").html("<div id='cargando' >Cargando...</div>");
      	 $.post("<?php echo URL ?>InspeccionMusaceas/resultadoInspeccion/generarReporteInspeccion", 
                 {
      		id_solicitud_inspeccion: $("#id_solicitud_inspeccion").val(),
                 }, function (data) {
                 	if (data.estado === 'EXITO') {
                 		 mostrarMensaje(data.mensaje, data.estado);
                     }else{
                    	 mostrarMensaje(data.mensaje, "FALLO");
	                     }
                 	$("#cargarMensajeTemporal").html("");
           }, 'json');
	});
	
</script>
