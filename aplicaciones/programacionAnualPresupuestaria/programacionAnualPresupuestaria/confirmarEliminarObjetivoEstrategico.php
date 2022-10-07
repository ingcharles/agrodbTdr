<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$conexion = new Conexion();
$cpp = new ControladorProgramacionPresupuestaria();
	
$fecha = getdate();
$anio = $fecha['year'];

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
	$idAreaFuncionario = $_SESSION['idArea'];
	$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
}

$elementosObjetivoEstrategico = explode(",",$_POST['elementos']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar Eliminación</h1>
</header>

<div id="estado"></div>          
  
<form id="eliminarObjetivoEstrategico" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarObjetivoEstrategico" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idObjetivoEstrategico' name='idObjetivoEstrategico' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>Objetivos Estratégicos</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosObjetivoEstrategico); $i++) {
			$objetivoEstrategico = pg_fetch_assoc($cpp->abrirObjetivoEstrategico($conexion, $elementosObjetivoEstrategico[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$objetivoEstrategico['id_objetivo_estrategico'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$objetivoEstrategico['nombre'].'
							</div>
							<input type="hidden" name="id[]" value="'.$objetivoEstrategico['id_objetivo_estrategico'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_objetivo_estrategico= <?php echo json_encode($elementosObjetivoEstrategico); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_objetivo_estrategico == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarObjetivoEstrategico").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>