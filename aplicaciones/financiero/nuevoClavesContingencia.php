<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorFinanciero.php';
    
    $cf = new ControladorFinanciero();
    $conexion = new Conexion();
    
    $identificadorUsuario = $_SESSION['usuario'];
    
    $fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));
    
    $fechaInicioPeriodo = strtotime ( '+1 day' , strtotime ($fechaVigente['fecha_hasta'])) ;
    $fechaInicioPeriodo = date ( 'Y/m/d' , $fechaInicioPeriodo );
?>
<!DOCTYPE html>
<html>
<head>
<meta charset = "utf-8">
</head>
<body>

<header>
    <h1>Claves de contingencia</h1>
</header>

<div id = "estado"></div>

<form id = "nuevoClaveContingencia" data-rutaAplicacion = "financiero" data-opcion = "guardarNuevoClaveContingencia" data-destino = "detalleItem" data-accionEnExito='ACTUALIZAR'>

	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="<?php echo $identificadorUsuario;?>" />
	<input type="hidden" id="idActualClaveContingencia" name="idActualClaveContingencia" value="<?php echo $fechaVigente['id_clave_contingencia'];?>" />
	
    <fieldset>
        <legend>Detalle de vigencia</legend>
        
        <div data-linea = "1">
	        <label>Fecha inicio</label> 
				<input type="text"	id="fechaDesde" name="fechaDesde" /> 
        </div>
        
         <div data-linea="1">
            <label>Hora inicio</label>
            <input id="horaDesde" name="horaDesde" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" />
        </div>
        
        <div data-linea = "2">
	        <label>Fecha fin</label> 
				<input type="text"	id="fechaHasta" name="fechaHasta" /> 
        </div>
        
         <div data-linea="2">
            <label>Hora fin</label>
            <input id="horaHasta" name="horaHasta" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" />
        </div>
        
        <div data-linea="3">
			<label>Observación</label> 
				<input type="text" id="observacion" name="observacion" data-er="^[A-Za-z0-9.,/ ]+$" />
		</div>
      
        <div data-linea = "5">
            <button id = "submit" type = "submit" class = "guardar">Guardar</button>  
        </div>
    </fieldset>
</form>
</body>
<script type = "text/javascript">

	var fecha_vigente= <?php echo json_encode($fechaInicioPeriodo); ?>;

	$("document").ready(function () {
	    distribuirLineas();
	    construirValidador();	    
	});

	 $("#fechaDesde").datepicker({
	    	changeMonth: true,
	    	changeYear: true,
	    	minDate: new Date(fecha_vigente),
	    	onSelect: function(dateText, inst) {
	    		 $('#fechaHasta').datepicker('option', 'minDate', $("#fechaDesde" ).val()); 
	        } 
	  	});
	
	$("#fechaHasta").datepicker({
    	changeMonth: true,
    	changeYear: true
  	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
    
	$("#nuevoClaveContingencia").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#fechaDesde").val()=="" ){
			error = true;
			$("#fechaDesde").addClass("alertaCombo");
		}

		if($("#fechaHasta").val()==""){
			error = true;
			$("#fechaHasta").addClass("alertaCombo");
		}

		if($("#horaHasta").val()==""){
			error = true;
			$("#horaHasta").addClass("alertaCombo");
		}

		if($("#horaDesde").val()==""){
			error = true;
			$("#horaDesde").addClass("alertaCombo");
		}

		if($("#observacion").val()=="" || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (!error){
			$("#estado").html("").removeClass('alerta');
			ejecutarJson(this);
		}else{
			$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
		}
	});

</script>
</html>