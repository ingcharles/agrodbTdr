<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datosProceso = $cpoa1->obtenerSubprocesoXProceso($conexion, $fecha['year']);

while($fila = pg_fetch_assoc($datosProceso)){
	$subprocesos[]= array(id_proceso=>$fila['id_proceso'], descripcion_proceso=>$fila['descripcion_proceso'], id_subproceso=>$fila['id_subproceso'], descripcion_subproceso=>$fila['descripcion_subproceso']);
}

/*$datosProceso2= $cpoa1->obtenerComponenteXProceso($conexion, $fecha['year']);
while($fila = pg_fetch_assoc($datosProceso2)){
	$objetivoComponente[]= array(id_proceso=>$fila['id_proceso'], descripcion_proceso=>$fila['descripcion'], codigo=>$fila['codigo'], descripcion_componente=>$fila['componente'],id_componente=>$fila['id_componente'] );
}*/

$datosProceso3= $cpoa1->obtenerActividadesXSubProceso($conexion, $fecha['year']);
while($fila = pg_fetch_assoc($datosProceso3)){
	$actividadesSubProceso[]= array(id_subproceso=>$fila['id_subproceso'], descripcion_subproceso=>$fila['sub_proceso'], descripcion_actividad=>$fila['descripcion_actividad'], id_actividad=>$fila['id_actividad']);
}

$res= $cpoa1->listarIndicadores($conexion, $fecha['year']);

