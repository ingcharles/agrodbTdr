<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cce = new ControladorCertificados();
$cr = new ControladorRegistroOperador();
$cdp = new ControladorDossierPlaguicida();
$cee = new ControladorEnsayoEficacia();

$idSolicitud = $_POST['id'];
$condicion = $_POST['opcion'];

$datosGenerales=$cdp->obtenerSolicitud($conexion, $idSolicitud);
$identificador=$datosGenerales['identificador'];

$res = $cr->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($res);

if($condicion == 'verificacion' || $condicion == 'verificacionVUE'){	
	$qOrdenPago=$cce->obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion, $idSolicitud , 'dossierPlaguicida');
}

if($condicion == 'pago'){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'pago'.'-dossierPlaguicida-tarifarioNuevo'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacionVUE'.'-dossierPlaguicida-tarifarioNuevo-'.'" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
}else if ($condicion == 'verificacion' && pg_num_rows($qOrdenPago)!=0){
    echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$operador['identificador'].'-'.'verificacion'.'-dossierPlaguicida-'.pg_fetch_result($qOrdenPago, 0, 'id_pago').'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitudSinGrupo" data-destino="ordenPago" />';
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
            <label>Actividades del solicitante: </label>
            <ul>
            	<?php
                    $items = $cee->obtenerOperacionesDelOperador($conexion,$identificador,'IAP');
                    foreach ($items as $key=>$item){
                        echo '<li>'.$sret=$item['operacion'].'</li>';
                    }
                ?>
            </ul>
         </div>
         <div data-linea="4">
            <label for="provincia" class="opcional">Provincia: </label><?php echo $operador['provincia'];?>
         </div>
         <div data-linea="4">
            <label for="canton" class="opcional">Cantón: </label><?php echo $operador['canton'];?>
         </div>
         <div data-linea="5">
            <label for="parroquia" class="opcional">Parroquia: </label><?php echo $operador['parroquia'];?>
         </div>
         <div data-linea="6">
            <label for="direccion" class="opcional">Dirección: </label><?php echo $operador['direccion'];?>
         </div>
         
         <hr>

		<div data-linea="7">
            <label for="normativa">Normativa Aplicada: </label>
			<?php
                $normativaLista = $cee->obtenerItemDelCatalogo($conexion,'P1C30', $datosGenerales['normativa']);
				echo $normativaLista['nombre'];
            ?>
         </div>
         <div data-linea="8">
            <label for="motivo">Objetivo: </label>
            <?php 
                $motivo = $cee->obtenerItemDelCatalogo($conexion,'P1C2', $datosGenerales['motivo']);
                echo $motivo['nombre'];
			?>
		</div>
		<div data-linea="8">
            <label>Registro de un CLON: </label><?php echo $datosGenerales['es_clon'] == 't' ? 'SI': 'NO';?>
		</div>
         
	</fieldset>

<div id="ordenPago"></div>

<script type="text/javascript">
	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});

</script>