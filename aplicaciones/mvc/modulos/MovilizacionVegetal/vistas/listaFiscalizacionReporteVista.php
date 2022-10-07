<header>
	<nav><?php echo $this->panelBusquedaFiscalizacionReporte;?></nav>
</header>

<script type="text/javascript">
var combo = "<option>Seleccione....</option>";

	$(document).ready(function () {
		construirValidador();
	});

    $("#idProvinciaFiltro").change(function () {
    	$("#idCantonFiltro").html(combo);
    	$("#idParroquiaFiltro").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarCantones();
            $("#provinciaFiltro").val($("#idProvinciaFiltro option:selected").text());
        }
    }); 

    $("#idCantonFiltro").change(function () {
    	$("#idParroquiaFiltro").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarParroquias();
            $("#cantonFiltro").val($("#idCantonFiltro option:selected").text());
        }
    });

    $("#idParroquiaFiltro").change(function () {
    	if ($(this).val !== "") {
            $("#parroquiaFiltro").val($("#idParroquiaFiltro option:selected").text());
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
        	$.post("<?php echo URL ?>MovilizacionVegetal/Fiscalizacion/comboCantones/" + idProvincia, function (data) {
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
        	$.post("<?php echo URL ?>MovilizacionVegetal/Fiscalizacion/comboParroquias/" + idCanton, function (data) {
                $("#idParroquiaFiltro").removeAttr("disabled");
                $("#idParroquiaFiltro").html(data);               
            });
        }
    }

</script>