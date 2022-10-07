<?php
	session_start ();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	
	$conexion = new Conexion ();
	$so = new ControladorSeguridadOcupacional ();
	
	$idLaboratorioMaterialPeligroso = htmlspecialchars ( $_POST ['idLaboratorioMaterialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$nombreLaboratorioMaterialPeligroso = htmlspecialchars ( $_POST ['nombreLaboratorioUno'], ENT_NOQUOTES, 'UTF-8' );
		
	$idLaboratorio= $so->guardarLaboratorioMaterialPeligroso($conexion, mb_strtoupper($nombreLaboratorioMaterialPeligroso));
	echo '<input type="hidden" id="' . pg_fetch_result($idLaboratorio, 0, 'id_laboratorio') . '" data-rutaAplicacion="seguridadOcupacional" data-opcion="abrirLaboratorioMaterialPeligroso" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>
	