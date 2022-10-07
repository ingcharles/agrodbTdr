<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='resultadoInspeccion/guardarResultado' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
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
   
    <fieldset>
   		<legend>Resultado de Inspección</legend>		
   		<div data-linea="1">
			<label for="lugar_inspeccion">Lugar de inspección: </label>
			<select name="lugar_inspeccion" id="lugar_inspeccion" >
				<?php echo $this->comboInspeccion();?>
			</select>
		</div>			
    	<div data-linea="2">
		    <input  name="resultado[]" type="radio"  id="apTotal"   value="apTotal" onclick="verificarOpcion(id);"><span> Aprobación total</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="resultado[]" type="radio"  id="apParcial" value="apParcial" onclick="verificarOpcion(id);"><span> Aprobación parcial</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="resultado[]" type="radio"  id="desapTotal" value="desapTotal" onclick="verificarOpcion(id);"><span> Desaprobación total</span>
		</div>				
    	<div data-linea="3">
			<label for="cantidad_aprobada">Cantidad aprobada: </label>
			<input type="text" id="cantidad_aprobada" name="cantidad_aprobada" value=""
			placeholder="Cantidad Aprobada"  maxlength="10" />
		</div>
		<div data-linea="4">
			<label for="num_contenedores">Nro. Contenedor: </label>
			<input type="text" id="num_contenedores" name="num_contenedores" value=""
			placeholder="Nro. Contenedor"  maxlength="60" />
		</div>
		<div data-linea="5">
			<label for="observacion">Observación: </label>
			<input type="text" id="observacion" name="observacion" value=""
			placeholder="Observación"  maxlength="300" />
		</div>
    </fieldset>
   <input type="hidden" id="id_solicitud_inspeccion" name="id_solicitud_inspeccion" value="<?php  echo $this->modeloSolicitudInspeccion->getIdSolicitudInspeccion();?>"/>
<input type="hidden" name="id" id="id">
        <div id="cargarMensajeTemporal"></div>
		<div data-linea="15" id="perfil">
			<button type="submit" class="guardar">Guardar</button>
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
		var resultado =  $("input[name='resultado[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		var check =  $("input[name='check[]']").map(function(){ if($(this).prop("checked")){return 1;}}).get();

		if(!$.trim($("#lugar_inspeccion").val())){
			   $("#lugar_inspeccion").addClass("alertaCombo");
			   error = true;
		  }
		if(!$("input[name='resultado[]']").is(':checked') ){
 			 texto = "Debe seleccionar un campo !!.";
 			$("input[name='resultado[]']").addClass("alertaCombo");
 			 error = true;
	  		}

		if(resultado == 'apParcial'){
    		if(!$.trim($("#cantidad_aprobada").val()) || !esCampoValidoExp("#cantidad_aprobada",2)){
    			   $("#cantidad_aprobada").addClass("alertaCombo");
    			   error = true;
    			   var texto = "Debe ingresar solo números..!!.";
    		  }else if($("#cantidad_aprobada").val() > totalCajas ) {
                   error = true;
                   var texto = 'La cantidad ingresada es mayor al total de cajas ingresadas...!!';
                   $("#cantidad_aprobada").addClass("alertaCombo");
   		                    
        		}
		}	
		if((resultado == 'apTotal' || resultado == 'apParcial' ) && check == ''){ 
			error = true;
			$("#listaProductores").addClass("alertaCombo");
			var texto = "Debe seleccionar los Items de los productores..!!.";
		}

		if(!$.trim($("#num_contenedores").val())){
			   $("#num_contenedores").addClass("alertaCombo");
			   error = true;
			   var texto = "Por favor revise los campos obligatorios.";
		  }
		if(!$.trim($("#observacion").val())  ){
			   $("#observacion").addClass("alertaCombo");
			   error = true;
			   var texto = "Por favor revise los campos obligatorios.";
		  }
		
		if (!error) {
			if(resultado == 'apTotal'){ 
				$("input[name='check[]']").map(function(){ $(this).prop("checked",true)}).get();
			}
			$("#cantidad_aprobada").removeAttr('disabled');
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);
				if (respuesta.estado == 'exito'){
					abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
					$("#id").val(respuesta.contenido);
					$("#formulario").attr('data-opcion', 'resultadoInspeccion/visualizar');
					abrir($("#formulario"),event,false);
				}	
			}, 1000);
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});

	   function verificarOpcion(id){
		    mostrarMensaje("", "FALLO");
		    $(".alertaCombo").removeClass("alertaCombo");
		    var seleccion = []; 
		    $("#cantidad_aprobada").val('');
		    $("#cantidad_aprobada").attr('disabled','disabled');
		   	if(id == 'apTotal'){
		   		$("input[name='check[]']").map(function(){ $(this).prop("checked",true)}).get();
			    $.post("<?php echo URL ?>InspeccionMusaceas/resultadoInspeccion/devolverTotal", 
		                 {
		             		idSolicitudInspeccion: idSolicitudInseccion,
		             		opcion:'total'
		                 }, function (data) {
		                 	if (data.estado === 'EXITO') {
		                         $("#cantidad_aprobada").val(data.contenido);
		                    } 
		           }, 'json');
		   	}else if(id == 'apParcial'){
		   		var check =  $("input[name='check[]']").map(function(){ if($(this).prop("checked")){return 1;}}).get();
			   	if(check != ''){
		   	       $("#cantidad_aprobada").removeAttr('disabled');
		   	       $("input[name='check[]']").map(function(){ if($(this).prop("checked")){seleccion.push($(this).val());} }).get();
		   	       $.post("<?php echo URL ?>InspeccionMusaceas/resultadoInspeccion/devolverTotal", 
		                 {
		             		idSolicitudInspeccion: idSolicitudInseccion,
		             		idCajas:seleccion,
		             	    opcion:'parcial'
		                 }, function (data) {
			               
		                 	if (data.estado === 'EXITO') {
		                 		totalCajas= data.contenido;
		                    } 
		           }, 'json');
			   	}else{
			   		$("#listaProductores").addClass("alertaCombo");
					var texto = "Debe seleccionar los Items de los productores..!!.";
					mostrarMensaje(texto, "FALLO");
					$("input[name='resultado[]']").map(function(){ $(this).prop("checked",false)}).get();
				   	}
			}else{
				 $("input[name='check[]']").map(function(){ $(this).prop("checked",false)}).get();
				}
	   }

		function limpiarResultado(id){
			$("input[name='resultado[]']").map(function(){ $(this).prop("checked",false)}).get();
			$("#cantidad_aprobada").val('');
		}
	   
	   function esCampoValidoExp(elemento,exp){ 
	    	if(exp==0)var patron = new RegExp($(elemento).attr("data-er"),"g");
	    	if(exp==1)var patron = new RegExp("^([a-zA-Z0-9]{1,15})$");
	    	if(exp==2)var patron = new RegExp("^([0-9]{1,10})$");
	       	return patron.test($(elemento).val());
	        }
</script>
