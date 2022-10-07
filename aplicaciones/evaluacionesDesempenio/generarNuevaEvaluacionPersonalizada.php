<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';


$conexion= new Conexion();
$ced= new ControladorEvaluacionesDesempenio();
$cc= new ControladorCatalogos();


$data =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
list($idPuesto,$idAplicanteIndividual,$idEvaluacion,$idEvaluado) = explode("-", $data);

$identificador = $_SESSION['usuario'];

$qListarFunciones=$cc->ListarFuncionesXPuesto($conexion, $idPuesto);

$ced->actualizarAplicanteIndividualFechaInicio($conexion, $idAplicanteIndividual);

?>

<header>
	<h1>Evaluación por funciones</h1>
</header>

<div id="estado"></div>

<form id="evaluacionPersonalizada" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="guardarEvaluacionPersonalizada"> <!--  data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"-->
	<input type = "hidden" name="idEvaluacion" value="<?php echo $idEvaluacion?>"></input>
	<input type = "hidden" name="idEvaluado" value="<?php echo $idEvaluado?>"></input>
	<fieldset>
		<legend>Seleccionar funciones, indicador y meta</legend>
			<table id="tValorCumplido">
				<thead>
					<tr>
			    		<th>#</th>
			    		<th>Descripción</th>
			    		<th>Indicador</th>
			   			<th>S/N</th>			    
			    		<th>Meta</th>
			    		<th>Cum.</th>
			    		<th>Total</th>
			    	</tr>
				</thead>
	
					<?php 
					
					//$contador=1;
					while($preguntasFunciones = pg_fetch_assoc($qListarFunciones)){
						echo '<tbody><tr id = '.$preguntasFunciones['id_funcion'].'>
									<td>'.++$contador.'</td>
									<td align="justify">'.$preguntasFunciones['descripcion'].'</td>
									<td><textarea style="width:150px; height: 60px;" id="indi_'.$preguntasFunciones['id_funcion'].'" ></textarea></td>
									<td align="center"><input type = "checkbox" id="c_'.$preguntasFunciones['id_funcion'].'"></td>
									<td><input type = "text" style="width:100%" id="meta_'.$preguntasFunciones['id_funcion'].'" ></input></td>
									<td><input type = "text" style="width:100%" id="cumpli_'.$preguntasFunciones['id_funcion'].'" ></input></td>
									<td><input type = "text" style="width:100%" id="total_'.$preguntasFunciones['id_funcion'].'" readonly="readonly" ></input></td>
					    </tr></tbody>';
					}
					?>

		</table>
	</fieldset>

	<p><button type="submit">Enviar evaluación</button></p>

</form>


<script type="text/javascript">



$('#tValorCumplido tbody tr').each(function(){  
	funcion = $(this).attr('id');	
	$('#meta_'+funcion+'').numeric();
	$('#cumpli_'+funcion+'').numeric();
});

$("input[type='checkbox']").change(function(){

	/*var valorMETA = 0;
	var valorCUMPLI = 0;
	var valorINDI = 0;
	var total = 0;*/

	var funcion = $(this).attr('id').split('_');
	
	if($(this).is(':checked')){

		$('#indi_'+funcion[funcion.length-1]+'').removeClass("alertaCombo");		
		$('#meta_'+funcion[funcion.length-1]+'').removeClass("alertaCombo");		
		$('#meta_'+funcion[funcion.length-1]+'').attr("disabled","disabled");
		$('#cumpli_'+funcion[funcion.length-1]+'').attr("disabled","disabled");
		$('#indi_'+funcion[funcion.length-1]+'').attr("disabled","disabled");
		$('#total_'+funcion[funcion.length-1]+'').attr("disabled","disabled");

		$('#meta_'+funcion[funcion.length-1]+'').val("");
		$('#cumpli_'+funcion[funcion.length-1]+'').val("");
		$('#indi_'+funcion[funcion.length-1]+'').val("");
		$('#total_'+funcion[funcion.length-1]+'').val("");

	}else{
		
		$('#meta_'+funcion[funcion.length-1]+'').removeAttr("disabled");
		$('#cumpli_'+funcion[funcion.length-1]+'').removeAttr("disabled");
		$('#indi_'+funcion[funcion.length-1]+'').removeAttr("disabled");
	}
});


$("input[type='text']").change(function(){

	var valorMETA = 0;
	var valorCUMPLI = 0;
	var total = 0;
	
	var funcion = $(this).attr('id').split('_');
	
	valorMETA = Number($('#meta_'+funcion[funcion.length-1]+'').val());
	valorCUMPLI = Number($('#cumpli_'+funcion[funcion.length-1]+'').val());
	if(valorCUMPLI>valorMETA)valorCUMPLI=valorMETA;

	total = Math.round((valorCUMPLI*100)/valorMETA);

	if(valorCUMPLI>valorMETA || valorMETA==0 ){		
			$('#total_'+funcion[funcion.length-1]+'').val("");
			$('#cumpli_'+funcion[funcion.length-1]+'').val("");
	}else{
		
	$('#total_'+funcion[funcion.length-1]+'').val(total);

	}	
	
});


$("#evaluacionPersonalizada").submit(function(event){
	event.preventDefault();

	var error = false;

	
	$(".alertaCombo").removeClass("alertaCombo");

	$banderaFuncion=false;
	
	$('#tValorCumplido tbody tr').each(function(){  

		var funcion = '';

		var aplicanteIndividual = "<?php echo $idAplicanteIndividual; ?>" ;
		//alert (aplicanteIndividual);
		funcion = $(this).attr('id');

		var indicadorFUNCION= $('#indi_'+funcion+'').val();
		var valorMETA = Number($('#meta_'+funcion+'').val());
		var valorCUMPLI = Number($('#cumpli_'+funcion+'').val());
		var total = Number($('#total_'+funcion+'').val());

		if($('#c_'+funcion+'').is(":checked") ==false ) {

			if (($('#indi_'+funcion+'').val()!="" || $('#indi_'+funcion+'').val()!=0) && ($('#meta_'+funcion+'').val()!="" || $('#meta_'+funcion+'').val()!=0)){

				$("#evaluacionPersonalizada").append("<input name='valorIngresado[]' value='"+aplicanteIndividual+'&'+funcion+'&'+indicadorFUNCION+'&'+valorMETA+'&'+valorCUMPLI+'&'+total+"' type='hidden'>");
		
				//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
				//abrir($("evaluacionPersonalizada"),event,false);
			}else{			

				error=true;
				if(indicadorFUNCION=="" || indicadorFUNCION==0){
					
				$('#indi_'+funcion+'').addClass("alertaCombo");		
						
				}

				if(valorMETA=="" || valorMETA==0){

					$('#meta_'+funcion+'').addClass("alertaCombo");		

				}
								
			}
			
		}
	
	});


	if (error){
		event.preventDefault();
		
		$("#estado").html("Revise la información ingresada").addClass('alerta');
		
	}else{

	abrir($(this),event,false);
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		$("#estado").html("").removeClass('alerta');
		
		
		
	}
	

});



</script>













