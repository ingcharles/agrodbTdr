<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='solicitudInspeccion/guardarSolicitud' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
    <?php echo $this->datosGenerales($this->operador);?>
     
     <fieldset>
   		<legend>Datos de inspección</legend>				
    	<div data-linea="1">
			<label for="lugar_inspeccion">Lugar de inspección: </label>
			<select id="lugar_inspeccion" name= "lugar_inspeccion" >
        		<?php echo $this->comboInspeccion();?>
        	</select>
		</div>				

		<div data-linea="1">
			<label for=nombre_inspeccion>Nombre: </label>
			<input type="text" id="nombre_inspeccion" name="nombre_inspeccion" value=""
			placeholder="Nombre lugar de producción"  maxlength="50" />
		</div>				

		<div data-linea="2">
			<label for="representante_tecnico">Representante técnico: </label>
			<input type="text" id="representante_tecnico" name="representante_tecnico" value=""
			placeholder="Representante técnico"  maxlength="50" />
		</div>	
		<div data-linea="2">
			<label for="celular_inspeccion">Celular: </label>
			<input type="text" id="celular_inspeccion" name="celular_inspeccion" value=""
			placeholder="Celular"  maxlength="10" />
		</div>			
		<div data-linea="3" id="inspector_externo_autorizado_div">
			<label for="identificador_inspeccion_externa">Inspector externo autorizado: </label>
			<select id="identificador_inspeccion_externa" name= "identificador_inspeccion_externa" >
        		<?php echo $this->operadorExterno;?>
        	</select>
		</div>	
    </fieldset>
    <fieldset>
    	<legend>Productores</legend>	
    	<div data-linea="1">
		    <input  name="codigo[]" type="radio"  value="mag" onChange="limpiar(); "><span> Código MAG</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="codigo[]" type="radio"  value="acopio" onChange="limpiar(); "><span> Centro de Acopio</span>
		</div>				
    	<div data-linea="2">
			<input type="text" id="codigoBusqueda" name="codigoBusqueda" placeholder="Ingresar código de busqueda"/>
			<button type="button" id="buscarProductor">Buscar</button>
		</div>
		<hr>
		<div id="resultado" style="width:100%"></div>
    </fieldset>
    <fieldset>
    	<legend>Productores Agregados</legend>				
    	<div id="listaProductores" style="width:100%"></div>
    </fieldset>
    <fieldset>
    	<legend>Datos de Exportación</legend>				
    		<div data-linea="1">
			<label for="producto">Producto: </label>
			<select id="producto" name= "producto" >
        		<?php echo $this->comboProductos();?>
        	</select>
		</div>				

		<div data-linea="1">
			<label for="marca">Marca: </label>
			<input type="text" id="marca" name="marca" value=""
			placeholder="Marca"  maxlength="30" />
		</div>				

		<div data-linea="2">
			<label for="tipo_produccion">Tipo producción: </label>
			<select id="tipo_produccion" name= "tipo_produccion" >
        		<?php echo $this->comboTipoProduccion();?>
        	</select>
		</div>				

		<div data-linea="2">
			<label for="viaje">Viaje: </label>
			<input type="text" id="viaje" name="viaje" value="<?php echo $this->modeloSolicitudInspeccion->getProducto(); ?>"
			placeholder="viaje"  maxlength="20" />
		</div>				

		<div data-linea="3">
			<label for="pais_destino">País destino: </label>
			<select name="pais_destino" id="pais_destino" >
							<?php echo $this->cargarPaises();?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="puerto_embarque">Puerto de Embarque: </label>
			<select name="puerto_embarque" id="puerto_embarque">
			<?php echo $this->cargarPuertos(66);?>
    		</select>
		</div>				

		<div data-linea="4">
			<label for="nombre_vapor">Nombre de Vapor: </label>
			<input type="text" id="nombre_vapor" name="nombre_vapor" value="<?php echo $this->modeloSolicitudInspeccion->getViaje(); ?>"
			placeholder="Nombre de Vapor"  maxlength="30" />
		</div>				

    </fieldset>
    <fieldset>
   		<legend>Requisitos para exportación</legend>				
    	<div id="listaRequisitos" style="width:100%"></div>
    </fieldset>
    

        <div id="cargarMensajeTemporal" ></div>
		<div data-linea="15">
			<button type="submit" class="guardar">Enviar solicitud</button>
		</div>
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		 $("input[name='numCajas[]']").numeric();
		 $("#celular_inspeccion").numeric();
		// $("#inspector_externo_autorizado_div").hide();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");

		if ( $("input[name='numCajas[]']").length == 0 ) {
			 texto = "Debe agregar un productor !!.";
			 error = true;
		}
		$("input[name='numCajas[]']").each(function() {
            if(!$.trim($(this).val()) && $("input[name='numCajas[]']").length > 0){
            	texto = "Debe agregar una cantidad !!.";
            	$(this).addClass("alertaCombo");
     			error = true;
            }
		});
		if(!$.trim($("#producto").val())){
  			   $("#producto").addClass("alertaCombo");
  			   error = true;
  		  }
		if(!$.trim($("#marca").val())){
			   $("#marca").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#tipo_produccion").val())){
			   $("#tipo_produccion").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#viaje").val())){
			   $("#viaje").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#pais_destino").val())){
			   $("#pais_destino").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#puerto_embarque").val())){
			   $("#puerto_embarque").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#nombre_vapor").val())){
			   $("#nombre_vapor").addClass("alertaCombo");
			   error = true;
		  }

		if(!$.trim($('#lugar_inspeccion').val())){
			$("#lugar_inspeccion").addClass("alertaCombo");
			   error = true;
         }
		 if($.trim($('#lugar_inspeccion').val()) && $('#lugar_inspeccion').val() != 'Puerto'){
			 if(!$.trim($('#nombre_inspeccion').val())){
					$("#nombre_inspeccion").addClass("alertaCombo");
					   error = true;
		         }
          }
		if(!$.trim($("#representante_tecnico").val())){
			   $("#representante_tecnico").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#celular_inspeccion").val())){
			   $("#celular_inspeccion").addClass("alertaCombo");
			   error = true;
		  }
		if (!error) {
			$("input[name='numCajas[]']").each(function() {
				$(this).val($(this).attr('id')+'.'+$(this).val());
			});
			mostrarMensaje('Solicitud enviada','EXITO');
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed;'>Cargando...</div>").fadeIn();
			$("#lugar_inspeccion").attr('disabled', false);
			setTimeout(function(){
				abrir($("#formulario"), event, false);
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			}, 250);
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});

	 function limpiar(){
			 $("#codigoBusqueda").val('');		
	 };
	  // busqueda de productor
    $("#buscarProductor").click(function () {
 		    event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			var opcion =  $("input[name='codigo[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		  	 
			if(!$.trim($("#codigoBusqueda").val())){
	  			   $("#codigoBusqueda").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$("input[name='codigo[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un campo !!.";
	  			$("input[name='codigo[]']").addClass("alertaCombo");
	  			 error = true;
		  		}

	  		if(opcion[0] == 'acopio'){
	  			if(!esCampoValidoExp("#codigoBusqueda",2)){
	    			error = true;
	    			$("#codigoBusqueda").addClass("alertaCombo");
	    			texto ='No posee el formato correcto...';
				}
	  		}else{
	  			if(!esCampoValidoExp("#codigoBusqueda",1)){
	    			error = true;
	    			$("#codigoBusqueda").addClass("alertaCombo");
	    			texto ='No posee el formato correcto...';
				}
		  	}
			   
			if (!error) {
				 $("#cargarMensajeTemporal").html("<div id='cargando' >Cargando...</div>");
			 	  $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/buscarProductor", 
		                  {
			  		         codigoBusqueda:$("#codigoBusqueda").val(),
			  		         opcion: opcion[0]
			  		         
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#resultado").html(data.contenido);
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                    	$("#resultado").html('');
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		                  	$("#cargarMensajeTemporal").html("");
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
         });
	function agregarProductor(id){
		var texto = "Debe seleccionar un lugar de inspección...!!";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
		if(!$.trim($('#lugar_inspeccion').val())){
			$("#lugar_inspeccion").addClass("alertaCombo");
			   error = true;
         }

		if (!error) {
		      $("#cargarMensajeTemporal").html("<div id='cargando' style='height:100%'>Cargando...</div>");
		 	  $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/agregarProductor", 
	                  {
		  		         id:id,
		  		         lugarInspeccion: $("#lugar_inspeccion").val()
		  		         
	                  }, function (data) {
	                  	if (data.estado === 'EXITO') {
	                  		$("#lugar_inspeccion").attr('disabled', true);
	                  		$("#listaProductores").html(data.contenido);
		                    mostrarMensaje(data.mensaje, data.estado);
		                    distribuirLineas();
		                    $("input[name='numCajas[]']").map(function(){ $(this).numeric();}).get();
	                      } else {
	                      	mostrarMensaje(data.mensaje, "FALLO");
	                      }
	                  	$("#cargarMensajeTemporal").html("");
	          }, 'json');
        	} else {
        		mostrarMensaje(texto, "FALLO");
        	}
		
	}
	function eliminarProductor(id){
		 $("#cargarMensajeTemporal").html("<div id='cargando' style='height:100%'>Cargando...</div>");
	 	  $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/eliminarProductor", 
                {
	  		         id:id
	  		         
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		$("#listaProductores").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
	                    if (data.validar === 'vacio') {
	                    	$("#lugar_inspeccion").attr('disabled', false);
	                    }
                    } else {
                  	$("#listaProductores").html('');
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
              $("#cargarMensajeTemporal").html("");
        }, 'json');
	
}
	  
    function esCampoValidoExp(elemento,exp){ 
    	if(exp==0)var patron = new RegExp($(elemento).attr("data-er"),"g");
    	if(exp==1)var patron = new RegExp("^([a-zA-Z0-9]{1,15})$");
    	if(exp==2)var patron = new RegExp("^([0-9]{10,13}).([0-9]{8})$");
       	return patron.test($(elemento).val());
        }

  //paises
    $("#pais_destino").change(function () {
        if($('#pais_destino').val() != ''){
     if($('#producto').val() != ''){
  	 $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/buscarRequisitos", 
             {
         		idPais: $('#pais_destino').val(),
         		producto: $('#producto').val()
             }, function (data) {
             	if (data.estado === 'EXITO') {
                     $("#listaRequisitos").html(data.contenido);
                 } 
       }, 'json');
      }
     
       }
    });
//productos
    $("#producto").change(function () {
        if($('#producto').val() != ''){
         if($('#pais_destino').val() != ''){
      	 $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/buscarRequisitos", 
                 {
             		idPais: $('#pais_destino').val(),
             		producto: $('#producto').val()
                 }, function (data) {
                 	if (data.estado === 'EXITO') {
                         $("#listaRequisitos").html(data.contenido);
                     } 
           }, 'json');
          }
       }
    });
  //lugar_inspeccion
    $("#lugar_inspeccion").change(function () {
        if($('#lugar_inspeccion').val() != ''){
        	$("#nombre_inspeccion").val('');
        	$("#inspector_externo_autorizado").val('');
         if($('#lugar_inspeccion').val() != 'Puerto'){
				$("#nombre_inspeccion").attr('disabled',false);
				$("#inspector_externo_autorizado_div").show();
             }else{
                 $("#nombre_inspeccion").attr('disabled',true);
                 $("#inspector_externo_autorizado_div").hide();
             }
     
       }
    });
</script>
