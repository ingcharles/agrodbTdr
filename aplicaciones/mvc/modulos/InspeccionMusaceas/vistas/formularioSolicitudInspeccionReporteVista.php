<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='resultadoInspeccion/guardarResultado' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
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
	
</form >
<script type ="text/javascript">
var resultado = <?php echo ($this->modeloResultadoInspeccion->getResultado()!= NULL)?json_encode($this->modeloResultadoInspeccion->getResultado()):0;?>;
var archivo = <?php echo ($this->modeloSolicitudInspeccion->getRutaArchivo()!= NULL)?json_encode($this->modeloSolicitudInspeccion->getRutaArchivo()):0;?>;
   	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("", "FALLO");
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
	 });

	
</script>
