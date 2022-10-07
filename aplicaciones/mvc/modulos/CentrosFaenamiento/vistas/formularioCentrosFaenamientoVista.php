<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CentrosFaenamiento' data-opcion='centrosFaenamiento/guardarRegistros' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">

	<button type="button" class="editar">Modificar</button>
	<button type="submit" class="guardar">Actualizar</button>
	
	<input type="hidden" id="id_centro_faenamiento" name="id_centro_faenamiento" value="<?php echo $this->modeloCentrosFaenamiento->getIdCentroFaenamiento(); ?>"/>
	<input type="hidden" id="identificador_operador" name="identificador_operador" value="<?php echo $this->modeloCentrosFaenamiento->getIdentificadorOperador(); ?>"/>
	<input type="hidden" id="especie" name="especie" value="<?php echo $this->modeloCentrosFaenamiento->getEspecie(); ?>"/>
	<input type="hidden" id="id_operador_tipo_operacion" name="id_operador_tipo_operacion" value="<?php echo $this->modeloCentrosFaenamiento->getIdOperadorTipoOperacion(); ?>"/>
	<input type="hidden" id="id_sitio" name="id_sitio" value="<?php echo $this->modeloCentrosFaenamiento->getIdSitio(); ?>"/>
	<input type="hidden" id="id_area" name="id_area" value="<?php echo $this->modeloCentrosFaenamiento->getIdArea(); ?>"/>

	<fieldset>
		<legend>Datos Centro de Faenamiento</legend>
        <div data-linea="1">
			<label>RUC: </label><span><?php echo $this->modeloCentrosFaenamiento->getIdentificadorOperador();?></span>
		</div>	
		<div data-linea="2">
			<label>Razón social: </label><span><?php echo $this->modeloCentrosFaenamiento->getRazonSocial();?></span>
		</div>	
		<div data-linea="3">
			<label>Provincia: </label><span><?php echo $this->modeloCentrosFaenamiento->getProvincia();?></span>
		</div>				
		<div data-linea="4">
			<label>Especie: </label><span><?php echo $this->modeloCentrosFaenamiento->getEspecie();?></span>
		</div>	
		<div data-linea="5">
			<label>Código de registro: </label>			
			<input type="text" id="codigo" name="codigo" value="<?php echo $this->modeloCentrosFaenamiento->getCodigo(); ?>"	placeholder="Código" required maxlength="10" />

		</div>	
		<div data-linea="6">
			<label for="tipo_centro_faenamiento">Tipo Centro Faenamiento: </label>
				<select id="tipo_centro_faenamiento" name= "tipo_centro_faenamiento" required>
            		<?php 
            		echo $this->comboTipoCentroFaenamiento($this->modeloCentrosFaenamiento->getTipoCentroFaenamiento());
                    ?>
        		</select>
		</div>	
		<div data-linea="7">
			<label for="criterio_funcionamiento">Criterio funcionamiento: </label>
				<select id="criterio_funcionamiento" name= "criterio_funcionamiento" >
            		<?php 
            		echo $this->comboCentrosFaenamientoAdministracion($this->modeloCentrosFaenamiento->getCriterioFuncionamiento());
                    ?>
        		</select>
		</div>			
		<div data-linea="8" id="tipoHabilitacionDiv">
			<label for="tipo_habilitacion">Tipo de habilitación: </label>
				<select id="tipo_habilitacion" name="tipo_habilitacion" >
            		<?php 
            		echo $this->comboTipoHabilitacion($this->modeloCentrosFaenamiento->getTipoHabilitacion());
                    ?>
        		</select>
		</div>				
		<div data-linea="9" id="listCantonProvincia">
			<?php echo $this->listarCantonProvincia;?>
		</div>
		<div data-linea="10">
			<label for="observacion">Observación: </label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloCentrosFaenamiento->getObservacion(); ?>"	placeholder="criterio de funcionamiento" required maxlength="2048" />
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
var provincia = <?php echo json_encode($this->modeloCentrosFaenamiento->getProvincia());?>;
var criterioFuncionamiento = <?php echo json_encode($this->modeloCentrosFaenamiento->getCriterioFuncionamiento());?>;
	$(document).ready(function() {
		fn_restricciones();
		fn_limpiar();
		distribuirLineas();
		$("input[name='cantonProvincia[]']").map(function(){ $(this).prop("disabled",true)}).get();
		$("#selectCanProv").prop("disabled",true);
		
		if(criterioFuncionamiento == 'Habilitado' || criterioFuncionamiento == 'Activo'){
			$("#tipoHabilitacionDiv").show();
		}else{
			$("#tipoHabilitacionDiv").hide();
			}
	 });
	//*************formulario de envio*************************************
	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#codigo").val())){
			   $("#codigo").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#tipo_centro_faenamiento").val())){
			   $("#tipo_centro_faenamiento").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#criterio_funcionamiento").val())){
			   $("#criterio_funcionamiento").addClass("alertaCombo");
			   error = true;
		}else{
			if($("#criterio_funcionamiento").val() == 'Habilitado' || $("#criterio_funcionamiento").val() == 'Activo'){
				if(!$.trim($("#tipo_habilitacion").val())){
					   $("#tipo_habilitacion").addClass("alertaCombo");
					   error = true;
				}
			}
		}
		if (!error) {
			$("#tipo_habilitacion").attr('disabled', false);
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	//***************** función de restricciones**************************
	function fn_restricciones() {
		$(".guardar").attr('disabled','disabled');
		$("#observacion").attr('disabled','disabled');
		$("#criterio_funcionamiento").attr('disabled','disabled');
		$("#codigo").attr('disabled',true);
		$("#tipo_centro_faenamiento").attr('disabled',true);
		$("#tipo_habilitacion").attr('disabled',true);
		
	}
	//******************boton modificar************************************
	$(".editar").click(function(){
		$(".editar").attr('disabled','disabled');
		$(".guardar").removeAttr('disabled');
		$("#criterio_funcionamiento").removeAttr('disabled');
		$("#observacion").removeAttr('disabled');
		$("#codigo").attr('disabled',false);
		$("#tipo_centro_faenamiento").attr('disabled',false);
		$("#tipo_habilitacion").attr('disabled',false);
		$("input[name='cantonProvincia[]']").map(function(){ $(this).prop("disabled",false)}).get();
		$("#selectCanProv").prop("disabled",false);
		 if(criterioFuncionamiento == 'Activo'){
			  $("#tipo_habilitacion").attr('disabled', true);
		 }
	});

	//**************************modificaco 05-12-2020**************************** 
	 $("#tipo_habilitacion").change(function () {
		  if($("#tipo_habilitacion").val() == 'Intercantonal' || $("#tipo_habilitacion").val() == 'Interprovincial'){
			  $("#listCantonProvincia").show();
		  $.post("<?php echo URL ?>CentrosFaenamiento/centrosFaenamiento/listarCantonProvincias", 
	              {
			         tipo_habilitacion:$("#tipo_habilitacion").val(),
			         provincia:provincia,
			         id_centro_faenamiento:$("#id_centro_faenamiento").val()
		  		         		  		     
	              }, function (data) {
	              	if (data.estado === 'EXITO') {
	              		    $("#listCantonProvincia").html(data.contenido);
	              		    $("#listCantonProvincia").show();
		                    mostrarMensaje(data.mensaje, data.estado);
		                    distribuirLineas();
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
		  }else{
			  $("input[name='cantonProvincia[]']").map(function(){ $("input[name='cantonProvincia[]']").prop("checked",false)}).get();
			  $("#listCantonProvincia").hide();
		  }
	     }); 
	 	//**************************modificaco 04-05-2021**************************** 
		 $("#criterio_funcionamiento").change(function () {
			  if($("#criterio_funcionamiento").val() == 'Habilitado'){
		           $("#tipoHabilitacionDiv").show();
		           $("#tipo_habilitacion").attr('disabled', false);
		           $("#tipo_habilitacion").val('');
			  }else if($("#criterio_funcionamiento").val() == 'Activo'){
				  $("#tipoHabilitacionDiv").show();
				  $("#tipo_habilitacion").val('Cantonal');
				  $("#tipo_habilitacion").attr('disabled', true);
				  $("input[name='cantonProvincia[]']").map(function(){ $("input[name='cantonProvincia[]']").prop("checked",false)}).get();
				  $("#listCantonProvincia").hide();
			  }else{
				  $("#tipoHabilitacionDiv").hide();
				  $("#listCantonProvincia").hide();
			  }
		  }); 
	function activarDesactivar(id){
		
		if($(id).prop('checked')){
			$("input[name='cantonProvincia[]']").map(function(){ $(this).prop("checked",true)}).get();
		}else{
			$("input[name='cantonProvincia[]']").map(function(){ $(this).prop("checked",false)}).get();
		}
	}


	//************************modificación 26.05.2021*************
	 $("#codigo").change(function () {
		 mostrarMensaje('', "FALLO");
		 $(".alertaCombo").removeClass("alertaCombo");
		  if($("#codigo").val() != ''){
    		  $.post("<?php echo URL ?>CentrosFaenamiento/centrosFaenamiento/validarCodigo", 
    	              {
    			         codigo:$("#codigo").val()
    	              }, function (data) {
    	              	if (data.estado === 'EXITO') {
    	              		$(".alertaCombo").removeClass("alertaCombo");
    	                  } else {
    	                  	mostrarMensaje(data.mensaje, "FALLO");
    	                  	$("#codigo").attr('placeholder',$("#codigo").val());
    	                  	$("#codigo").val('');
    	                  	$("#codigo").addClass("alertaCombo");;
    	                  }
    	      }, 'json');
		  }
	     }); 
	
</script>
