<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cce = new ControladorCertificados();
$cr = new ControladorRegistroOperador();
$cee = new ControladorEnsayoEficacia();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$datosGenerales=$cee->obtenerProtocolo($conexion, $idSolicitud);
$identificador=$datosGenerales['identificador'];

$res = $cr->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($res);

if($condicion == 'verificacion' || $condicion == 'verificacionVUE'){	
	$qOrdenPago=$cce->obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion, $idSolicitud , 'ensayoEficacia');	
}

if($condicion == 'pago'){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'pago'.'-ensayoEficacia-tarifarioNuevo'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacionVUE'.'-ensayoEficacia-tarifarioNuevo-'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacion' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacion'.'-ensayoEficacia-'.pg_fetch_result($qOrdenPago, 0, 'id_pago').'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
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
            <label for="tipoRazon" class="opcional">Tipo razón social: </label><?php echo $operador['tipo_operador'];?>
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
         <div data-linea="8">
			<label for="id_subtipo_producto">Normativa aplicada: </label>
				<?php 
				    $tipoSolicitud = $cee->obtenerItemDelCatalogo($conexion,'P1C30', $datosGenerales['normativa']);
				    echo $tipoSolicitud['nombre'];
				?>	
		</div>
		<div data-linea="9">
			<label>Motivo del Ensayo: </label>
				<?php 
				    $tipoSolicitud = $cee->obtenerItemDelCatalogo($conexion,'P1C2', $datosGenerales['motivo']);
				    echo $tipoSolicitud['nombre'];
				?>
		</div>
		<div data-linea="10">
			<label for="id_area">Nombre científico del cultivo: </label>
				<?php
				    $producto = pg_fetch_assoc($cc->obtenerNombreProducto($conexion, $datosGenerales['cultivo'])); 
				    echo $producto['nombre_cientifico'];
				?>
		</div>
		<div data-linea="10">
			<label for="id_area">Nombre común del cultivo: </label>
				<?php
				    echo $producto['nombre_comun'];
				?>
		</div>
		<div data-linea="11">
			<label for="id_area">Uso propuesto del producto: </label>
				<?php
				echo pg_fetch_result($cc->obtenerSubTipoProductosPorCodigo($conexion, $datosGenerales['uso']),0,'nombre');
				?>
		</div>
			
			</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">
	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>