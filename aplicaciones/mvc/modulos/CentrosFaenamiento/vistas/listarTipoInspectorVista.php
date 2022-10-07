<header>
	<h1>Información</h1>
	<nav><?php  echo $this->panelBusquedaCentro;?></nav>
</header>
<script src="<?php echo URL ?>modulos/CentrosFaenamiento/vistas/js/centroFaenamiento.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>#</th>
		<th>RUC / CI</th>
		<th>Nombre</th>
		<th>Provincia</th>		
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		fn_restricciones();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").removeClass("comunes");
	});

    $("#btnFiltrar").click(function () {
    	var tipo = $('input:radio[name=tipo]:checked').val();
		 
	     if(tipo == 'ruc'){
	    	 if($("#identificadorFiltro").val() !== ''  &&  $("#identificadorFiltro").val().length == 13 && esCampoValidoExp("#identificadorFiltro",1)){
	    	    	fn_limpiar();
	    			fn_filtrar();
	    	 }else{
	    			$("#identificadorFiltro").addClass("alertaCombo");
	        		fn_mensajes(2);
	    	 }
	     }else{
	    	 if($("#identificadorFiltro").val() !== ''  &&  $("#identificadorFiltro").val().length == 10 && esCampoValidoExp("#identificadorFiltro",2)){
	    	    	fn_limpiar();
	    			fn_filtrar();
	    	 }else{
	    		    $("#identificadorFiltro").addClass("alertaCombo");
	     		    fn_mensajes(3);
		    	 }
	     }
	});

 // Función para filtrar
	function fn_filtrar() {
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	    $.post("<?php echo URL ?>CentrosFaenamiento/TipoInspector/listarTipoInspector",
	    	{
	        	identificador_operador: $("#identificadorFiltro").val()
	        },
	      	function (data) {
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });
	    }
//***************** Función para validaciones***********************
	function fn_restricciones() {
		 $("#identificadorFiltro").numeric();
		 $("#identificadorFiltro").attr('maxlength', 13);
	}
//****************************************************************
   $("#busqueda1").click(function () {
	   $("#identificadorFiltro").attr('maxlength', 13);
	   $("#identificadorFiltro").val("");
	 });
 //****************************************************************
   $("#busqueda2").click(function () {
	   $("#identificadorFiltro").attr('maxlength', 10);
	   $("#identificadorFiltro").val("");
	 });
 //****************************************************************
</script>
