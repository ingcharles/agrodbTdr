<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<fieldset id="seccionTramitesIngresado" name="seccionTramitesIngresado">
	<legend>Trámites Ingresados</legend>
	
	<div>
		<input id = "seleccionarTodosIngresado" type = "checkbox" />
		<label >Seleccionar todos </label>
	</div>
	
	<div id="tablaTramitesIngresado" style="width:100%"> </div>
	
	<div data-linea="1">
			<button type="button" class="mas" id="btnIngresado">Añadir</button>
		</div>
</fieldset>

<fieldset id="seccionTramitesSeguimiento" name="seccionTramitesSeguimiento">
	<legend>Trámites Seguimiento</legend>
	
	<div>
		<input id = "seleccionarTodosSeguimiento" type = "checkbox" />
		<label >Seleccionar todos </label>
	</div>
	
	<div id="tablaTramitesSeguimiento" style="width:100%"> </div>
	
	<div data-linea="1">
			<button type="button" class="mas" id="btnSeguimiento">Añadir</button>
		</div>
</fieldset>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='tramites/generarBitacora' data-destino="detalleItem" method="post">
	<input type="hidden" name="id" id="id">

    <fieldset>
    	<legend>Bitácora a imprimir</legend>
    		<div data-linea="1">
    				<table id="tbItems" style="width:100%">
    					<thead>
    						<tr>
    							<th style="width: 30%;">No. Registro</th>
                                <th style="width: 15%;">Fecha</th>
                                <th style="width: 55%;">Unidad destino</th>
    						</tr>
    					</thead>
    					<tbody>
    					</tbody>
    				</table>
    		</div>	
    		
        	<div data-linea="2">
    			<button type="submit" class="exportar" id="btnImpBitacora">Imprimir</button>
    		</div>	
    </fieldset>	
</form>

<script type="text/javascript">
	$(document).ready(function() {
		fn_mostrarTramitesIngresados();
		fn_mostrarTramitesSeguimiento();
		
		construirValidador();
		distribuirLineas();
		$("#estado").html("");
	 });

  //Para cargar los trámites en estado Ingresado
    function fn_mostrarTramitesIngresados() {
        var estadoTramite = "'Ingresado'";
        
    	$.post("<?php echo URL ?>SeguimientoDocumental/Tramites/construirTramitesCheck/" + estadoTramite, function (data) {
            $("#tablaTramitesIngresado").html(data);
        });
    }

    $('#btnIngresado').click(function(){
		var idItem = 0;
        var selected = '';
        var result = [];
        var i = 0;
                 
        $('#tablaTramitesIngresado input[type=checkbox]').each(function(){
            if (this.checked) {
            	var result = [];
            	i = 0;
            	
            	idItem = $(this).val();
            	
				$(this).closest('td').siblings().each(function(){
					result[i] = $(this).text();
					selected += result[i];//
                	++i;
                });

                agregar(idItem, result);
            }            
        }); 

        if (selected == ''){ 
            alert('Debe seleccionar al menos un trámite.');
        }

        return false;
    });

  //Para cargar los trámites en estado Seguimiento
    function fn_mostrarTramitesSeguimiento() {
        var estadoTramite = "'Seguimiento'";
        
    	$.post("<?php echo URL ?>SeguimientoDocumental/Tramites/construirTramitesCheck/" + estadoTramite, function (data) {
            $("#tablaTramitesSeguimiento").html(data);
        });
    }

    $('#btnSeguimiento').click(function(){
		var idItem = 0;
        var selected = '';
        var result = [];
        var i = 0;
                 
        $('#tablaTramitesSeguimiento input[type=checkbox]').each(function(){
            if (this.checked) {
            	var result = [];
            	i = 0;
            	
            	idItem = $(this).val();
            	
				$(this).closest('td').siblings().each(function(){
					result[i] = $(this).text();
					selected += result[i];//
                	++i;
                });

                agregar(idItem, result);
            }            
        }); 

        if (selected == ''){ 
            alert('Debe seleccionar al menos un trámite.');
        }

        return false;
    });

    function agregar(idItem, datos){
    	$("#estado").html("");
    	$(".alertaCombo").removeClass("alertaCombo");

        var error = false;
    	var duplicado = false;
    		
    	var nombre=idItem;
    	var datoTramite = datos;

    	if(nombre==""){
        	alert('error');
    		error=true;	
    	}
    	
    	if(!error){
    		$('#tbItems tbody tr').each(function (rows){
    	        var rd=$(this).find('td').eq(0).find('input[id="dtxtItem"]').val();
    			
    			if(rd == nombre){
    				duplicado=true;	
    				$("#estado").html("Ya ha agregado uno o más de los trámites seleccionados.").addClass('alerta');
    		    	return false;
    	    	}
    		});

    	    if(!duplicado){
    			var cadena= '<tr>'+
                  				'<td style="width: 30%;">'+datoTramite[0]+'<input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'+nombre+'"></td>'+
                  				'<td style="width: 15%;">'+datoTramite[1]+'</td>'+
        						'<td style="width: 20%;">'+datoTramite[2]+'</td>'+
    						'</tr>';

    		    	$("#tbItems tbody").append(cadena);
    				$("#estado").html("");
    	    	}
    	} else{
    		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
    	}
    }

	$("#formulario").submit(function (event) {
		event.preventDefault();

		$("#estado").html("");
		
		var error = false;

		if (($("#dtxtItem").length > 0)){
			error = false;
		}else{
			error = true;
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_filtrar_default();

	            $("#id").val(respuesta.contenido);
				$($(this)).attr('data-opcion', 'tramites/mostrarReporte');
				abrir($(this),event,false);
	        }
		} else {
			$("#estado").html("Ingrese por lo menos un trámite para generar la bitácora.").addClass("alerta");
		}
	});

	$("#seleccionarTodosIngresado").click(function(e){
		if($('#seleccionarTodosIngresado').is(':checked')){
			$('.registroActivarIngresado').prop('checked', true);
		}else{
			$('.registroActivarIngresado').prop('checked', false);
		}
	});

	$("#seleccionarTodosSeguimiento").click(function(e){
		if($('#seleccionarTodosSeguimiento').is(':checked')){
			$('.registroActivarSeguimiento').prop('checked', true);
		}else{
			$('.registroActivarSeguimiento').prop('checked', false);
		}
	});
</script>