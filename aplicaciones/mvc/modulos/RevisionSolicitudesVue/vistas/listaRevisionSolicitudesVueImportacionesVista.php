
<header>
	<nav><?php echo $this->panelBusqueda;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>Tipo de Certificado	</th>
			<th>País</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});


	//Cuando se presiona en Filtrar lista, debe cargar los datos
    $("#btnFiltrar").click(function () {
        
    	var error = false;
        $(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "EXITO");

		if(!$.trim($("#id_vue").val())){
			error = true;
			$("#id_vue").addClass("alertaCombo");
		}

		if(!error){
			fn_filtrar();
		}
		
	});
	 		
	    function fn_filtrar() {

			  $("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

			  $.post("<?php echo URL ?>Importaciones/Importaciones/listarImportacion",
		    	{
		        	id_vue: $('#id_vue').val()
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
</script>
