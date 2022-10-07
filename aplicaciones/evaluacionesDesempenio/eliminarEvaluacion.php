<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../aplicaciones/uath/models/salidas.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();


?>
<header>
	<h1>Eliminar evaluación</h1>
</header>
<div id="estado"></div>
<?php 
	if(!empty($_POST['elementos']) and is_numeric($_POST['elementos']) )
		 {  
		 	$qEvaluacion = $ced->abrirEvaluacion($conexion, $_POST['elementos'], 'ABIERTOS');
			$evaluacion = pg_fetch_assoc($qEvaluacion);
			$res = $ced->listaParametros($conexion,'ABIERTOS');
		 	$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'','', $_POST['elementos'] ),0,'vigencia');
		 	$banderaEvaluacion = pg_num_rows($ced->devolverEvaluacionActiva($conexion));
		 	?> 

<form 
id="borrarEvaluacion" 
data-rutaAplicacion="evaluacionesDesempenio" 
data-opcion="borrarEvaluacion" 
data-destino="detalleItem" 
data-accionEnExito="ACTUALIZAR">
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
					<option value="" >Seleccione...</option>
					<?php
					   while($fila = pg_fetch_assoc($res)){
							echo '<option value="'.$fila['cod_parametro'].'">'. $fila['nombre_parametro'] . '</option>';
						}		   					
					?>
				</select>
			</div>			
			<div data-linea="4">
				<label>Desactivar catastro</label> 
				<select style='width:100%' name="catastro" id="catastro" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
						$area = array('Si','No');										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   					
					?>
				</select>	
			</div>	
	</fieldset>

	<input type="hidden" name="idEvaluacion" id="idEvaluacion" value="<?php echo $_POST['elementos'];?>"> 
	<input type="hidden" name="estadoCatastro" id="estadoCatastro" value="<?php echo $evaluacion['estado_catastro'];?>"> 
	<input type="hidden" name="banderaEvaluacion" id="banderaEvaluacion" value="<?php echo $banderaEvaluacion;?>"> 
	<input type="hidden" name="resultado" id="resultado" value="<?php echo $consultaEva;?>"> 
	<button type="submit" id="btnGenerar" class="eliminar">Eliminar evaluación</button>
</form>
<?php }else{ 
    $mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Debe seleccionar un elemento para eliminar...!!';
	mensajesSalidas($mensaje);
 }?>
</body>
<script type="text/javascript">
	
	$(document).ready(function(){
			distribuirLineas();
			construirValidador();
			cargarValorDefecto("parametro","<?php echo $evaluacion['cod_parametro'];?>");
			cargarValorDefecto("catastro","<?php echo $evaluacion['estado_catastro'];?>");
		});

	$("#borrarEvaluacion").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
	});
	
</script>



