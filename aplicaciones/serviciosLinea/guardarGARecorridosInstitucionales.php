<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion ();
$csl = new ControladorServiciosLinea();

$identificadorResponsable = htmlspecialchars ( $_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8' );
$nombreRuta = htmlspecialchars ( $_POST['nombreRuta'], ENT_NOQUOTES, 'UTF-8' );
$idProvincia = htmlspecialchars ( $_POST['provincia'], ENT_NOQUOTES, 'UTF-8' );
$nombreProvincia = htmlspecialchars ( $_POST['nombreProvincia'], ENT_NOQUOTES, 'UTF-8' );
$idCanton = htmlspecialchars ( $_POST['canton'], ENT_NOQUOTES, 'UTF-8' );
$nombreCanton = htmlspecialchars ( $_POST['nombreCanton'], ENT_NOQUOTES, 'UTF-8' );
$idOficina = htmlspecialchars ( $_POST['oficina'], ENT_NOQUOTES, 'UTF-8' );
$nombreOficina = htmlspecialchars ( $_POST['nombreOficina'], ENT_NOQUOTES, 'UTF-8' );
$nombreSector = htmlspecialchars ( $_POST['sector'], ENT_NOQUOTES, 'UTF-8' );
$conductor = htmlspecialchars ( $_POST['conductor'], ENT_NOQUOTES, 'UTF-8' );
$telefono = htmlspecialchars ( $_POST['telefono'], ENT_NOQUOTES, 'UTF-8' );
$administradorGrupo = htmlspecialchars ( $_POST['administradorGrupo'], ENT_NOQUOTES, 'UTF-8' );
$telefonoAdministrador = htmlspecialchars ( $_POST['telefonoAdministrador'], ENT_NOQUOTES, 'UTF-8' );
$capacidadVehiculo = htmlspecialchars ( $_POST['capacidadVehiculo'], ENT_NOQUOTES, 'UTF-8' );
$numeroPasajeros = htmlspecialchars ( $_POST['numeroPasajeros'], ENT_NOQUOTES, 'UTF-8' );
$placaVehiculo = htmlspecialchars ( $_POST['placaVehiculo'], ENT_NOQUOTES, 'UTF-8' );
$descripcionVehiculo = htmlspecialchars ( $_POST['descripcionVehiculo'], ENT_NOQUOTES, 'UTF-8' );


$conexion->ejecutarConsulta("begin;");
$qGuardarNuevaRutaTransporte=$csl->guardarNuevaRutasTransporte($conexion, $identificadorResponsable, $nombreRuta, $idProvincia, $nombreProvincia, $idCanton, $nombreCanton, $idOficina, $nombreOficina, $nombreSector, $conductor, $telefono
																,$administradorGrupo,$telefonoAdministrador,$capacidadVehiculo,$numeroPasajeros,$placaVehiculo,$descripcionVehiculo);
$idRutaTransporte=pg_fetch_result($qGuardarNuevaRutaTransporte, 0, 'id_ruta_transporte');
$conexion->ejecutarConsulta("commit;");
$conexion->ejecutarConsulta("rollback;");

echo '<input type="hidden" id="' . $idRutaTransporte . '" data-rutaAplicacion="serviciosLinea" data-opcion="abrirGARecorridosInstitucionales" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>