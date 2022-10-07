<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatastro.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../aplicaciones/uath/models/salidas.php';

try {
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	
	$tmp = explode('.',$_POST['id']);
	$identificador=$tmp[0];
	$area=$tmp[1];
	$responsable=$tmp[2];
	
} catch (Exception $e) {
	echo $e;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<header>
	<h1>Administrar Responsable</h1>
</header>
<body>
<div id="estado"></div>
<form id="actualizarResponsable" data-rutaAplicacion="uath" data-opcion="actualizarResponsableEstructura"  data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="responsable" name="responsable" value="<?php echo $responsable; ?>"/>	
	<input type="hidden" id="area" name="area" value="<?php echo $area; ?>" /> 

	<input type="hidden" id="identificadorSubrogacion" name="identificadorSubrogacion" value="" />
<p>
		<button id="modificarRes" type="button" class="editar" <?php echo ($filaSolicitud['estado']=='Aprobado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizarRes" type="submit" class="guardar" disabled="disabled">Guardar</button>
	</p>
	
	<fieldset>
		<legend>Funcionario</legend>		
		<table style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Nombre funcionario</th>
					<th>Área</th>
					<th>Subrogar</th>
					<th>Responsable</th>
				</tr>
			</thead>
			<?php 
			//$contador = 0;
			$listaReporte = $cc->filtroObtenerFuncionarios($conexion, $identificador, '', '', $responsable, $area);
			while($fila = pg_fetch_assoc($listaReporte)) {	
			if($fila['area']==$area){
				if(pg_num_rows($cc->obtenerSubrogacionesFuncionarios($conexion, $area,$fila['identificador'],'')))
					$subrogar='<input checked type="checkbox" disabled="disabled" >';				
				if($responsable <> ''){
					$respons='<input id="'.$identificador.'" checked type="checkbox" class="respon" value="'.$identificador.'" disabled="disabled" onclick="seleccionarFuncionario(id)">';
				}else {
					$respons='<input id="'.$identificador.'" type="checkbox" value="'.$identificador.'" class="respon" disabled="disabled" onclick="seleccionarFuncionario(id)">';					
				}
				
			}else {
				$respons='';							
			}			
						
			echo '<tr>
					<td>'.$fila['identificador'].'</td>
					<td>'.$fila['nombre'].'</td>
					<td>'.$fila['area'].'<br>'.$fila['nombrearea'].'</td>	
					<td>'.$subrogar.'</td>
					<td>'.$respons.'</td>
				</tr>';
	 	}
	 	
	 	?>
		</table>
	</fieldset>
<?php 
	$listaSubrogacion = $cc->obtenerSubrogacionesFuncionarios($conexion, $area,'','');
	$consultaSubrog = pg_fetch_assoc($listaSubrogacion);
	if($responsable == 'Responsable'){
				menuSubrogacion();
				fechaSubrogacion( $consultaSubrog['fecha_inicio'],$consultaSubrog['fecha_fin'],$consultaSubrog['id_responsable']);
	}else 
	      		fechaSubrogacion( $consultaSubrog['fecha_inicio'],$consultaSubrog['fecha_fin'],$consultaSubrog['id_responsable']);
		?>	
	
	
	<fieldset>
		<legend>Funcionarios</legend>		
		<table style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Nombre funcionario</th>
					<th>Área</th>
					<th>Subrogar</th>
					<th>Responsable</th>
				</tr>
			</thead>

			<?php 
			$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $area);			
			while($fila = pg_fetch_assoc($listaReporte)) {
				if(pg_num_rows($cc->verificarResponsable($conexion,$fila['identificador'], $area))){
					$identifi=$fila['identificador'];
				}
				
			if(!strcmp($fila['identificador'], $identificador)==0){
				$respon='<input id="'.$fila['identificador'].'" type="checkbox" class="respon" value="'.$fila['identificador'].'" disabled="disabled" onclick="seleccionarFuncionario(id)">';
				if(pg_num_rows($cc->verificarResponsable($conexion,$fila['identificador'], $area))){
					$respon='<input id="'.$fila['identificador'].'" checked type="checkbox"  class="respon" value="'.$fila['identificador'].'" disabled="disabled" onclick="seleccionarFuncionario(id)">';
				}
				$subrogar='';
				if(pg_num_rows($cc->obtenerSubrogacionesFuncionarios($conexion, $area,$fila['identificador'],''))){
					$subrogar='<input checked type="checkbox" disabled="disabled" >';
				}
				
			echo '<tr>
					<td>'.$fila['identificador'].'</td>
					<td>'.$fila['nombre'].'</td>
					<td>'.$fila['area'].'<br>'.$fila['nombrearea'].'</td>
					<td>'.$subrogar.'</td>
					<td>'.$respon.'</td>
				</tr>';
			}
	 	}
	 	if($responsable != ''){
	 			$identifi=$identificador;
	 	}
	 	echo '<input type="hidden" id="identificador" name="identificador" value="'.$identifi.'" />';
	 	?>
		</table>
	</fieldset>
	</form>
<script>
var result= <?php echo json_encode($responsable); ?>;
$(document).ready(function(){
		distribuirLineas();
	if(result == 'Responsable')
		$("#detalle").hide();
	else {
		$("#opcion").val('subrogacion');
		$("#detalle").show();
	}
}); 


function agregarOpcion(id){
	
	$("#opcion").val($("#"+id).val());

	if($("#"+id).val() == 'responsable'){
		$("#detalle").hide();
		$("#fechaSalida").val('');
		$("#fechaRetorno").val('');
	    $("#actualizarResponsable").attr('data-opcion', 'actualizarResponsabilidad');
	}else{
		$("#detalle").show();
		$("#actualizarResponsable").attr('data-opcion', 'actualizarResponsableEstructura');
	}
}
function seleccionarFuncionario(id){
	$('.respon:checked').each(			
		    function() {
			    if($(this).val() != $("#"+id).val() )
		    	$(this).removeAttr('checked');
		    }
		);	
	if($("#"+id).prop('checked') ) {
		$("#identificadorSubrogacion").val($("#"+id).val());
	}	
}

$("#modificarRes").click(function(){	
	$("#actualizarResponsable input").removeAttr("disabled");
	$("#actualizarRes").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	
});
	
$("#actualizarResponsable").submit(function(e) {
	e.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	$("#estadoOpcion").html("");
	var error = false;	
	var msj="Por favor debe seleccionar un responsable o ingresar las fechas";
	$('.respon:checked').each(			
		    function() {
		    	error = true; 
		    	msj="Por favor debe seleccionar un responsable..!";
		    });	
    
	if($("#opcion").val()==""){
    	error = false;
		$("#estadoOpcion").html("Seleccione una opcion...").addClass('alerta');
		msj="Por favor debe seleccionar una opcion..!!";
	}

	if($("#opcion").val()=="subrogacion"){
		if($("#fechaSalida").val()==""){
			error = false;
			$("#fechaSalida").addClass("alertaCombo");
			msj="Por favor debe ingresar las fechas";
		}
		if($("#fechaRetorno").val()==""){
			error = false;
			$("#fechaRetorno").addClass("alertaCombo");
			msj="Por favor debe ingresar las fechas";
		}
	}
	if (error == true){
		$("#tabla").html('');
        abrir($(this), e, false);
   } else {
       mostrarMensaje(msj,"FALLO");
   }
	
});

$("#fechaSalida").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    beforeShowDay:function(dt){
   
    		return [dt.getDay() == 0 || dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5 || dt.getDay() == 6, ""];
    },
    onSelect: function(dateText, inst) {

    	$("#fechaRetorno").removeAttr("disabled");
	  	$('#fechaRetorno').datepicker('option', 'minDate', $("#fechaSalida" ).val());   	   	
    }
});

$("#fechaRetorno").datepicker({
	yearRange: "c:c+1",
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    beforeShowDay:function(dt){

    		return [dt.getDay() == 0 || dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5 || dt.getDay() == 6, ""];
    	
    },
    onSelect: function(dateText, inst){

    }
  });
  
</script>
	</body>
</html>

