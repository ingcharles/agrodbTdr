<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/detalleModeloAdministrativo' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Creación de Proceso Administrativo</legend>				

	<div data-linea="1">
			<label for="provincia">Provincia: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getProvincia();?></span>
		</div>				

		<div data-linea="2">
			<label for="area_tecnica">Área Técnica: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getAreaTecnica();?></span>
		</div>				

	   <div data-linea="3" id="NumProceso">
			<label for="numero_proceso">Número del Expediente: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNumeroProceso(); ?></span>
		</div>	
		
		<div data-linea="4">
			<label for="nombre_accionado">Nombre del Accionado: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreAccionado(); ?></span>
		</div>				

		<div data-linea="5">
			<label for="nombre_establecimiento">Nombre del Establecimiento: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreEstablecimiento(); ?></span>
		</div>				

	</fieldset >
	<fieldset id="field1">
		<legend>Tipo de Documento Jurídico</legend>				

		<div data-linea="1">
			<label for="tipo_documento">Tipo de Documento: </label>
			<select id="tipo_documento" name="tipo_documento" >
				<?php echo $this->comboModeloAdministrativo();?>
			</select>
		</div>				
      
	</fieldset >
	    <div data-linea="8" id="">
			<button type="button" class="guardar" id="generarDocumento" >Generar Documento</button>
		</div>
	<fieldset >
		<legend>Tipo Documento Jurídico Agregado</legend>	
		<div id="listaTipoDocumento" style="width:100%"><?php echo $this->listarTipoDocumento($this->modeloProcesoAdministrativo->getIdProcesoAdministrativo())?></div>
	</fieldset>
	<input type="hidden" name="id_tipo_documento" id="id_tipo_documento">
</form >
<form id='formDescarga' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/descargaModeloJuridico' data-destino="rutaDescar"  method="post">
<input type="hidden" name="id" id="id">
<div id="rutaDescar"></div>
</form>
<script type ="text/javascript">
var opcion = <?php echo json_encode($this->opcion); ?>;
var idProcesoAdministrativo = <?php echo json_encode($this->modeloProcesoAdministrativo->getIdProcesoAdministrativo()); ?>;

	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		if(opcion=='editar'){
		  inactivarInputs();
		}else{
			$("#field1").hide();
			$("#NumProceso").hide();
			}
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	function inactivarInputs(){
			$("#provincia").prop("disabled", true);
			$("#area_tecnica").prop("disabled", true); 
			$("#nombre_accionado").prop("disabled", true); 
			$("#nombre_establecimiento").prop("disabled", true); 
			$("#buttonGuardar").hide();
			$("#NumProceso").show();
		}

	 //Cuando selecciona el modelo
    $("#generarDocumento").click(function() {
    	$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
    	if($("#tipo_documento").val() != ''){
            $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/ProcesoAdministrativo/generarDocumento", 
    				{
    				    id_modelo_administrativo: $("#tipo_documento").val(),
    				    id_proceso_administrativo: idProcesoAdministrativo
    				}, function (data) {
    					if (data.estado === 'EXITO') {
                   		$("#listaTipoDocumento").html(data.contenido);
                   		$("#id").val(data.rutaArch);
        				abrir($("#formDescarga"),event,false);
   	                   	 mostrarMensaje(data.mensaje, data.estado);
   	                     $("#tipo_documento").val('');
   	                     distribuirLineas();
                       } else {
                       	mostrarMensaje(data.mensaje, "FALLO");
                           $("#listaTipoDocumento").html(data.paciente);
                           $("#tipo_documento").addClass("alertaCombo");
                           distribuirLineas();
                       }
            }, 'json');
          //  abrir($("#formDescarga"),event,false);
            }else {
            	mostrarMensaje("Debe seleccionar un tipo documento", "FALLO");
            	$("#tipo_documento").addClass("alertaCombo");
                }
	    
	});

	function detalleModeloAdministrativo(id){
		event.preventDefault();
		$("#id_tipo_documento").val(id);
		abrir($("#formulario"), event, false);
	}
	// eliminar subtipos agregados
    function eliminarModeloAdministrativo(id){
        $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/ProcesoAdministrativo/eliminarModeloAdministrativo", 
                {
        	       id_tipo_documento: id,
        	       id_proceso_administrativo: idProcesoAdministrativo
	  		         		  		     
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		$("#listaTipoDocumento").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
        }, 'json');

     }
</script>
