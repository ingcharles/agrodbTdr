<header>
<nav><?php if($this->perfilUsuario == 'PFL_MEDICO'){
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();}
	?></nav>

</header>
<script src="<?php echo URL ?>modulos/HistoriasClinicas/vistas/js/funcionHC.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>Fecha de creación</th>
		<th>Tipo Documento</th>
		<th>Doc. Identidad</th>
		<th>Funcionario</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); });
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

		 // Función para filtrar
			function fn_filtrar() {
				$("#paginacion").html("<div id='cargando'>Cargando...</div>");
				  $.post("<?php echo URL ?>HistoriasClinicas/certificadoMedico/filtrarInformacion",
					    	{
					  identificadorFiltro: $("#identificadorFiltro").val(),
					  tipo:$('input:radio[name=tipo]:checked').val()
			        },
			      	function (data) {

				      	if(data.estado== 'EXITO'){
		                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
		                	mostrarMensaje('', "EXITO");
				      	}else{
				      		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
				      		mostrarMensaje(data.mensaje, "FALLO");
				      	}
			        }, 'json');
			}
</script>
