<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion ();
$cc = new ControladorCatalogos();

$nombre = htmlspecialchars ( $_POST['nombreEnfermedad'], ENT_NOQUOTES, 'UTF-8' );
$descripcion = htmlspecialchars ( $_POST['descripcion'], ENT_NOQUOTES, 'UTF-8' );
$observacion = htmlspecialchars ( $_POST['observacion'], ENT_NOQUOTES, 'UTF-8' );
$usuarioResponsable = htmlspecialchars ($_POST['usuarioResponsable'], ENT_NOQUOTES, 'UTF-8' );

$conexion->ejecutarConsulta("begin;");
$qEnfermedad=$cc->guardarEnfermedadAnimal($conexion, $nombre, $descripcion,$observacion,$usuarioResponsable);
$idEnfermedad=pg_fetch_result($qEnfermedad, 0, 'id_enfermedad');
$conexion->ejecutarConsulta("commit;");
$conexion->ejecutarConsulta("rollback;");

echo '<input type="hidden" id="' . $idEnfermedad . '" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="abrirEnfermedadAnimalSAA" data-destino="detalleItem"/>'
?>
<script type="text/javascript">
	$("document").ready(function(){
		$('#_actualizarSubListadoItems').click();
		abrir($("#detalleItem input"),null,true);	
	});	
</script>