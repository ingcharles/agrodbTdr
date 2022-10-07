<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$cce = new ControladorCertificados();
$ce = new ControladorEtiquetas();


$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$qDatosOperador=$ce->abrirSolicitudEtiquetasEnviada($conexion, $idSolicitud);

if($condicion == 'verificacion' || $condicion == 'verificacionVUE'){
	
	$qOrdenPago=$cce->obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion,$idSolicitud , 'Emisión de Etiquetas');	
}


if($condicion == 'pago'){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qDatosOperador[0]['identificador'].'-'.'pago'.'-Emisión de Etiquetas-tarifarioNuevo-'.$qDatosOperador[0]['cantidadEtiqueta'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($qOrdenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qDatosOperador[0]['identificador'].'-'.$qDatosOperador[0]['estado'].'-Emisión de Etiquetas-tarifarioNuevo-'.$qDatosOperador[0]['cantidadEtiqueta'].'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacion' && pg_num_rows($qOrdenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qDatosOperador[0]['identificador'].'-'.$qDatosOperador[0]['estado'].'-Emisión de Etiquetas-'.pg_fetch_result($qOrdenPago, 0, 'id_pago').'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}

?>
 <header>
	<h1>Solicitud de Etiquetas</h1>
</header> 
	
<div id="estado"></div>
	<fieldset>
			<legend>Etiquetas de Ornamentales</legend>

			<div data-linea="1">
				<label>Tipo Solicitud: </label> Emisión de Etiquetas 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $qDatosOperador[0]['razonSocial']; ?> <br/>
			</div>
			
			<div data-linea="3">
				<label>Representante legal: </label> <?php echo $qDatosOperador[0]['nombreRepresentante'] . ' ' . $qDatosOperador[0]['apellidoRepresentante']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label><?php echo $qDatosOperador[0]['estado']; ?>
			</div>
			
			<div data-linea="5">
				<label>Cantidad: </label><?php echo $qDatosOperador[0]['cantidadEtiqueta']; ?>
			</div>

	</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">
	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>