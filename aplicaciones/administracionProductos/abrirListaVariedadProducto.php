<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRequisitos();

$idOperacion =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$nombreOpcion=$_POST['opcion'];

$qVariedades = $cc->buscarOperacionesProductosVariedades($conexion, $idOperacion);
//$datos=pg_fetch_assoc($qVariedades);
?>
<header>
	<h1><?php echo $nombreOpcion?></h1>
</header>
<div id="estado"></div>
<div id="visualizar">


	<fieldset>
					<legend>Lista de productos</legend>
					<table id="codigoVP">
					<tr style="font-weight: bold;"><td>Id producto</td> <td>Nombre producto</td><td>Múltiple variedad</td><td>Eliminar</td></tr>
						<?php 
						//$contador=pg_num_rows($qVariedades);
						while ($fila = pg_fetch_assoc($qVariedades)){
							//$contador=
							echo $cr->imprimirTipoOperacionMultiplesVariedades($fila['id_producto'],$fila['nombre_comun'],$fila['id_tipo_operacion'],$fila['multiple_variedad']);
						}
						?>
					</table>
	</fieldset>


</div>



<script type="text/javascript">

$(document).ready(function(){

	acciones(false,"#codigoVP");
	distribuirLineas();

});


$(".icono").click(function(event){
	if ($('#codigoVP >tbody >tr').length == 2){
		$("#imprimirVariedad").attr('data-accionEnExito','ACTUALIZAR');
	}	
});

</script>



