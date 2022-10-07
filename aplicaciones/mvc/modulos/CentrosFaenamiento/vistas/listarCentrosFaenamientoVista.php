<header>
	<h1>Información</h1>
	<nav><?php 
	if(in_array('PFL_ADM_CF_PC', $this->perfilUsuario)){
	    echo $this->panelBusquedaPC;
	}else{
	    echo $this->panelBusqueda;
	}
	?></nav>	
</header>
<script src="<?php echo URL ?>modulos/CentrosFaenamiento/vistas/js/centroFaenamiento.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>Código de registro</th>
		<th>RUC</th>
		<th>Razón social</th>
		<th>Sitio</th>
		<th>Área</th>
		<th>Provincia</th>
		<th>Criterio</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
var perfil = <?php echo json_encode($this->perfilUsuario);?>;
	$(document).ready(function () {
		fn_limpiar_detalle();
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		fn_restricciones();
	});

//*******************************************************************
    $("#btnFiltrar").click(function () {

        var error = true;
        $(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
        
    	if($("#identificadorFiltro").val() == '' && !esCampoValidoExp("#identificadorFiltro",3) ){
        	error = false;
        	$("#identificadorFiltro").addClass("alertaCombo");
    		fn_mensajes(1);	
    	}
    	 if($.inArray("PFL_ADM_CF_PC", perfil) >= 0 ){
        	if($("#provincia").val() == ''){
            	error = false;
            	$("#provincia").addClass("alertaCombo");
        		fn_mensajes(1);	
        	}
    	}

    	if(error){
    		fn_limpiar();
    		fn_filtrar();
    	}

    	
	});

//*************** Función para filtrar******************************
	function fn_filtrar() {
		  $("#paginacion").html("<div id='cargando'>Cargando...</div>");
		  $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		  $.post("<?php echo URL ?>CentrosFaenamiento/CentrosFaenamiento/listarCentroFaenamientoPorIdentificador",
	    	{
	        	identificadorOperador: $("#identificadorFiltro").val(),
	        	provincia:$("#provincia option:selected").text()
	        },
	      	function (data) {
	        	if (data.estado === 'FALLO') {
                    construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                    mostrarMensaje(data.mensaje, "FALLO");
                } else {
                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                }
	        }, 'json');
	    }
//***************** Función para restricciones en campos***********************
	function fn_restricciones() {
		 $("#identificadorFiltro").numeric();
         $("#identificadorFiltro").attr('maxlength', 13);
	}

</script>
