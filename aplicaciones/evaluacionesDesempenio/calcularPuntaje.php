<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../aplicaciones/uath/models/salidas.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
?>
<header>
	<h1>Calcular Puntaje</h1>
</header>
<?php 
	if(!empty($_POST['elementos']) and is_numeric($_POST['elementos']) )
		{
?>
<form id="recalcular" 
	  data-rutaAplicacion="evaluacionesDesempenio" 
	  data-opcion="procesarCalculoPuntaje" 
	  data-accionEnExito="ACTUALIZAR">
	  <div id="estado"></div>
	  
	  <input type="hidden" name="codEvaluacion" id="codEvaluacion" value="<?php echo $_POST['elementos'];?>" />
	  
	<fieldset>	
		<legend>Calcular Puntaje de Servidores con Excepciones</legend>		
			<div data-linea="1" >
				<label><strong>Se realizará el cálculo de los puntajes finales de las evaluaciones de los siguientes servidores:</strong> </label>
				<?php 
				$ban=0;
				$consulta=$ced->devolverListaAplicantesExcepciones($conexion,$_POST['elementos'],'finalizado');
				while($file=pg_fetch_assoc($consulta)){
					$result=$ced->obtenerExcepcionesFuncionarios($conexion,$file['identificador_evaluado']);
					while($res=pg_fetch_assoc($result)){
						$ban=1;
						echo '<hr color="black" size=1 width="100%">';
						echo '<label><strong>Nombre: </strong> </label>';
						echo '<label>'.$res['nombres'].'</label>';
						echo '<br><label><strong>Localización: </strong> </label>';
						echo '<label>'.$res['provincia'].' - '.$res['canton'].' - '.$res['oficina'].'</label>';
						echo '<br><label><strong>Estructura: </strong> </label>';
						echo '<label>'.$res['coordinacion'].' - '.$res['direccion'].' - '.$res['gestion'].'</label>';
					}
				}
				
			//-------------------------------------------------------------------------------------------------------------------------------
				$result=$ced->devolverListaAplicantesIndividualExcepciones($conexion,$_POST['elementos'],'finalizado');
				while($aplicantes = pg_fetch_assoc($result)){
					$file = pg_fetch_assoc($ced->obtenerExcepcionesFuncionarios($conexion,$aplicantes['identificador_evaluado']));
					if($file['nombres'] != ''){
						$ban=1;
						echo '<hr color="black" size=1 width="100%">';
						echo '<label><strong>Nombre: </strong> </label>';
						echo '<label>'.$file['nombres'].'</label>';
						echo '<br><label><strong>Localización: </strong> </label>';
						echo '<label>'.$file['provincia'].' - '.$file['canton'].' - '.$file['oficina'].'</label>';
						echo '<br><label><strong>Estructura: </strong> </label>';
						echo '<label>'.$file['coordinacion'].' - '.$file['direccion'].' - '.$file['gestion'].'</label>';
							
					}
				}
				
				?>
			</div>		
	</fieldset>
	<p>
		<button id="guardarPuntaje" type="submit" class="guardar" >Calcular Puntaje</button>
	</p>
	
</form>
<?php }else{ 
    $mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Debe seleccionar una evaluación...!!';
	mensajesSalidas($mensaje);
 }?>
<script type="text/javascript">
    var valor = <?php echo json_encode($ban);?>;
	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		if(valor == 0 ){
			$("#guardarPuntaje").attr('disabled','disabled')
			$("#estado").html("No existe información pendiente para calcular..!!").addClass("alerta");
			}
		
	});	
//-----------------------------------------------------------------------------------------------------------------------
	 $("#recalcular").submit(function(event){
		 	event.preventDefault();
			ejecutarJson($(this));
	 });
//----------------------------------------------------------------------------------------------------------------------
</script>



