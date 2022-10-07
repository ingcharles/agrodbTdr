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

$elementosTipoCompra = explode(",",$_POST['elementos']);
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
  
<form id="eliminarTipoCompra" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarTipoCompra" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idTipoCompra' name='idTipoCompra' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>Tipos de Compra y Procedimientos Registrados</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosTipoCompra); $i++) {
			$tipoCompra = pg_fetch_assoc($cpp->abrirTipoCompra($conexion, $elementosTipoCompra[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$tipoCompra['id_tipo_compra'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$tipoCompra['nombre'].'
							</div>
							<input type="hidden" name="id[]" value="'.$tipoCompra['id_tipo_compra'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_tipo_compra= <?php echo json_encode($elementosTipoCompra); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_tipo_compra == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarTipoCompra").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>