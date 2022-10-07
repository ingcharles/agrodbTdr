<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$idItem = $_POST['id'];
	//$idArea = $_POST['idArea'];
	
	$tmp= explode("-", $idItem);
		
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	
	$items = pg_fetch_assoc($cf->abrirSubDocumento($conexion, $tmp[0],$tmp[1]));
		
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<h1>Detalle de subdocumento</h1>
	</header>
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarItem" data-rutaAplicacion="financiero" data-opcion="modificarItem">
					<input type="hidden" id="idItem" name="idItem" value="<?php echo $items['id_servicio'];?>">
					<input type="hidden" name="id" value="<?php echo $idArea;?>"/>
					
					<p>
						<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
					</p>
								
					<fieldset>
						<legend>Item</legend>	
						<div data-linea="1">
							<label for="concepto">Concepto</label>
							<input id="concepto" name="concepto" type="text" value="<?php echo $items['concepto'];?>" disabled="disabled"/>
						</div>
							
						<div data-linea="2">
							<label for="unidad">Unidad</label>
							<input name="unidad" id="unidad" type="number" value="<?php echo $items['unidad'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="2">
							<label for="valor">Valor</label>
							<input name="valor" id="valor" type="number" value="<?php echo $items['valor'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="2">
							<label id="liva">Iva</label> 
								<select id="iva" name="iva">
									<option value="0">Seleccione....</option>
									<option value="TRUE">Iva 12%</option>
									<option value="FALSE">Excepto de iva</option>
								</select>
						</div> 
						
						<div data-linea="3">
							<label for="partidaPresupuestaria">Partida presupuestaria</label>
							<input name="partidaPresupuestaria" id="partidaPresupuestaria" type="text" value="<?php echo $items['partida_presupuestaria'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="4">
							<label for="unidadMedida">Unidad de medida</label>
							<input name="unidadMedida" id="unidadMedida" type="text" value="<?php echo $items['unidad_medida'];?>" disabled="disabled" />
						</div>
					</fieldset>	
						
					</form>	
				
				
			    
			</td>
		</tr>
	</table>
</body>
<script>
var iva = <?php echo json_encode($items['iva']); ?>;
var valorIva;

$('document').ready(function(){		
	distribuirLineas();
	
		if(iva == 't'){
			valorIva = 'TRUE';
		}else{
			valorIva = 'FALSE';
		}
	cargarValorDefecto("iva",valorIva);
});

$("#actualizarItem").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

acciones("#nuevoItem","#items");
	
</script>
</html>
