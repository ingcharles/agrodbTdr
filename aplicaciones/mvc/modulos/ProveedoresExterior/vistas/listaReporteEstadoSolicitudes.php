<header>
	<nav><?php echo $this->panelBusquedaProveedoresExteriorReporte;?></nav>
</header>

<script type="text/javascript">

    $(document).ready(function () {
    	construirValidador();
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
      		$('#fechaFin').datepicker('setDate', fecha);
	    }
	}).datepicker("setDate", new Date());

	$("#fechaFin").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
	    }
	}).datepicker("setDate", new Date());
   
</script>