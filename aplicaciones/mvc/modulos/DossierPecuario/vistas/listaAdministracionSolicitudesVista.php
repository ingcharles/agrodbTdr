<header>
	<nav><?php echo $this->panelBusquedaAdministrador;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Expediente</th>
			<th>Nº Registro</th>
			<th>Producto</th>
			<th>Tipo solicitud</th>
			<th>Provincia</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
var combo = "<option>Seleccione....</option>";

    $(document).ready(function () {
    	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
    	$("#listadoItems").removeClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');

    	$(".provincia").hide();
    	$(".tecnico").hide();
    });

    $("#numeroTramiteFiltro").change(function () {
    	$("#estadoFiltro").val('');
    	$("#idProvinciaFiltro").val('');
    	$("#provinciaFiltro").val('');
    	$("#idProvinciaFiltro").attr('disabled', 'disabled');
    	$("#identificadorTecnicoFiltro").val('');
    	$("#identificadorTecnicoFiltro").attr('disabled', 'disabled');
    	$(".provincia").hide();
    	$(".tecnico").hide();
    });

    $("#estadoFiltro").change(function () {
    	$("#numeroTramiteFiltro").val('');
    	$("#idProvinciaFiltro").val('');
    	$("#idProvinciaFiltro").attr('disabled', 'disabled');
    	$("#provinciaFiltro").val('');
    	$(".provincia").hide();
    	$("#identificadorTecnicoFiltro").val('');
    	$("#identificadorTecnicoFiltro").attr('disabled', 'disabled');
    	$(".tecnico").hide();
    	
        if ($("#estadoFiltro option:selected").val() !== "") {
        	$("#idProvinciaFiltro").removeAttr('disabled');    
        	$(".provincia").show();  	
        }
    });

	$("#idProvinciaFiltro").change(function () {
		$("#provinciaFiltro").val('');
    	$("#identificadorTecnicoFiltro").val('');
    	$("#identificadorTecnicoFiltro").attr('disabled', 'disabled');
    	
        if ($("#idProvinciaFiltro option:selected").val() !== "") {
        	$("#provinciaFiltro").val($("#idProvinciaFiltro option:selected").text());
        	
        	if ($("#estadoFiltro option:selected").val() != "Recibido") {
        		fn_cargarTecnicosXProvincia();
        		$(".tecnico").show();
        	}
        }
    });

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	//Lista de técnicos con perfil de revisión de soliciudes RIA
	function fn_cargarTecnicosXProvincia() {
        var idProvincia = $("#idProvinciaFiltro option:selected").val();
        
        if (idProvincia !== '') {
        	$.post("<?php echo URL ?>DossierPecuario/AdministracionSolicitudes/comboTecnicosXProvincia/", 
			{
        		idProvincia : idProvincia
			},
            function (data) {
                $("#identificadorTecnicoFiltro").html(combo+data);
                $("#identificadorTecnicoFiltro").removeAttr('disabled');
            });
        }else{
        	$("#identificadorTecnicoFiltro").val('');
        	$("#identificadorTecnicoFiltro").attr('disabled', 'disabled');
        }
    }

	//Función para realizar la búsqueda de solicitudes con los parámetros de búsqueda especificados
	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;

		if($("#numeroTramiteFiltro").val() == ''){
			if(!$.trim($("#estadoFiltro").val())){
				$("#numeroTramiteFiltro").addClass("alertaCombo");
				$("#estadoFiltro").addClass("alertaCombo");
				error = true;
			}
		}	
        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>DossierPecuario/AdministracionSolicitudes/listarSolicitudesAdministracionFiltradas",
		    	{
				  numeroTramiteFiltro: $("#numeroTramiteFiltro").val(),
				  estadoFiltro: $("#estadoFiltro option:selected").val(),
				  idProvinciaFiltro: $("#idProvinciaFiltro option:selected").val(),
				  identificadorTecnicoFiltro: $("#identificadorTecnicoFiltro option:selected").val(),
				  filtro: 'administrador'
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