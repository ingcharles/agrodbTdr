<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	
	$identificador=$_SESSION['usuario'];
	
	$operaciones = $cro-> listarOperacionesEnvioMasivo($conexion, $identificador);
	$areas =  $cro-> listarAreasEnvioMasivo($conexion,$identificador);
	
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Envio de operaciones masivo</h1>

</header>

<nav>
	<form id="listaEnvioOperacionMasivo" data-rutaAplicacion="registroOperador" data-opcion="listaEnvioOperacionMasivoFiltrado" data-destino="tabla">
		<table class="filtro" style="width: 500px;">
		<tbody>
			<tr>
				<th>Operaciones: </th>
				<td>
				
					<select id="lOperacion" name="lOperacion" style="width:100%;" >
						<option value="0">Seleccione...</option>
						<?php 
							while ($fila = pg_fetch_assoc($operaciones)){
							
							switch ($fila['id_area']){
								case 'SA':
									$tipoArea = 'Sanidad Animal';
								break;
								
								case 'SV':
									$tipoArea = 'Sanidad Vegetal';
								break;
								
								case 'IAV':
									$tipoArea = 'Registros de insumos Pecuarios';
								break;
								
								case 'IAF':
									$tipoArea = 'Registros de insumos Fertilizantes';
								break;
								
								case 'IAP':
									$tipoArea = 'Registros de insumos Agricolas';
								break;
								
								case 'IAPA':
									$tipoArea = 'Registro de insumos para plantas de autoconsumo';
								break;
								
								case 'AI':
									$tipoArea = 'Inocuidad de los alimentos';
								break;
								
								case 'LT':
									$tipoArea = 'Laboratorios';
									break;
									
								default:
									$tipoArea = 'Tipo Area Desconocido';
								
							}

							echo '<option value="' . $fila['id_tipo_operacion'] . '">' . $fila['nombre'].' - '.$tipoArea. '</option>';
							}
						?>
					</select> 
				</td>
			</tr>
			<tr>
				<th>Area:</th>
				<td>
						
					<select id="lArea" name="lArea" disabled="disabled" style="width:100%;">
					<option value="0">Seleccione...</option>
					</select>
				</td>
			</tr>								
			<tr>	
				<td colspan="4"><button>Filtrar lista</button></td>
			</tr>
		</tbody>
		</table>
	
		<input type="hidden" name="opcion" value= "<?php echo $_POST["opcion"];?>">
		<input type="hidden" name="idOperacion" id="idOperacion" value= "">
		<input type="hidden" name="idArea" id="idArea" value= "">
		<input type="hidden" name="nombreOperacion" id="nombreOperacion" value= "">
		
	</form>
</nav>

<div id="tabla"></div>

</body>


<script>

var array_area= <?php echo json_encode($areas); ?>;
var error = false;	

$(document).ready(function(){
	$("#listadoItems").addClass("lista");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
});


$("#lOperacion").change(function(event){
	 $("#idOperacion").val($("#lOperacion option:selected").val());
	 $("#nombreOperacion").val($("#lOperacion option:selected").text());
	sarea ='0';
	sarea = '<option value="">Seleccione...</option>';
    for(var i=0;i<array_area.length;i++){
	    if ($("#lOperacion").val()==array_area[i]['id_tipo_operacion']){
	    	sarea += '<option value="'+array_area[i]['id_area']+'">'+array_area[i]['nombre_area']+' - '+array_area[i]['nombre_lugar']+'</option>';
		}
   	}
    $('#lArea').html(sarea);
    $("#lArea").removeAttr("disabled");
});


$("#lArea").change(function(event){
	$("#idArea").val($("#lArea option:selected").val());
	
});


$("#listaEnvioOperacionMasivo").submit(function(e){

 	if($("#lOperacion option:selected").val()=="0"){	
		event.preventDefault();
		
	}else if($("#lArea option:selected").val()=="0"){	
		event.preventDefault();

	}else if($("#lArea option:selected").val()==""){	
		event.preventDefault();
	
	}else{
		$("#listaEnvioOperacionMasivo").attr('data-opcion', 'listaEnvioOperacionMasivoFiltrado');
		$("#listaEnvioOperacionMasivo").attr('data-destino', 'tabla');
		abrir($(this),e,false);         
	}
		
});


</script>
</html>