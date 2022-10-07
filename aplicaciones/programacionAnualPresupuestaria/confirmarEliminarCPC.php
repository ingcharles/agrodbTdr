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

$elementosCPC = explode(",",$_POST['elementos']);
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
  
<form id="eliminarCPC" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarCPC" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idCPC' name='idCPC' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>CPC</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosCPC); $i++) {
			$cpc = pg_fetch_assoc($cpp->abrirCPC($conexion, $elementosCPC[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$cpc['id_cpc'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$cpc['nombre'].'
							</div>
							<div data-linea="2">
								<label>Código: </label>' .$cpc['codigo'].'
							</div>
							<div data-linea="3">
								<label>Nivel: </label>' .$cpc['nivel'].'
							</div>
							<input type="hidden" name="id[]" value="'.$cpc['id_cpc'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_cpc= <?php echo json_encode($elementosCPC); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_cpc == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarCPC").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>