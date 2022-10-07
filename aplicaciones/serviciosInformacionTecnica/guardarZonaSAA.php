<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion ();
$cc = new ControladorCatalogos();

$nombre = htmlspecialchars ( $_POST['nombreZona'], ENT_NOQUOTES, 'UTF-8' );
$usuarioResponsable = htmlspecialchars ( $_POST['usuarioResponsable'], ENT_NOQUOTES, 'UTF-8' );
$conexion->ejecutarConsulta("begin;");
$qZonas=$cc->guardarZonas($conexion, $nombre,$usuarioResponsable);
$idZona=pg_fetch_result($qZonas, 0, 'id_zona');
$conexion->ejecutarConsulta("commit;");
$conexion->ejecutarConsulta("rollback;");

echo '<input type="hidden" id="' . $idZona . '" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="abrirZonasPaisesSAA" data-destino="detalleItem"/>'
?>
<script type="text/javascript">
	$("document").ready(function(){
		$('#_actualizarSubListadoItems').click();
		abrir($("#detalleItem input"),null,true);
	});	
</script>