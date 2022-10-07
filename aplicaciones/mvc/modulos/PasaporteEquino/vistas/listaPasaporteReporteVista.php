<header>
	<nav><?php echo $this->panelBusquedaPasaporteReporte;?></nav>
</header>

<script>
var combo = "<option>Seleccione....</option>";

	$(document).ready(function () {
		construirValidador();
	});

    $("#idProvinciaFiltro").change(function () {
        
    	if ($("#idProvinciaFiltro option:selected").val != "") {
            fn_cargarCantones();
        }
    }); 

    $("#fechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+90);	 
      		$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val());
      		$('#fechaFin').datepicker('option', 'maxDate', fecha);
	    }
	 });//target="_blank"

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
        	$.post("<?php echo URL ?>PasaporteEquino/Reportes/comboCantonXPrediosOperacionesRegistradas/" + idProvincia, function (data) {
                $("#idCantonFiltro").removeAttr("disabled");
                $("#idCantonFiltro").html(data);               
            });
        }else{
        	$("#idCantonFiltro").html(combo);
        }
    }

    /*$("#filtrar").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#fechaInicio").val())){
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}
		
        if (!$.trim($("#fechaFin").val())) {
        	error = true;
			$("#fechaFin").addClass("alertaCombo");
        }
        
		if (!error) {
							
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);			

		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}				
	};*/
</script>