<?php echo $this->descripcionConfiguracionCronogramaVacaciones; ?>

<!-- <form id='frmVistaPreviaSolicitud' data-rutaAplicacion='dossierFertilizante' data-opcion='' >		
		<button id="btnVistaPreviaSolicitud" type="button" class="documento btnVistaPreviaSolicitud">Generar vista previa solicitud</button>
		<a id="verReporteSolicitud" href="" target="_blank" style="display:none">Ver archivo</a>
	</form> -->


<script type="text/javascript">
    $(document).ready(function() {
    	$("#estado").html("").removeClass('alerta');    	
		construirValidador();
        distribuirLineas();
    });
    
	
	
</script>

