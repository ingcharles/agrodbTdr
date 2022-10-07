<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCertificadoCalidad.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCertificadoCalidad();
$cct = new ControladorCatalogos();

$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$inspector = htmlspecialchars ($_POST['inspectores'],ENT_NOQUOTES,'UTF-8');
$condicion = htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8');
$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$revisionUbicacion = htmlspecialchars ($_POST['revisionUbicacion'],ENT_NOQUOTES,'UTF-8');
$tipoProcesoCombo = htmlspecialchars ($_POST['tipoProcesoCombo'],ENT_NOQUOTES,'UTF-8');

$provincia = ($_POST['provincia']==''?$_SESSION['nombreProvincia']:$_POST['provincia']);

switch ($tipoProcesoCombo){
    case'tipoOperacion':
        switch ($tipoSolicitud){
            case 'operadoresLT':
                $area = 'LT';
                $idLaboratorio = pg_fetch_assoc($cct->buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $provincia));
                $provincia = $cct->obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorio['id_laboratorio_diagnostico']);
            break;
            case 'operadoresAI':
                $area = 'AI';
                $provincia = "'$provincia'";
            break;
            case 'operadoresSV':
                $area = 'SV';
                $provincia = "'$provincia'";
            break;
            case 'operadoresSA':
                $area = 'SA';
                $provincia = "'$provincia'";
            break;
            case 'operadoresAGR':
                $area = 'IAP';
                $provincia = "'$provincia'";
            break;
            case 'operadoresFER':
                $area = 'IAF';
                $provincia = "'$provincia'";
            break;
            case 'operadoresPEC':
                $area = 'IAV';
                $provincia = "'$provincia'";
            break;
            case 'operadoresALM':
            	$area = 'CGRIA';
            	$provincia = "'$provincia'";
            break;
        }

        if($inspector == 'asignar'){
            $tipoOperacion = $cr-> obtenerTipoOperacionUbicacionPorEstadoArea($conexion, $area, $estado);
            $ingresoConsultaProvincia = false;
            $grupoOperacionDisponible = '(';
            $tipoOperacionDisponible ='';
            while ($fila = pg_fetch_assoc($tipoOperacion)){
                if($fila['ubicacion_revision'] == 'provincia'){
                    $grupoOperacionDisponible.= $fila['id_tipo_operacion'].",";
                    $ingresoConsultaProvincia = true;
                }else{
                    $tipoOperacionDisponible .= '<option data-ubicacionrevision="'.$fila['ubicacion_revision'].'" value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre_operacion'].'</option>';
                }
            }
            
            if($ingresoConsultaProvincia){
                $grupoOperacionDisponible = rtrim($grupoOperacionDisponible,",");
                $grupoOperacionDisponible .=')';
                $tipoOperacion = $cr->obtenerTipoOperacionUbicacionPorEstadoAreaProvincia($conexion, $area, $estado, "(".mb_strtoupper($provincia).")", $grupoOperacionDisponible);
                
                while ($fila = pg_fetch_assoc($tipoOperacion)){
                    $tipoOperacionDisponible .= '<option data-ubicacionrevision="'.$fila['ubicacion_revision'].'" data-codigotipooperacion="'.$fila['codigo'].'" value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre_operacion'].'</option>';
                }
            }
        }else{

            $tipoOperacion = $cr->obtenerAsignacionTipoOperacionPorEstadoAreaIdentificador($conexion, $inspector, $estado, 'Operadores', $condicion, $area, $tipoProcesoCombo);

            while ($fila = pg_fetch_assoc($tipoOperacion)){
                $tipoOperacionDisponible .= '<option data-ubicacionrevision="'.$fila['ubicacion_revision'].'" value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre_operacion'].'</option>';
            }
        }
       
        
        echo '<label>Tipo operaci&oacute;n</label>
                <select id="tipoOperacion" name="tipoOperacion" style="width:54%;">
                    <option value="">Seleccione....</option>'.$tipoOperacionDisponible.'
               </select>';
    break; 

    case 'operadores':
        switch ($tipoSolicitud){
            case 'operadoresLT':
				$area = 'LT';
                $idLaboratorio = pg_fetch_assoc($cct->buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $provincia));
                $provincia = $cct->obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorio['id_laboratorio_diagnostico']);
            break;
            case 'operadoresAI':
                $area = 'AI';
                $provincia = "'$provincia'";
            break;
            case 'operadoresSV':
                $area = 'SV';
                $provincia = "'$provincia'";
            break;
            case 'operadoresSA':
                $area = 'SA';
                $provincia = "'$provincia'";
            break;
            case 'operadoresAGR':
                $area = 'IAP';
                $provincia = "'$provincia'";
            break;
            case 'operadoresFER':
                $area = 'IAF';
                $provincia = "'$provincia'";
            break;
            case 'operadoresPEC':
                $area = 'IAV';
                $provincia = "'$provincia'";
            break;
            case 'operadoresALM':
            	$area = 'CGRIA';
            	$provincia = "'$provincia'";
            break;
        }
        
        if($inspector == 'asignar'){
            $qOperadores = $cr->obtenerOperadorPorTipoOperacionProvinciaEstado($conexion, "(".mb_strtoupper($provincia).")", $estado, $tipoOperacion, $revisionUbicacion);
        }else{
            $qOperadores = $cr->obtenerAsignacionTipoOperacionPorEstadoAreaIdentificador($conexion, $inspector, $estado, 'Operadores', $condicion, $area, $tipoProcesoCombo, $tipoOperacion);
        }

        echo '<label>Operador</label>
                <select id="identificadorOperador" name="identificadorOperador" style="width:78%;">
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

	$("#tipoOperacion").change(function (event) {
		$("#listaRevision").attr('data-opcion', 'combosOperador');
    	$("#listaRevision").attr('data-destino', 'operador');
    	$("#tipoProcesoCombo").val('operadores');
    	$("#revisionUbicacion").val($("#tipoOperacion option:selected").attr('data-ubicacionrevision'));
    	$("#codigoTipoOperacion").val($("#tipoOperacion option:selected").attr('data-codigotipooperacion'));
    	event.stopImmediatePropagation();
    	abrir($("#listaRevision"), event, false); 
	});

	$("#identificadorOperador").change(function (event) {
		if(estado == 'documental' || estado == 'asignadoDocumental'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionDocumentalFiltradoOperador');
		}else if (estado == 'inspeccion' || estado == 'asignadoInspeccion'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionInspeccionFiltradoOperador');
		}else if (estado == 'cargarRespaldo' || estado == 'asignadoCargarRespaldo'){
			$("#listaRevision").attr('data-opcion', 'listaRevisionRespaldoFiltradoOperador');
		}
		$("#listaRevision").attr('data-destino', 'tabla');
    	event.stopImmediatePropagation();
	});

</script>