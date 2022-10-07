<header>
	<nav><?php echo ($this->menu == 'emisionPasaporte'?$this->panelBusquedaEmisionPasaporte:($this->menu == 'liberacionTraspaso'?$this->panelBusquedaLiberacionTraspaso:($this->menu == 'deceso'?$this->panelBusquedaDeceso:'')));?></nav>
	<nav><?php echo ($this->menu == 'emisionPasaporte'?$this->crearAccionBotones():''); //print_r($this->menu);?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Pasaporte</th>
			<th>Nombre Equino</th>
			<th>Nombre Predio</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
var menu = <?php echo json_encode($this->menu); ?>;
var combo = "<option>Seleccione....</option>";

    $(document).ready(function () {
    	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
    	$("#listadoItems").removeClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
    });

    $("#idProvinciaMiembroFiltro").change(function () {
    	$("#idMiembroFiltro").val(combo);
    	$("#pasaporteFiltro").val('');
    	
        if ($("#estadoFiltro option:selected").val() != "") {
        	fn_cargarMiembrosXProvincia(); 	
        }
    });

  	//Lista de miembros de la asociación por provincia
	function fn_cargarMiembrosXProvincia() {
        var idProvincia = $("#idProvinciaMiembroFiltro option:selected").val();
        
        if (idProvincia !== '') {
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboMiembrosXProvincia/", 
			{
        		idProvincia : idProvincia
			},
            function (data) {
                $("#idMiembroFiltro").html(data);
            });
        }else{
        	$("#idMiembroFiltro").val(combo);
        }
    }
    
    $("#btnFiltrar").click(function (event) {
    	event.preventDefault();
    	fn_filtrar();
    });

  	//Función para realizar la búsqueda de equinos con los parámetros de búsqueda especificados
	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;

		switch (menu){
    		case 'emisionPasaporte':
    			if(!$.trim($("#idProvinciaMiembroFiltro").val())){
    				$("#idProvinciaMiembroFiltro").addClass("alertaCombo");
    				error = true;
    			}
    			break;
    
    		case 'liberacionTraspaso':
    			if(!$.trim($("#pasaporteFiltro").val())){
    				$("#pasaporteFiltro").addClass("alertaCombo");
    				error = true;
    			}
    			break;
    
    		case 'deceso':
    			if(!$.trim($("#idProvinciaMiembroFiltro").val())){
    				$("#idProvinciaMiembroFiltro").addClass("alertaCombo");
    				error = true;
    			}
    			break;
		}

		
        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>PasaporteEquino/Equinos/listarEquinosFiltrados",
		    	{
				  idProvinciaMiembroFiltro: $("#idProvinciaMiembroFiltro").val(),
				  idMiembroFiltro: $("#idMiembroFiltro").val(),
				  pasaporteFiltro: $("#pasaporteFiltro").val(),
				  menu: <?php echo json_encode($this->menu); ?>
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                }
		        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}		
	}

    function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>