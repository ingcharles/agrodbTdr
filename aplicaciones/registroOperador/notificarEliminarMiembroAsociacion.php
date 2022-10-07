<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$miembros=explode("@",$_POST['elementos']);

list($idMiembroAsociacion, $idSitio, $nombreSitio) = $miembros;

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar eliminación</h1>
</header>

<div id="estado"></div>

<?php 

	echo '<p>El <b>miembro de la asociación</b> a ser eliminado es: </p>';

	$qMiembroAsociacion = $cro->obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembroAsociacion);
	$miembroAsociacion = pg_fetch_assoc($qMiembroAsociacion);
	
	$res = $cro->obtenerDetalleMiembroXIdentificadorXSitio($conexion, $idMiembroAsociacion, $idSitio);

		echo '<fieldset>
						<legend>Información general</legend>
							<div data-linea="1">
								 <label>Código operador: </label>' .$miembroAsociacion['codigo_miembro_asociacion'].'<br/>' .
									 '</div>
							<div data-linea="1">
								 <label>Identificación: </label>' .$miembroAsociacion['identificador_miembro_asociacion'].'<br/>' .
									 '</div>
							<div data-linea="2">
								 <label>Nombres y apellidos: </label>' .$miembroAsociacion['nombre_miembro_asociacion'].'<br/>' .
									 '</div>
							<div data-linea="3">
								 <label>Código Magap: </label>' .$miembroAsociacion['codigo_magap'].'<br/>' .
									 '</div>
			</fieldset>';
	
		echo '<fieldset>
			<legend>Áreas y operaciones del sitio ' .$nombreSitio. '</legend>';
	
			while($miembro = pg_fetch_assoc($res)){
				
				echo '<div data-linea="1">
					 <label>Nombre sitio: </label>' .$miembro['nombre_lugar'].'<br/>' .
				'</div>
				<div data-linea="2">
					 <label>Nombre área: </label>' .$miembro['nombre_area'].'<br/>' .
				'</div>
				<div data-linea="3">
					 <label>Operación: </label>' .$miembro['nombre_tipo_operacion'].'<br/>' .
				'</div>
				<div data-linea="4">
					 <label>Nombre producto: </label>' .$miembro['nombre_producto'].'<br/>' .
				'</div>
				<div data-linea="5">
					 <label>Rendimiento: </label>' .$miembro['rendimiento'].'<br/>' .
				'</div> <hr>';				
			}
			
			echo '</fieldset>';
?>

<form id="eliminarMiembroAsociacion" data-rutaAplicacion="registroOperador" data-opcion="eliminarMiembroAsociacion" data-destino="detalleItem" data-accionenexito="ACTUALIZAR">

	<input type="hidden" name="idMiembroAsociacion" value="<?php echo $idMiembroAsociacion;?>"/>
	<input type="hidden" name="idSitio" value="<?php echo $idSitio;?>"/>

	<button id="eliminar" type="submit" class="eliminar" >Eliminar</button>
	
</form>

</body>

<script type="text/javascript">
var array_contrato= <?php echo json_encode($contratos); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

$("#eliminarMiembroAsociacion").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(".eliminar").click(function(event){
	
	$("#eliminarMiembroAsociacion").attr('data-accionEnExito','ACTUALIZAR');

});

</script>

</html>

