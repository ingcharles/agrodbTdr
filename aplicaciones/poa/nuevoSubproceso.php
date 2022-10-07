<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cpoa1 = new ControladorPAPP();
	
	$datosProceso = $cpoa1->listarProcesos($conexion, $fecha['year']);
	
	while($fila = pg_fetch_assoc($datosProceso)){
		$listadoProcesos[]= array(id_proceso=>$fila['id_proceso'], descripcion_proceso=>$fila['descripcion'], proyecto=>$fila['proyecto']);
	}
?>

<header>
	<h1>Nuevo subproceso</h1>
</header>

<div id="estado"></div>

<form id="nuevoSubproceso" data-rutaAplicacion="poa" data-opcion="guardarNuevoSubProceso" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>
	
	<fieldset>
	
	<legend>Procesos / Proyectos</legend>
		<div data-linea="1">
		<table>
			<tr>
				<td>
					<label>Mostrar Proyectos</label>
				</td>
				<td>
					<input type="checkbox" id="filtroProyectos" name="filtroProyectos" value="1">
				</td>
			</tr>
		</table>
			
		
		</div>
		
		<div data-linea="2">	
			<select id="listaProcesos" name="listaProcesos">
				<option value="">Seleccione un elemento...</option>
				
				<?php 	
					$res= $cpoa1->listarProcesos($conexion, $fecha['year']);
	
					while($fila = pg_fetch_assoc($res)){
	                    if($fila['proyecto']== 0)        
				       	echo '<option value="' . $fila['id_proceso'] . '">' . $fila['descripcion'] .'</option>';
					}
				?>
			</select>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Descripción del nuevo subproceso</legend>
		<div data-linea="1">
			<input type="text" id="descripcion" name="descripcion" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
	</fieldset>
	
	<button type="submit" class="guardar">Generar Subproceso</button>

</form>
<script type="text/javascript">



var array_proyectos= <?php echo json_encode($listadoProcesos); ?>;
	$(document).ready(function(){
		distribuirLineas();	
		construirValidador();
	});


    $("#filtroProyectos").change(function() {  
      
    	if($("#filtroProyectos:checked").val()==1){
        	sactividades ='0';
    		sactividades = '<option value="">Seleccione un elemento...</option>';
	    	for(var z=0;z<array_proyectos.length;z++){ 	  
			    if (1==array_proyectos[z]['proyecto']){
			    	sactividades += '<option value="'+array_proyectos[z]['id_proceso']+'">'+array_proyectos[z]['descripcion_proceso']+'</option>';
				}
		   	}
   		
  	    	$('#listaProcesos').html(sactividades);

  	    }else{
    		sactividades ='0';
    		sactividades = '<option value="">Seleccione un elemento...</option>';
    		for(var z=0;z<array_proyectos.length;z++){
  	     	  
    		    if (1!=array_proyectos[z]['proyecto']){
    		    	sactividades += '<option value="'+array_proyectos[z]['id_proceso']+'">'+array_proyectos[z]['descripcion_proceso']+'</option>';
    			    }
    	   		}
      	    $('#listaProcesos').html(sactividades);
      	    }
    });  
 
	$("#nuevoSubproceso").submit(function(event){

		/*if($("#descripcion").val()=="") {
	    	$("#descripcion").focus();
	    	$("#descripcion").css("background-color","#ed4e76");
	        alert("Debe ingresar una descripción");
	        return false;
		}else{*/
			event.preventDefault();
			chequearCampos(this); 
			//ejecutarJson($(this));
			//abrir($(this),event,false);
		//}
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#listaProcesos").val()) || !esCampoValido("#listaProcesos")){
			error = true;
			$("#listaProcesos").addClass("alertaCombo");
		}
		
		if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
			error = true;
			$("#descripcion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($("#estado").html()=='El Subproceso ha sido generado satisfactoriamente'){
				$("#_actualizar").click();
			}
		}
	}
</script>