while($fila = pg_fetch_assoc($res)){
	$indicadoresActividades[]= array(id_indicador=>$fila['id_indicador'], descripcion_indicador=>$fila['descripcion'], linea_base=>$fila['linea_base'], metodo_calculo=>$fila['metodo_calculo'], id_actividad=>$fila['id_actividad']);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<header>
	<h1>Nuevo Item Proforma</h1>


</header>
<form id="nuevoItemPOA" data-rutaAplicacion="poa" data-opcion="guardarNuevoPOA" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="usuario" value="<?php echo $_SESSION['usuario'];?>"/>
	<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>

	<fieldset>

		<legend>Objetivo Estratégico</legend>
			<div data-linea="1">
				<select id="listaObjetivoEstrategico" name="listaObjetivoEstrategico">
					<option value="">Seleccione un Objetivo estratégico</option>
					<?php 										
						$res= $cpoa1->listarObjetivosEstrategicos($conexion, $fecha['year']);
		
						while($fila = pg_fetch_assoc($res)){
					    	echo '<option value="' . $fila['id_objetivo'] . '">' . $fila['descripcion'] .'</option>';
						}
					?>
				</select>
			</div>
	</fieldset>

	<fieldset>

		<legend>Procesos/Proyectos</legend>
		<div data-linea="1">
			<select id="listaProcesos" name="listaProcesos">
				<option value="">Seleccione un Proceso/Proyecto</option>
				<?php 					
					$res= $cpoa1->listarProcesos($conexion, $fecha['year']);
	
					while($fila = pg_fetch_assoc($res)){
				    	echo '<option value="' . $fila['id_proceso'] . '">' . $fila['descripcion'] .'</option>';
					}
				?>
			</select>
		</div>
	</fieldset>

	<fieldset>

		<legend>Subprocesos</legend>
			<div data-linea="1">
				<select id="listaSubprocesos" name="listaSubprocesos" disabled="disabled">
					<option value="">Seleccione un Subproceso</option>
				</select>
			</div>
	</fieldset>
	
	<!-- fieldset>
		<legend>Objetivo operativo</legend>
		<div data-linea="1">
			<select id="listaComponentes" name="listaComponentes" disabled="disabled">
				<option value="">Seleccione un Componente</option>
			</select>
		</div>
		<input type="hidden" id="descripcionComponente" name="descripcionComponente"></input>
	</fieldset-->


	<fieldset>
		<legend>Actividades/Proyectos</legend>
			<div data-linea="1">
				<select id="listaActividades" name="listaActividades" disabled="disabled">
					<option value="">Seleccione una Actividad/Proyecto</option>
				</select>				
			</div>
			
			<input type="hidden" id="descripcionActividad" name="descripcionActividad"></input>
			
			<div id="dDetalle" data-linea="2">
				<label>Detalle</label>
					<input id="detalleActividad" name="detalleActividad"></input>
			</div>
			
			
			
	</fieldset>

	<!-- fieldset>
		<legend>Indicadores</legend>
			<div data-linea="1">
				<select id="listaIndicadores" name="listaIndicadores" disabled="disabled"></select>
			</div>
			
			<div id="lineaBase"></div>
			
			<div id="metodoCalculo"></div>
	</fieldset-->


	<!-- fieldset>
		<legend>Metas</legend>
		<div data-linea="1">
			<label>Trimestre I: </label>
				<input class="numeric" id="meta1" type="text" name="meta1" placeholder="0" data-er="^[0-9]+$"/>
		</div>
		
		<div data-linea="1">
			<label>Trimestre II: </label>
				<input class="numeric" id="meta2" type="text" name="meta2" placeholder="0" data-er="^[0-9]+$"/>
		</div>

		<div data-linea="2">
			<label>Trimestre III: </label>
				<input class="numeric" id="meta3" type="text" name="meta3" placeholder="0" data-er="^[0-9]+$"/>
		</div>

		<div data-linea="2">
			<label>Trimestre IV: </label>
				<input class="numeric" id="meta4" type="text" name="meta4" placeholder="0" data-er="^[0-9]+$"/>
		</div>
		
		<div data-linea="3">
			<label>Sumatoria: </label>
				<input class="numeric" id="total" type="text" name="total" disabled="disabled"/>
		</div>
		
	</fieldset-->
	<button type="submit" class="guardar">Crear Item Proforma</button>

</form>
</body>

<script type="text/javascript">

	var array_subprocesos= <?php echo json_encode($subprocesos); ?>;
	//var array_componentes= < ?php echo json_encode($objetivoComponente); ?>;
	var array_actividades= <?php echo json_encode($actividadesSubProceso); ?>;
	//var array_indicadores= < ?php echo json_encode($indicadoresActividades); ?>;
	
	$("#listaProcesos").change(function(){
		sresponsable ='0';
		sresponsable = '<option value="">Seleccione un Subproceso</option>';
	    for(var i=0;i<array_subprocesos.length;i++){
		   
		    if ($("#listaProcesos").val()==array_subprocesos[i]['id_proceso']){
		    	sresponsable += '<option value="'+array_subprocesos[i]['id_subproceso']+'">'+array_subprocesos[i]['descripcion_subproceso']+'</option>';
			    }
	   		}

	    $('#listaSubprocesos').html(sresponsable);
	    $('#listaSubprocesos').removeAttr("disabled");
	     
	    /*scomponentes ='0';
	    scomponentes = '<option value="">Seleccione un Componente</option>';
	   	   
	    for(var z=0;z<array_componentes.length;z++){
	     	   
		    if ($("#listaProcesos").val()==array_componentes[z]['id_proceso']){
			    scomponentes += '<option value="'+array_componentes[z]['id_componente']+'">'+array_componentes[z]['descripcion_componente']+'</option>';
			    }
	   		}
  
	    $('#listaComponentes').html(scomponentes);
	    $('#listaComponentes').removeAttr("disabled");*/
 
	 });

	/*$("#listaComponentes").change(function(){
		$('#descripcionComponente').val($("#listaComponentes option:selected").text());
	});*/

	$("#listaSubprocesos").change(function(){

		sactividades ='0';
	    sactividades = '<option value="">Seleccione una Actividad/Proyecto</option>';
	   	   
	    for(var z=0;z<array_actividades.length;z++){
	     	   
		    if ($("#listaSubprocesos").val()==array_actividades[z]['id_subproceso']){
		    	sactividades += '<option value="'+array_actividades[z]['id_actividad']+'">'+array_actividades[z]['descripcion_actividad']+'</option>';
			    }
	   		}
  	    $('#listaActividades').html(sactividades);
	    $('#listaActividades').removeAttr("disabled");
	    $('#dDetalle').show();
		
	});

	$("#listaActividades").change(function(){
		
		/*sIndicador ='0';
		sIndicador = '<option value="">Seleccione un Indicador</option>';*/

		$('#descripcionActividad').val($("#listaActividades option:selected").text());
	   	   
	    /*for(var z=0;z<array_indicadores.length;z++){
	     	   
		    if ($("#listaActividades").val()==array_indicadores[z]['id_actividad']){
		    	sIndicador += '<option value="'+array_indicadores[z]['id_indicador']+'" data-base="'+array_indicadores[z]['linea_base']+'" data-calculo="'+array_indicadores[z]['metodo_calculo']+'">'+array_indicadores[z]['descripcion_indicador']+'</option>';
			    }
	   		}
  	    $('#listaIndicadores').html(sIndicador);
	    $('#listaIndicadores').removeAttr("disabled");*/
		
	});

	/*$("#listaIndicadores").change(function(){
		//alert($("#listaIndicadores option:selected").attr('data-base'));
		$('#lineaBase').html('<label>Línea Base: </label>' + $("#listaIndicadores option:selected").attr('data-base'));
	    $('#lineaBase').show();		
	    $('#metodoCalculo').html('<label>Método de Cálculo: </label>' + $("#listaIndicadores option:selected").attr('data-calculo'));
	    $('#metodoCalculo').show();	
	});*/

	$("#nuevoItemPOA").submit(function(event){
		event.preventDefault();
		chequearCampos(this);			
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#listaObjetivoEstrategico").val())){
			error = true;
			$("#listaObjetivoEstrategico").addClass("alertaCombo");
		}
		
		if(!$.trim($("#listaProcesos").val())){
			error = true;
			$("#listaProcesos").addClass("alertaCombo");
		}
		
		if(!$.trim($("#listaSubprocesos").val())){
			error = true;
			$("#listaSubprocesos").addClass("alertaCombo");
		}

		/*if(!$.trim($("#listaComponentes").val())){
			error = true;
			$("#listaComponentes").addClass("alertaCombo");
		}*/
		
		if(!$.trim($("#listaActividades").val())){
			error = true;
			$("#listaActividades").addClass("alertaCombo");
		}

		/*if(!$.trim($("#listaIndicadores").val())){
			error = true;
			$("#listaIndicadores").addClass("alertaCombo");
		}
		
		if(!$.trim($("#meta1").val()) || !esCampoValido("#meta1")){
			error = true;
			$("#meta1").addClass("alertaCombo");
		}

		if(!$.trim($("#meta2").val()) || !esCampoValido("#meta2")){
			error = true;
			$("#meta2").addClass("alertaCombo");
		}

		if(!$.trim($("#meta3").val()) || !esCampoValido("#meta3")){
			error = true;
			$("#meta3").addClass("alertaCombo");
		}

		if(!$.trim($("#meta4").val()) || !esCampoValido("#meta4")){
			error = true;
			$("#meta4").addClass("alertaCombo");
		}*/
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
	

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		
		/*var acum=0;
		
		  $("input").focus(function(){
		    $(this).css("background-color","#cccccc");
		  });
	
		$("input").blur(function(){
		    $(this).css("background-color","#ffffff");
		    acum=Number($("#meta1").val())+Number($("#meta2").val())+Number($("#meta3").val())+Number($("#meta4").val());

		    if( acum > $("#listaIndicadores option:selected").attr('data-base')){
		    	$("#total").css("background-color","#30C951");
		    }else if( acum < $("#listaIndicadores option:selected").attr('data-base')){
		    	$("#total").css("background-color","#C4313D");
		    }else if( acum == $("#listaIndicadores option:selected").attr('data-base')){
		    	$("#total").css("background-color","#F2EF38");
		    }

		    $("#total").val(acum).toFixed(2);

		 });	*/

		 $("#dDetalle").hide();
	
	});

</script>
