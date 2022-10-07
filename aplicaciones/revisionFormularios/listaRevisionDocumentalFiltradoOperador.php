<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();


$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$condicion = htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$identificadorInspector = htmlspecialchars ($_POST['inspectores'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$estadoActual = htmlspecialchars ($_POST['estadoActual'],ENT_NOQUOTES,'UTF-8');
$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$revisionUbicacion = htmlspecialchars ($_POST['revisionUbicacion'],ENT_NOQUOTES,'UTF-8');
$codigoTipoOperacion = htmlspecialchars ($_POST['codigoTipoOperacion'],ENT_NOQUOTES,'UTF-8');


//////

$nombreOpcion=$_POST['nombreOpcion'];

$provincia = $_SESSION['nombreProvincia'];
//$estado = 'inspeccion';

//Área para filtro de inspección
$areaInspeccion = $_POST['areaInspeccion'];


$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $_SESSION['usuario']);

			$banderaAgruparOrganicos = false;
			
			if($tipoSolicitud == 'operadoresAI'){
			    $inocuidad = true;
			    switch ($codigoTipoOperacion){
			        case 'PRO':
			        case 'COM':
			        case 'PRC':
			        case 'REC':
			            $banderaAgruparOrganicos = true;
			        break;
			    }
			}			
						
			while($fila = pg_fetch_assoc($res)){
				
			    $validacion = 1;
			    
			    if($inocuidad){			    
    			    if($banderaAgruparOrganicos){       
    			        if($fila['estilo'] == '_agrupar'){			            
    			            $fila['pagina'] = 'abrirOperacionDocumentalAgrupada';
    			        }
    			    }else{
    			        $validacion = ($fila['estilo'] != "_agrupar");
    			    }	
			    }else{
			        if($fila['estilo'] == '_agrupar'){
			            $fila['pagina'] = 'abrirOperacionDocumentalGrupo';
			        }
			    }
					
			    if($validacion){			    
    				echo '<a href="#"
    					id="' . $fila['estilo'] . '"
    					data-destino="detalleItem"
    					data-opcion="' . $fila['pagina'] . '"
    					data-rutaAplicacion="' . $fila['ruta'] . '"
    					>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';				
			    }
			}
			
	echo'</nav></header>';
	
	//Elección de tipo de formulario para impresión
	
	switch ($tipoSolicitud){

	    case 'operadoresLT':
	        $idLaboratorio = pg_fetch_assoc($cc->buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $provincia));
	        $provincia = $cc->obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorio['id_laboratorio_diagnostico']);
	        $formularioGeneral = 'Operadores';
        break;
	    case 'operadoresAI':
	    case 'operadoresSV':
	    case 'operadoresSA':
	    case 'operadoresAGR':
	    case 'operadoresFER':
	    case 'operadoresPEC':
	    case 'operadoresALM':
	        $provincia = "'$provincia'";
	        $formularioGeneral = 'Operadores';
        break;

		default :
			echo 'Formulario desconocido';
		break;
	}
	
	if($identificadorInspector == 'asignar'){
	    $qSitios = $cr->obtenerSolicitudesOperadores($conexion, "(".mb_strtoupper($provincia).")", $estado, 'SITIOS', $estadoActual, $formularioGeneral, $identificadorOperador, $revisionUbicacion, $tipoOperacion);
	    $qOperadores = $cr->obtenerSolicitudesOperadores($conexion, "(".mb_strtoupper($provincia).")", $estado, 'OPERACIONES', $estadoActual, $formularioGeneral, $identificadorOperador, $revisionUbicacion, $tipoOperacion);
	}else{
	    $qSitios = $cr->listarOperacionesAsignadasInspectorRS($conexion, $estado, $identificadorInspector, $formularioGeneral, $condicion, 'SITIOS',$identificadorOperador, $tipoOperacion);
	    $qOperadores = $cr->listarOperacionesAsignadasInspectorRS($conexion, $estado, $identificadorInspector, $formularioGeneral, $condicion, 'OPERACIONES', $identificadorOperador, $tipoOperacion);
	}
	
	while($sitio = pg_fetch_assoc($qSitios)){
		echo '<div id="'.$sitio['id_sitio'].'" class="contenedor">
						<h2>'.$sitio['nombre_lugar'].'</h2>
						<div class="elementos"></div>
					</div>';
	}
	
	$contador = 0;
	while($operacion = pg_fetch_assoc($qOperadores)){
		
		$nombreArea = $cr->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $operacion['id_tipo_operacion'], $identificadorOperador, $operacion['id_sitio'], $operacion['id_operacion']);
		
		$categoria = $operacion['id_sitio'];
		$contenido = '<article
							id="'.$operacion['id_operacion'].'"
							class="item"
							data-rutaAplicacion="registroOperador"
							data-opcion="abrirOperacionDocumentalGrupo"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem"
							data-sitio="'.$operacion['id_sitio'].'"
							data-idOpcion="'.$nombreOpcion.'">
							<span class="ordinal">'.++$contador.'</span>
							<span> # '.$operacion['id_tipo_operacion'].'-'.$operacion['id_sitio'].'</span><br />
							<span><small>'.(strlen($operacion['nombre'])>30?(substr($cr->reemplazarCaracteres($operacion['nombre']),0,30).'...'):(strlen($operacion['nombre'])>0?$operacion['nombre']:'')).'<b> en </b> '.
							(strlen($nombreArea)>42?(substr($cr->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
							<aside>'.date('j/n/Y',strtotime($operacion['fecha_creacion'])).'</aside>
					</article>';
		?>
						<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
						</script>
		<?php					
		}	
		?>

<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');	

	$("#_agrupar").click(function(e){
		//e.preventDefault();
		$("#estado").html("").removeClass('alerta');

		if($("#cantidadItemsSeleccionados").text() >= 1){		
    		if($("#cantidadItemsSeleccionados").text() > 500){
    			$("#estado").html("Solo puede agrupar un máximo de 500 solicitudes.").addClass('alerta');			
    			return false;
    		}else{
    			$('#_agrupar').attr('data-rutaaplicacion','registroOperador');
    			$('#_agrupar').attr('data-idOpcion',<?php echo json_encode($nombreOpcion);?>);
    		}
		}else{
			$("#estado").html("Seleccione almenos una solicitud.").addClass('alerta');			
			return false;
		}
	});

	$('#_asignar').addClass('_asignar');
	$('#_asignar').attr('id', <?php echo json_encode($tipoSolicitud);?>+'-'+<?php echo json_encode($condicion);?>);

</script>

