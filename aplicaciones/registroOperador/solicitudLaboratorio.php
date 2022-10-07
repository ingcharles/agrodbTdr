
<button type="button" id="labSolicitud">Crear solicitud laboratorio</button>
<script type="text/javascript">

$(document).ready(function(){
	$('#labSolicitud').click(function(){
		$.post("aplicaciones/mvc/laboratorios/solicitudes/aplicacion/app1/475", function(data){
			console.log(data);
			});	
});
});
</script>
<?php

?>