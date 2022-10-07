<header>
	<nav><?php echo $this->panelBusquedaReporte;?></nav>
</header>

<script>
    $(document).ready(function () {
    	construirValidador();
    });

    $("#fechaInicioFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicioFiltro').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+90);	 
      		$('#fechaFinFiltro').datepicker('option', 'minDate', $("#fechaInicioFiltro" ).val());
      		$('#fechaFinFiltro').datepicker('option', 'maxDate', fecha);
	    }
	 });

	$("#fechaFinFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicioFiltro').datepicker('getDate')); 
	    }
	 });

</script>