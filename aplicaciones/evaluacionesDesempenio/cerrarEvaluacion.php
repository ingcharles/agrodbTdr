<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

//$resultadoEvaluacion = pg_fetch_assoc($ced->verificarResultadosEvaluacion($conexion, $evaluacion['id_evaluacion']));

?>
<header>
	<h1>Resultado evaluación</h1>
</header>
<div id="estado"></div>
<form id="cerrarEvaluacion" 
data-rutaAplicacion="evaluacionesDesempenio" 
data-opcion="procesarCerrarEvaluacion" 
data-destino="detalleItem" 
data-accionEnExito="ACTUALIZAR">
<?php 
if(!empty($_POST['elementos']) and is_numeric($_POST['elementos']) )
{
	$qEvaluacion = $ced->abrirEvaluacion($conexion, $_POST['elementos'], 'ABIERTOS');
	$evaluacion = pg_fetch_assoc($qEvaluacion);
	$res = $ced->listaParametros($conexion,'ABIERTOS');
	
	$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'','', $_POST['elementos'] ),0,'vigencia');
	?>

		<input type="hidden" name="codEvaluacion" id="codEvaluacion" value="<?php echo $_POST['elementos'];?>"> 
	<fieldset>	
		<legend>Datos generales</legend>		
			<div data-linea="1">
				<label>Nombre evaluación</label>	
					<input type="text" name="nombreEvaluacion" id="nombreEvaluacion" value="<?php echo $evaluacion['nombre'];?>" disabled="disabled"> 
			</div>			
			<div data-linea="2">
				<label>Objetivo</label> 
					<input type="text" name="objetivo" id="objetivo" value="<?php echo $evaluacion['objetivo'];?>" disabled="disabled"/> 
			</div>
			<div data-linea="3">
				<label>Parámetros</label>	
				
					<select style='width:100%' name="parametro" id="parametro" disabled="disabled">
					<option value="" ><?php echo $evaluacion['cod_parametro'];?>...</option>
					<?php
					   while($fila = pg_fetch_assoc($res)){
							if($evaluacion['cod_parametro'] == $fila['cod_parametro'])
							echo '<option value="'.$fila['cod_parametro'].'">'. $fila['nombre_parametro'] . '</option>';
						}		   					
					?>
				</select>
			</div>			
			<div data-linea="4">
				<label>Desactivar catastro</label> 
				<input type="text" name="cc" id="cc" value="<?php echo $evaluacion['estado_catastro'];?>" disabled="disabled">
				
			</div>	
			<p>
		</p>		
	</fieldset>
	<button type="submit" id="btnGenerar" disabled="disabled" >Cerrar evaluación</button>
</form>
<?php 

}else{
	 
	?>
	<script>
	$("#detalleItem").html('<div class="mensajeInicial">Seleccione una evaluación, a continuación presione el boton Cerrar Evaluación.</div>');
	</script>
	<?php 
 }
?>
</body>
<script type="text/javascript">
	var valor_resultado= <?php echo json_encode($consultaEva); ?>;
	$(document).ready(function(){
			distribuirLineas();
			construirValidador();
			if(valor_resultado == 'excepciones'){
				$('#btnGenerar').removeAttr("disabled","disabled");
				
			}else {
				$("#estado").html("Evaluación en curso tiene que finalizar su proceso...!!").addClass('alerta');
				//$('#btnGenerar').attr("disabled","disabled");
				
			}
			cargarValorDefecto("parametro","<?php echo $evaluacion['cod_parametro'];?>");
			
		});
	
	$("#cerrarEvaluacion").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
		
	});

</script>



