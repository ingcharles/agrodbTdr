<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$qProcesoConciliacion = $cb -> listadoRegistroProcesoConciliacion($conexion);

//$qBancosProcesoConciliacion = $cb -> listadoBancosRegistroProcesoConciliacion($conexion);

$identificadorUsuario = $_SESSION['usuario'];

$fechaActual = date( 'YmdHis');

?>

<header>
	<h1>Nueva Conciliación</h1>
</header>

	<form id="nuevoProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria">
	
	<input type="hidden" id="opcionConciliacion" name="opcionConciliacion" value="procesoConciliacion" />
	<input type="hidden" id="identificadorOperador" value=<?php echo $identificadorOperador;?> name="identificadorOperador">
	
		<fieldset id="procesoConciliacion">
				<legend>Proceso de Conciliación</legend>
				<div data-linea="1">
					<label>Proceso Conciliación:</label>
						<select id="idRegistroProcesoConciliacion" name="idRegistroProcesoConciliacion" >
							<option value="">Seleccione...</option>
							<?php 
								while ($procesoConciliacion = pg_fetch_assoc($qProcesoConciliacion)){
									echo '<option value="'.$procesoConciliacion['id_registro_proceso_conciliacion'].'" >'. $procesoConciliacion['nombre_registro_proceso_conciliacion'] .'</option>';
								}
							?>
						</select>
				</div>
				<hr>
				
		</fieldset>
		<div data-linea="2" id="documentosConciliacion">

				</div>

		<div>
			<button type="submit" class="guardar" id="guardar">Analizar</button>
		</div>
	</form>
	
	
<script type="text/javascript">			

var array_bancos = <?php echo json_encode($bancos);?>;
				
    $(document).ready(function(){	
    	distribuirLineas();	    
    });

    $("#idRegistroProcesoConciliacion").change(function (event){
    	if($.trim($("#idRegistroProcesoConciliacion").val())!=""){
    		$("#nuevoProcesoConciliacion").attr('data-destino', 'documentosConciliacion');
    	    $("#nuevoProcesoConciliacion").attr('data-opcion', 'combosProcesoConciliacion');
    		abrir($("#nuevoProcesoConciliacion"), event, false);
    	}
    });

    function cuantosDias(m, y) {
  	  if (m == "Febrero") {
  	    return 28;
  	  } else if (m == "Enero" || m == "Marzo" || m == "Mayo"|| m == "Julio"|| m == "Agosto"|| m == "Octubre"|| m == "Diciembre"){
  	    return 31;
  	  }else{
  		return 30;
  	  	  }
  	}
  	
  	function asignaDias() {

  	  comboDias = $(".diaProcesoConciliacion");
  	  comboMeses = $(".mesProcesoConciliacion").val();   // esto realmente no se necesita
  	  comboAnyos = $(".anioProcesoConciliacion").val();  // esto realmente no se necesita

  	  dias_index = $('option:selected', '.diaProcesoConciliacion').index();

  	  Month = $('option:selected', '.mesProcesoConciliacion').text();
  	  Year = $('option:selected', '.anioProcesoConciliacion').text();

  	  diasEnMes = cuantosDias(Month, Year);
  	  diasAhora = comboDias.find("option").length - 1;  // calcula el numero de dias

  	  if (diasAhora > diasEnMes) {
  	    comboDias.find("option").each(function() { 
  	      if ($(this).attr("value") > diasEnMes)
  	        $(this).remove();
  	    });
  	  }
  	  if (diasEnMes > diasAhora) {
  	    for (i = diasAhora+1; i <= diasEnMes ; i++) {
  	      comboDias.append('<option value="' + i + '">' + i + "</option>");
  	    }
  	  }

  	  if (dias_index < 0)
  	    dias_index = 0;
  	}

  	
  	function botones (id){

  		var fechaActual = <?php echo json_encode($fechaActual); ?>;
  		
  		var result = id.split('o');
		result = result[1];
		
		nombreDocumento = $("#nombreDocumento"+result).val();
		formatoDocumento = $("#formatoDocumento"+result).val();
		cargarArchivos('#'+id,nombreDocumento,'_'+fechaActual+'_');
		
		
	    function cargarArchivos(button,numero,documento){
	    	var numero = numero;
	    	var usuario = <?php echo json_encode($identificadorUsuario); ?>;

	        var boton = $(button);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");

	       if (extension[extension.length - 1].toUpperCase() == formatoDocumento.toUpperCase()) {       	
	        		subirArchivo(
	    	                archivo
	    	                , usuario+documento+numero
	    	                , boton.attr("data-rutaCarga")
	    	                , rutaArchivo	                
	    	                , new carga(estado, archivo, $("#no"))
	    	            );     
	        		$(archivo).removeClass("alertaCombo");
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato ' + formatoDocumento);
	            archivo.val("");
	        }
	    }
  	 }

   $("#nuevoProcesoConciliacion").submit(function(){
 	 	event.preventDefault();

 	    $(".alertaCombo").removeClass("alertaCombo");
 	  	var error = false;
   
    	if($("#idRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#idRegistroProcesoConciliacion").addClass("alertaCombo");
		}

    	if($("#anioProcesoConciliacion").val()==""){
			error = true;
			$("#anioProcesoConciliacion").addClass("alertaCombo");
		}

       	if($("#mesProcesoConciliacion").val()==""){
			error = true;
			$("#mesProcesoConciliacion").addClass("alertaCombo");
		}

       	if($("#diaProcesoConciliacion").val()==""){
			error = true;
			$("#diaProcesoConciliacion").addClass("alertaCombo");
		}

    	if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$('#nuevoProcesoConciliacion').attr('data-opcion','guardarNuevoProcesoConciliacion');
			$('#nuevoProcesoConciliacion').attr('data-destino','detalleItem');					
			abrir($("#nuevoProcesoConciliacion"),event,false);                           
		}
		
    });

 
</script>
	