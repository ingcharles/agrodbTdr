<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cce = new ControladorCertificados();
$cr = new ControladorRegistroOperador();
$cdp = new ControladorDossierPecuario();
$cee = new ControladorEnsayoEficacia();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$datosGenerales=$cdp->obtenerSolicitud($conexion, $idSolicitud);
$identificador=$datosGenerales['identificador'];

$res = $cr->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($res);

if($condicion == 'verificacion' || $condicion == 'verificacionVUE'){	
	$qOrdenPago=$cce->obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion, $idSolicitud , 'dossierPecuario');	
}

if($condicion == 'pago'){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'pago'.'-dossierPecuario-tarifarioNuevo'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacionVUE'.'-dossierPecuario-tarifarioNuevo-'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacion' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacion'.'-dossierPecuario-'.pg_fetch_result($qOrdenPago, 0, 'id_pago').'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}

?>
 <header>
	<h1>Solicitud de Dossier Pecuario</h1>
</header> 
	
<div id="estado"></div>

      <fieldset>
         <legend>Información del solicitante</legend>

         <div data-linea="1">
            <label for="razon" class="opcional">Razón social: </label><?php echo $operador['razon_social'];?>
         </div>
         <div data-linea="2">
            <label for="ruc" class="opcional">CI/RUC/PASS: </label><?php echo $operador['identificador'];?>
         </div>
         <div data-linea="3">
            <label for="provincia" class="opcional">Provincia: </label><?php echo $operador['provincia'];?>
         </div>
         <div data-linea="3">
            <label for="canton" class="opcional">Cantón: </label><?php echo $operador['canton'];?>
         </div>
         <div data-linea="4">
            <label for="parroquia" class="opcional">Parroquia: </label><?php echo $operador['parroquia'];?>
         </div>
		<div data-linea="5">
            <label for="direccion" class="opcional">Dirección: </label><?php echo $operador['direccion'];?>
         </div>
         <div data-linea="6">
            <label for="nombreLegal">Nombres representante legal: </label><?php echo $operador['apellido_representante'].' '.$operador['nombre_representante'];?>
         </div>
         <hr>
         <div data-linea="7">
            <label for="nombreProducto">Nombre del producto: </label><?php echo $datosGenerales['nombre'];?>
         </div>
         <div data-linea="8">
			<label for="id_subtipo_producto">Subtipo producto: </label>
				<?php 
				   echo pg_fetch_result($cc->obtenerSubTipoProductosXid($conexion, $datosGenerales['id_subtipo_producto']),0,'nombre');
				?>	
		</div>      	  
	  	<div data-linea="8">
			<label for="tipo_solicitud">Tipo de solicitud</label>				
				<?php
				   $tipoSolicitud = $cee->obtenerItemDelCatalogo($conexion,'P4C0', $datosGenerales['tipo_solicitud']);
				   echo $tipoSolicitud['nombre'];
                ?>
		</div>
		<div data-linea="9">
			<label for="id_sitio">Sitio: </label>
				<?php echo pg_fetch_result($cr->abrirSitio($conexion, $datosGenerales['id_sitio']),0,'nombre_lugar'); ?>
		</div>
		
		<div data-linea="10">
			<label for="id_area">Área: </label>
				<?php echo pg_fetch_result($cr->ObtenerDatosAreaOperador($conexion, $datosGenerales['id_area']),0,'nombre_area');?>
			</div>
			
			</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">
	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>