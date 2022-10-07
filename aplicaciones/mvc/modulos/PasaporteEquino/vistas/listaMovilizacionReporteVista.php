<header>
	<nav><?php echo $this->panelBusquedaMovilizacionReporte;?></nav>
</header>

<script type="text/javascript">
var combo = "<option>Seleccione....</option>";

	$(document).ready(function () {
		construirValidador();
	});

    $("#idProvinciaFiltro").change(function () {
    	$("#provinciaFiltro").val("");
    	$("#idCantonFiltro").html(combo);
    	$("#cantonFiltro").val("");
    	$("#idParroquiaFiltro").html(combo);
    	$("#parroquiaFiltro").val("");
    	
        if ($(this).val !== "") {
            fn_cargarCantones();
            $("#provinciaFiltro").val($("#idProvinciaFiltro option:selected").text());
        }
    }); 

    $("#idCantonFiltro").change(function () {
    	$("#cantonFiltro").val("");
    	$("#idParroquiaFiltro").html(combo);
    	$("#parroquiaFiltro").val("");
    	
        if ($(this).val !== "") {
            fn_cargarParroquias();
            $("#cantonFiltro").val($("#idCantonFiltro option:selected").text());
        }
    });

    $("#idParroquiaFiltro").change(function () {
    	$("#parroquiaFiltro").val("");
    	
    	if ($(this).val !== "") {
            $("#parroquiaFiltro").val($("#idParroquiaFiltro option:selected").text());
        }
    });

    $("#idTipoProductoFiltro").change(function () {
    	$("#idSubtipoProductoFiltro").html(combo);
    	
        if ($(this).val !== "") {
        	buscarSubtipoProducto();
        }
    });

    $("#idOperacionFiltro").change(function () {
    	$("#operacionFiltro").val("");
    	
    	if ($(this).val !== "") {
            $("#operacionFiltro").val($("#idOperacionFiltro option:selected").text());
        }
    });

    $("#fechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+30);	 
      		$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val());
      		$('#fechaFin').datepicker('option', 'maxDate', fecha);
	    }
	 });

	$("#fechaFin").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
	    }
	 });

    //Lista de cantones por provincia
    function fn_cargarCantones() {
        var idProvincia = $("#idProvinciaFiltro option:selected").val();
        
        if (idProvincia !== "" && idProvincia !== "Todas") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/comboCantones/" + idProvincia, function (data) {
                $("#idCantonFiltro").removeAttr("disabled");
                $("#idCantonFiltro").html(data);               
            });
        }else{
        	$("#idCantonFiltro").html(combo);
        }
    }

    //Lista de parroquias por cantón
	function fn_cargarParroquias() {
        var idCanton = $("#idCantonFiltro option:selected").val();
        
        if (idCanton !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/comboParroquias/" + idCanton, function (data) {
                $("#idParroquiaFiltro").removeAttr("disabled");
                $("#idParroquiaFiltro").html(data);               
            });
        }
    }

	//Función para buscar el subtipo de producto
    function buscarSubtipoProducto(){
    	var idTipoProducto = $("#idTipoProductoFiltro option:selected").val();
            		
    	if (idTipoProducto !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Reportes/comboSubtipoProductoXTipo/" + idTipoProducto, function (data) {
                $("#idSubtipoProductoFiltro").removeAttr("disabled");
                $("#idSubtipoProductoFiltro").html(data);               
            });
        }
    }
	
</script>