<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
?>

<style>

input[type="text"], select {
     width: 100%; 
     box-sizing: border-box;
     -webkit-box-sizing:border-box;
     -moz-box-sizing: border-box;
}

</style>

<header>
    <nav>
        <form id="reporteRegistroOperadorLeche" action="aplicaciones/reportes/generarReporteRegistroOperadorLeche.php" data-rutaAplicacion='reportes' target="_blank" method="post">
			<input type="hidden" name="opcion" id="opcion" />
            <table class="filtro">
                <tbody>
                <tr>
                    <th>Tipo reporte</th>
                    <td>
                    <select id="tipoReporte" name="tipoReporte" required>
					<option value="">Seleccione....</option>
					<option value="individual">Individual</option>
					<option value="consolidado">Consolidado</option>					
					</select>
					 <td>	 
                </tr>                
                <tr>
                    <th>Provincia</th>
                    <td>
	                    <select id="provincia" name="provincia" required>
						<option value="">Seleccione....</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
								echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
							}
						?>
						</select> 
					</td>
                </tr>
                <tr id="fechaInicial">
                    <th>Fecha inicial</th>
                    <td><input name="fechaInicio" id="fechaInicio" type="text" required></td>
                </tr>
                <tr id="fechaFinal">
                    <th>Fecha final</th>
                    <td><input name="fechaFin" id="fechaFin" type="text" required></td>
                </tr>
                <tr>
                    <th>Reporte</th>
                    <td> 
                    	<select id="tipoOperacion" name="tipoOperacion" required>
                    	<option value="">Seleccione....</option>
						<option value="AI-ACO">Centros de Acopio</option>
						<option value="AI-MDT">Medios de Transporte</option>				
						</select> </td>
                </tr>
                <tr id="FtipoProducto">
                    <th>Tipo Producto</th>
                    <td> 
                    	<select id="tipoProducto" name="tipoProducto">
                    	<option value="">Seleccione....</option>
						<?php 
						  $qTipoProducto = $cc->listarTipoProductosXarea($conexion, 'AI');
						  while($tipoProducto = pg_fetch_assoc($qTipoProducto)){
						      echo '<option value="'.$tipoProducto['id_tipo_producto'].'">'.$tipoProducto['nombre'].'</option>'; 
						      
						  }
						?>				
						</select>
					</td>
                </tr>
                <tr id="FsubtipoProducto">
                    <th>Subtipo Producto</th>
                    <td> 
                    	<div id="resultadoTipoProducto">
                        	<select id="subtipoProducto" name="subtipoProducto">
                        	<option value="">Seleccione....</option>
    						</select> 
						</div>
					</td>
                </tr>
                <tr id="Fproducto">
                    <th>Producto</th>
                    <td> 
                    	<div id="resultadosubtipoProducto">
                        	<select id="producto" name="producto">
                        	<option value="">Seleccione....</option>
    						</select> 
						</div>
					</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
                    </td>
                </tr>
                </tbody>             
            </table>
        </form>
    </nav>
</header>

<script>
						
    $(document).ready(function () {

		$("#fechaInicial").hide();
		$("#fechaFinal").hide();

		$("#FtipoProducto").hide();
		$("#FsubtipoProducto").hide();
		$("#Fproducto").hide();	
    	
    	$("#fechaInicio").datepicker({
    	    changeMonth: true,
    	    changeYear: true,
    	    dateFormat: "yy-mm-dd",
    	    onSelect: function(dateText, inst) {
    	    	var fecha = $("#fechaInicio").datepicker("getDate");
        	    fecha.setDate(fecha.getDate() + 30);
        	    $('#fechaFin').datepicker('option', 'minDate', fecha);        	    
    	    }
    	}).datepicker('setDate', new Date());

    	$("#fechaFin").datepicker({
    	    changeMonth: true,
    	    changeYear: true,
    	    dateFormat: "yy-mm-dd"//,
    	    //maxDate: "+3M" ,
    	    	//numberOfMonths: 1   	    
    	}).datepicker('setDate', new Date());
    	
    });

	$("#tipoProducto").change(function(event){

		event.preventDefault();
		event.stopImmediatePropagation();
		
		if($("#tipoProducto") != ""){
    		 $('#reporteRegistroOperadorLeche').attr('data-opcion','accionesRegistroOperadorLeche');
    		 $('#reporteRegistroOperadorLeche').attr('data-destino','resultadoTipoProducto');
    		 $('#opcion').val('tipoProducto');
    		 abrir($("#reporteRegistroOperadorLeche"),event,false);	
	 	 }
	 	 
	 });

    $("#tipoReporte").change(function(event){
    	if($("#tipoReporte").val() == "individual"){
	    	$("#fechaInicial").show();
			$("#fechaFinal").show();

    		$("#FtipoProducto").hide();
    		$("#FsubtipoProducto").hide();
    		$("#Fproducto").hide();	
				
    	}else{
    		$("#fechaInicial").hide();
    		$("#fechaFinal").hide();

    		$("#FtipoProducto").show();
    		$("#FsubtipoProducto").show();
    		$("#Fproducto").show();	
    		
        }
    });
    
</script>
