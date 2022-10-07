nombreEnfermedad<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion ();
$cc = new ControladorCatalogos();

$nombre = htmlspecialchars ( $_POST['nombreRequerimiento'], ENT_NOQUOTES, 'UTF-8' );
$descripcion = htmlspecialchars ( $_POST['descripcion'], ENT_NOQUOTES, 'UTF-8' );
$usuarioResponsable = htmlspecialchars ( $_POST['usuarioResponsable'], ENT_NOQUOTES, 'UTF-8' );

$conexion->ejecutarConsulta("begin;");
$qRequerimiento=$cc->guardarRequerimientoRevisionIngreso($conexion, $nombre, $descripcion,$usuarioResponsable);
$idRequerimiento=pg_fetch_result($qRequerimiento, 0, 'id_requerimiento');
$conexion->ejecutarConsulta("commit;");
$conexion->ejecutarConsulta("rollback;");

echo '<input type="hidden" id="' . $idRequerimiento . '" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="abrirRequerimientoSAA" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		$('#_actualizarSubListadoItems').click();
		abrir($("#detalleItem input"),null,true);
	});	
</script>