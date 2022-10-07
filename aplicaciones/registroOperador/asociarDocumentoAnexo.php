<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $cr = new ControladorRegistroOperador();

    $usuario = $_SESSION['usuario'];
    $operacion = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);
    $idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

    $listado = pg_fetch_all($cc->obtenerListaDeVerificacion($conexion, $operacion));
    $listaDeDocumentosDisponibles = pg_fetch_all($cr->listarDocumentosAnexos($conexion, $usuario));

   
	foreach ($idGrupoOperaciones as $idSolicitud){
		
		
		$res = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idSolicitud));
					
		$areaTipoOperacion = pg_fetch_assoc($cr->buscarOperacionTipoOperacionXIdOperacion($conexion, $idSolicitud));
		$areaOperacion = $areaTipoOperacion['id_area'];
		$codigoOperacion = $areaTipoOperacion['codigo'];
		
		switch($areaOperacion){
			
			case 'AI':
				
			    switch($codigoOperacion){
			        			        
			        case 'MDT':
			            $mensaje = "<b>NOTA:</b> Los literales E y F son obligatorios para los vehículos que transporten leche cruda, para los que transportan suero son opcionales.";
			        break;
			        
			    }
			    		
			break;
			
			default:
				$mensaje = "";				
		}		
		
		$qTipoOperacion[]  = $res['id_tipo_operacion'];
		$qObservacion[] = array('estado' => $res['estado'], 'observacion' => $res['observacion'], 'idOperacion' => $res['id_operacion'], 'estadoAnterior' => $res['estado_anterior']);
	}
		
	$tipoOperacion = array_unique($qTipoOperacion);
	
	$arraySelect = array();
	
	foreach ($listado as $combo) {		
		$datos = '<select name="'.$combo['id_tipo_operacion_requisito'].'" style="width: 460px;">';
		$opciones = '<option value="NADA" data-ruta="">Seleccione una opción...</option>';		
		foreach ($listaDeDocumentosDisponibles as $idDocumento){						
			if($combo['id_documento_anexo'] == $idDocumento['id_documento_anexo']){				
				$opciones .= '<option value="'.$idDocumento['id'].'" data-ruta="'.$idDocumento['ruta_documento'].'">'.$idDocumento['descr'].' ('.$idDocumento['nombre_documento'].')</option>';
			}			
		}		
		$datos .= $opciones.'</select><br/><button type="button" class="previsualizar">Previsualizar documento</button>';
		$arraySelect[$combo['id_tipo_operacion_requisito']] = $datos;		
	}
	
	$datoObservacion = '<fieldset id= "fObservaciones"><legend>Novedades presentadas en la operación.</legend>';
			
	foreach ($qObservacion as $observacion){
		if($observacion['estado'] == 'subsanacion' && ($observacion['estadoAnterior'] == 'inspeccion' || $observacion['estadoAnterior'] == 'documental' || $observacion['estadoAnterior'] == 'asignadoInspeccion' || $observacion['estadoAnterior'] == 'asignadoDocumental' || $observacion['estadoAnterior'] == 'representanteTecnico')){
			$datoObservacion .='<label class = "observacionDocumento">Número de solicitud '.$observacion['idOperacion'].' : </label><div>'. $observacion['observacion'].'</div>';
		}else if($observacion['estado'] == 'subsanacion' && $observacion['estadoAnterior'] == 'registrado'){
			$datoObservacion .='<label class = "observacionDocumento">Operación en nuevo proceso de verificación.</div><hr>';
		}
	}
	
	$datoObservacion .='</fieldset>';
	
	
	
   ?>
   
<header>
    <h1>Documento</h1>
</header>

<div id="estado"></div>
	<form id="guardarDocumentos" data-rutaAplicacion='registroOperador' data-opcion='guardarDocumentoOperacion' data-destino="detalleItem">
	
		<input type="hidden" name="idOperacion" value="<?php echo $operacion;?>"/>
		
		<?php echo $datoObservacion;?>
		
	    <fieldset>
	        <legend>Documento anexo</legend>
	            <?php
	                $html = '<table width="100%">';
	                foreach ($listado as $item) {
	                    $html .= '<tr>' .
	                        '<td><input type="checkbox" value="M" class="obligatorio_'.$item['es_obligatorio'].'" name="c_'.$item['id_tipo_operacion_requisito'].'" '.($item['requiere_archivo'] == 't'?'disabled="disabled"':'').' /></td>'.
	                        '<td class="obligatorio_'.$item['es_obligatorio'].'"><span class="'.($item['es_obligatorio']=='t'?'obligatorio':'').'">' . $item['titulo'] . '<span></td>' .
	                        '<td>' .
	                        '<div>' . $item['descripcion'] . '</div>' .
	                        '<div>';
	                    if($item['requiere_archivo'] == 't'){
	                      	$html .= $arraySelect[$item['id_tipo_operacion_requisito']]; 
	                    }	
	                    $html .= '</div>' .
	                        '</td>' .
	                        '</tr>';
	                }	
	                $html.='</table>';	
	                echo $html;
	            ?>
	        <button id="guardar" disabled="disabled">Cargar documentos adjuntos</button>
	    </fieldset>
	</form>
	
	<?php 
		echo $mensaje;
	?>
	
	
<script>

	var array_operacion= <?php echo json_encode($tipoOperacion); ?>;

	$(document).ready(function(){

		$("#fObservaciones").hide();
		if(array_operacion.length != 1){
			$("#detalleItem").html('<div class="mensajeInicial">No se permite agrupar solicitudes de diferentes operaciones.</div>');
		}

		if($(".observacionDocumento").length != 0){
			$("#fObservaciones").show();
		}
		
	});
	            
    $("button.previsualizar").click(function(){
        var documento = $(this).parent().find("select option:selected").attr("data-ruta");
        if (documento != ""){
            window.open(documento);
        } else {
            alert ("Esta opción no tiene asociado ningún documento.");
        }
    });

    $("select").change(function(){
        var check = $(this).parent().parent().parent().find("input");
        if($(this).find("option:selected").val()!='NADA'){
            check.prop("checked", true);
            if($(".obligatorio").length <= $("input.obligatorio_t:checked").length){
                $("#guardar").removeAttr("disabled");
            }            
        }else {
            check.attr("disabled","disabled");
            check.removeAttr("checked");
            $("#guardar").attr("disabled","disabled");
        }
    });

    $("#guardarDocumentos").submit(function(e){
        e.preventDefault();
        abrir($(this),e,false);
    });
</script>