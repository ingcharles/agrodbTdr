<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$cbpa = new ControladorCertificacionBPA();
$cap = new ControladorAplicacionesPerfiles();

$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$inspector = htmlspecialchars ($_POST['inspectores'],ENT_NOQUOTES,'UTF-8');
$condicion = htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8');
$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$revisionUbicacion = htmlspecialchars ($_POST['revisionUbicacion'],ENT_NOQUOTES,'UTF-8');
$tipoProcesoCombo = htmlspecialchars ($_POST['tipoProcesoCombo'],ENT_NOQUOTES,'UTF-8');

$provincia = ($_POST['provincia']==''?$_SESSION['nombreProvincia']:$_POST['provincia']);
$identificador = $_SESSION['usuario'];
$idAplicacion = $_SESSION['idAplicacion'];

switch ($tipoProcesoCombo){
    case 'operadores':
        switch ($tipoSolicitud){
            case 'certificacionBPA':
            	
            	$perfiles = $cap->obtenerPerfilesUsuario($conexion, $idAplicacion, $identificador);
            	
            	$i=false;
            	
            	while ($perfil = pg_fetch_assoc($perfiles)){
            		if($perfil['codificacion_perfil'] == 'PFL_REV_CERT_BPA'){
            			$qOperadores = $cbpa->obtenerOperadoresCertificacionBPA($conexion, $estado, $provincia,'');
            			$i=true;
            		}else if($perfil['codificacion_perfil'] == 'PFL_ADM_CERT_BPA'){
            			$qOperadores = $cbpa->obtenerOperadoresCertificacionBPA($conexion, $estado, '', 'Si');
            			$i=true;
            		}else if($perfil['codificacion_perfil'] == 'PFL_APR_CERT_BPA'){
            			$qOperadores = $cbpa->obtenerOperadoresCertificacionBPA($conexion, $estado, $provincia,'');
            			$i=true;
            		}            		
            		if($i){
            		    break;
            		}
            	}
            	
            break;
            
            default :
            	echo 'Formulario desconocido';
        }
		
        echo '<label>Operador</label>
                <select id="identificadorOperador" name="identificadorOperador" style="width:84%;" required>
                    <option value="">Seleccione....</option>';
                    while ($fila = pg_fetch_assoc($qOperadores)){
                        echo '<option value="'.$fila['identificador'].'">'.$fila['nombre_operador'].'</option>';
                    }
        echo '</select>';
    break;
}
?>
<script type="text/javascript">

var estado = <?php echo json_encode($estado);?>;

	$("#identificadorOperador").change(function (event) {
		if(estado == 'enviado'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionFiltradoBPA');
		}else if (estado == 'inspeccion'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionFiltradoBPA');
		}else if(estado =='aprobacion'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionFiltradoBPA');
		}
		$("#listaRevision").attr('data-destino', 'tabla');
    	event.stopImmediatePropagation();
	});

</script>