<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$cg=new ControladorDossierPlaguicida();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_solicitud']=$id_solicitud;
	$dato['nivel']=intval($_POST['nivel']);
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){
			case "P2":
				break;
			case "P3":
				break;
			case "P4":
				$dato['producto_nombre'] = htmlspecialchars ($_POST['producto_nombre'],ENT_NOQUOTES,'UTF-8');
				
				break;
			case "P5":
				$dato['fabricante_certificado'] = htmlspecialchars ($_POST['fabricante_certificado'],ENT_NOQUOTES,'UTF-8');
				$dato['formulador_certificado'] = htmlspecialchars ($_POST['formulador_certificado'],ENT_NOQUOTES,'UTF-8');
				$dato['formulador_acreditacion'] = htmlspecialchars ($_POST['formulador_acreditacion'],ENT_NOQUOTES,'UTF-8');
				$dato['informe_analisis'] = htmlspecialchars ($_POST['informe_analisis'],ENT_NOQUOTES,'UTF-8');
				$dato['declaracion_juramentada'] = htmlspecialchars ($_POST['declaracion_juramentada'],ENT_NOQUOTES,'UTF-8');
				$dato['libre_venta'] = htmlspecialchars ($_POST['libre_venta'],ENT_NOQUOTES,'UTF-8');
				break;
			case "P6":
				$dato['composicion_sustancias'] = htmlspecialchars ($_POST['composicion_sustancias'],ENT_NOQUOTES,'UTF-8');
				$dato['composicion_sustancias_ref'] = htmlspecialchars ($_POST['composicion_sustancias_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['composicion_naturaleza'] = htmlspecialchars ($_POST['composicion_naturaleza'],ENT_NOQUOTES,'UTF-8');
				$dato['composicion_naturaleza_ref'] = htmlspecialchars ($_POST['composicion_naturaleza_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['composicion_metodo'] = htmlspecialchars ($_POST['composicion_metodo'],ENT_NOQUOTES,'UTF-8');
				$dato['composicion_metodo_ref'] = htmlspecialchars ($_POST['composicion_metodo_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['estado_fisico'] = htmlspecialchars ($_POST['estado_fisico'],ENT_NOQUOTES,'UTF-8');
				$dato['color'] = htmlspecialchars ($_POST['color'],ENT_NOQUOTES,'UTF-8');
				$dato['color_ref'] = htmlspecialchars ($_POST['color_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['olor'] = htmlspecialchars ($_POST['olor'],ENT_NOQUOTES,'UTF-8');
				$dato['olor_ref'] = htmlspecialchars ($_POST['olor_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['estabilidad'] = htmlspecialchars ($_POST['estabilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['estabilidad_ref'] = htmlspecialchars ($_POST['estabilidad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['densidad'] = htmlspecialchars ($_POST['densidad'],ENT_NOQUOTES,'UTF-8');
				$dato['densidad_ref'] = htmlspecialchars ($_POST['densidad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['punto_inflamacion'] = htmlspecialchars ($_POST['punto_inflamacion'],ENT_NOQUOTES,'UTF-8');
				$dato['inflamacion_es_solido'] =$ce->normalizarBoolean($_POST['inflamacion_es_solido']);
				$dato['inflamacion_adjunto'] = htmlspecialchars ($_POST['inflamacion_adjunto'],ENT_NOQUOTES,'UTF-8');
				$dato['ph'] = round ($_POST['ph'],1);
				$dato['ph_ref'] = htmlspecialchars ($_POST['ph_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['explosivo_referencia'] = htmlspecialchars ($_POST['explosivo_referencia'],ENT_NOQUOTES,'UTF-8');
				$dato['es_explosivo'] = $ce->normalizarBoolean($_POST['es_explosivo']);

				break;
			case "P7":
				$dato['humedad'] = htmlspecialchars ($_POST['humedad'],ENT_NOQUOTES,'UTF-8');
				$dato['humedad_ref'] = htmlspecialchars ($_POST['humedad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['persistencia'] = htmlspecialchars ($_POST['persistencia'],ENT_NOQUOTES,'UTF-8');
				$dato['persistencia_ref'] = htmlspecialchars ($_POST['persistencia_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['suspensibilidad'] = htmlspecialchars ($_POST['suspensibilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['suspensibilidad_ref'] = htmlspecialchars ($_POST['suspensibilidad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['granulometria_humedo'] = htmlspecialchars ($_POST['granulometria_humedo'],ENT_NOQUOTES,'UTF-8');
				$dato['granulometria_humedo_ref'] = htmlspecialchars ($_POST['granulometria_humedo_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['granulometria_seco'] = htmlspecialchars ($_POST['granulometria_seco'],ENT_NOQUOTES,'UTF-8');
				$dato['granulometria_seco_ref'] = htmlspecialchars ($_POST['granulometria_seco_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['estabilidad_emulsion'] = htmlspecialchars ($_POST['estabilidad_emulsion'],ENT_NOQUOTES,'UTF-8');
				$dato['estabilidad_emulsion_ref'] = htmlspecialchars ($_POST['estabilidad_emulsion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['corrosivo_ref'] = htmlspecialchars ($_POST['corrosivo_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['es_corrosivo'] = $ce->normalizarBoolean($_POST['es_corrosivo']);
				$dato['incompatibilidad'] = htmlspecialchars ($_POST['incompatibilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['incompatibilidad_ref'] = htmlspecialchars ($_POST['incompatibilidad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['viscosidad'] = htmlspecialchars ($_POST['viscosidad'],ENT_NOQUOTES,'UTF-8');
				$dato['viscosidad_ref'] = htmlspecialchars ($_POST['viscosidad_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sulfonacion'] = htmlspecialchars ($_POST['sulfonacion'],ENT_NOQUOTES,'UTF-8');
				$dato['sulfonacion_ref'] = htmlspecialchars ($_POST['sulfonacion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['dispersion'] = htmlspecialchars ($_POST['dispersion'],ENT_NOQUOTES,'UTF-8');
				$dato['dispersion_ref'] = htmlspecialchars ($_POST['dispersion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['desprendimiento'] = htmlspecialchars ($_POST['desprendimiento'],ENT_NOQUOTES,'UTF-8');
				$dato['desprendimiento_ref'] = htmlspecialchars ($_POST['desprendimiento_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['soltura'] = htmlspecialchars ($_POST['soltura'],ENT_NOQUOTES,'UTF-8');
				$dato['soltura_ref'] = htmlspecialchars ($_POST['soltura_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['indice_yodo'] = htmlspecialchars ($_POST['indice_yodo'],ENT_NOQUOTES,'UTF-8');
				$dato['indice_yodo_ref'] = htmlspecialchars ($_POST['indice_yodo_ref'],ENT_NOQUOTES,'UTF-8');

				$dato['id_informe_final'] = htmlspecialchars ($_POST['id_informe_final'],ENT_NOQUOTES,'UTF-8');
				$dato['reingreso'] = htmlspecialchars ($_POST['reingreso'],ENT_NOQUOTES,'UTF-8');
				$dato['carencia'] = htmlspecialchars ($_POST['carencia'],ENT_NOQUOTES,'UTF-8');
				$dato['carencia_ref'] = htmlspecialchars ($_POST['carencia_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['efectos_cultivos'] = htmlspecialchars ($_POST['efectos_cultivos'],ENT_NOQUOTES,'UTF-8');
				$dato['efectos_cultivos_ref'] = htmlspecialchars ($_POST['efectos_cultivos_ref'],ENT_NOQUOTES,'UTF-8');

				break;
			case "P8":
				$dato['precaucion_uso'] = htmlspecialchars ($_POST['precaucion_uso'],ENT_NOQUOTES,'UTF-8');
				$dato['almacenamieno_manejo'] = htmlspecialchars ($_POST['almacenamieno_manejo'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_ingestion'] = htmlspecialchars ($_POST['aux_ingestion'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_telefono'] = htmlspecialchars ($_POST['aux_telefono'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_disposicion'] = htmlspecialchars ($_POST['aux_disposicion'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_ambiente'] = htmlspecialchars ($_POST['aux_ambiente'],ENT_NOQUOTES,'UTF-8');
				$dato['aux_instrucciones'] = htmlspecialchars ($_POST['aux_instrucciones'],ENT_NOQUOTES,'UTF-8');
				$dato['frecuencia_aplicacion'] = htmlspecialchars ($_POST['frecuencia_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['responsabilidad'] = htmlspecialchars ($_POST['responsabilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['paraquat'] = htmlspecialchars ($_POST['paraquat'],ENT_NOQUOTES,'UTF-8');
				$dato['tiene_hoja_informativa'] = $ce->normalizarBoolean($_POST['tiene_hoja_informativa']);
				$dato['hoja_informativa'] = htmlspecialchars ($_POST['hoja_informativa'],ENT_NOQUOTES,'UTF-8');
				$dato['hoja_informativa_ref'] = htmlspecialchars ($_POST['hoja_informativa_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_tipo'] = htmlspecialchars ($_POST['envase_tipo'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_material'] = htmlspecialchars ($_POST['envase_material'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_capacidad'] = htmlspecialchars ($_POST['envase_capacidad'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_resistencia'] = htmlspecialchars ($_POST['envase_resistencia'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_resistencia_ref'] = htmlspecialchars ($_POST['envase_resistencia_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['embalaje_tipo'] = htmlspecialchars ($_POST['embalaje_tipo'],ENT_NOQUOTES,'UTF-8');
				$dato['embalaje_material'] = htmlspecialchars ($_POST['embalaje_material'],ENT_NOQUOTES,'UTF-8');
				$dato['embalaje_capacidad'] = htmlspecialchars ($_POST['embalaje_capacidad'],ENT_NOQUOTES,'UTF-8');
				$dato['embalaje_resistencia'] = htmlspecialchars ($_POST['embalaje_resistencia'],ENT_NOQUOTES,'UTF-8');
				$dato['embalaje_resistencia_ref'] = htmlspecialchars ($_POST['embalaje_resistencia_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['accion_envases'] = htmlspecialchars ($_POST['accion_envases'],ENT_NOQUOTES,'UTF-8');
				$dato['destruccion_envaces'] = htmlspecialchars ($_POST['destruccion_envaces'],ENT_NOQUOTES,'UTF-8');
				break;
			case "P9":
				$dato['sobra_destruccion'] = htmlspecialchars ($_POST['sobra_destruccion'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_destruccion_ref'] = htmlspecialchars ($_POST['sobra_destruccion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_residuos'] = htmlspecialchars ($_POST['sobra_residuos'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_residuos_ref'] = htmlspecialchars ($_POST['sobra_residuos_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_recuperacion'] = htmlspecialchars ($_POST['sobra_recuperacion'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_recuperacion_ref'] = htmlspecialchars ($_POST['sobra_recuperacion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_neutralizacion'] = htmlspecialchars ($_POST['sobra_neutralizacion'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_neutralizacion_ref'] = htmlspecialchars ($_POST['sobra_neutralizacion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_incineracion'] = htmlspecialchars ($_POST['sobra_incineracion'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_incineracion_ref'] = htmlspecialchars ($_POST['sobra_incineracion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_depuracion'] = htmlspecialchars ($_POST['sobra_depuracion'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_depuracion_ref'] = htmlspecialchars ($_POST['sobra_depuracion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_precauciones'] = htmlspecialchars ($_POST['sobra_precauciones'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_precauciones_ref'] = htmlspecialchars ($_POST['sobra_precauciones_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_incendio'] = htmlspecialchars ($_POST['sobra_incendio'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_incendio_ref'] = htmlspecialchars ($_POST['sobra_incendio_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_equipo'] = htmlspecialchars ($_POST['sobra_equipo'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_equipo_ref'] = htmlspecialchars ($_POST['sobra_equipo_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_limpieza'] = htmlspecialchars ($_POST['sobra_limpieza'],ENT_NOQUOTES,'UTF-8');
				$dato['sobra_limpieza_ref'] = htmlspecialchars ($_POST['sobra_limpieza_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_obtenidos'] = htmlspecialchars ($_POST['residuo_obtenidos'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_obtenidos_ref'] = htmlspecialchars ($_POST['residuo_obtenidos_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_hoja'] = htmlspecialchars ($_POST['residuo_hoja'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_hoja_ref'] = htmlspecialchars ($_POST['residuo_hoja_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_evaluacion'] = htmlspecialchars ($_POST['residuo_evaluacion'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_evaluacion_ref'] = htmlspecialchars ($_POST['residuo_evaluacion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_obtenidos'] = htmlspecialchars ($_POST['residuo_obtenidos'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_obtenidos_ref'] = htmlspecialchars ($_POST['residuo_obtenidos_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_hoja'] = htmlspecialchars ($_POST['residuo_hoja'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_hoja_ref'] = htmlspecialchars ($_POST['residuo_hoja_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_evaluacion'] = htmlspecialchars ($_POST['residuo_evaluacion'],ENT_NOQUOTES,'UTF-8');
				$dato['residuo_evaluacion_ref'] = htmlspecialchars ($_POST['residuo_evaluacion_ref'],ENT_NOQUOTES,'UTF-8');
				$dato['tiene_aditivos_toxicos'] = $ce->normalizarBoolean($_POST['tiene_aditivos_toxicos']);

				break;
			case "P10":
				$archivo = htmlspecialchars ($_POST['rutaArchivo'],ENT_NOQUOTES,'UTF-8');
				$referencia = htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8');
				$fase = htmlspecialchars ($_POST['fase'],ENT_NOQUOTES,'UTF-8');
				$tipoArchivo=htmlspecialchars ($_POST['tipoArchivo'],ENT_NOQUOTES,'UTF-8');
				$cg->agregarArchivoAnexo($conexion, $id_solicitud,$archivo,$referencia,$fase,$identificador,$tipoArchivo);
				$mensaje['datos']=$cg->imprimirArchivosAnexos($conexion, $id_solicitud);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El documento ha sido cargado.';
				$esGuardar=false;

				break;
			case "P11":
				break;
		}
		if($esGuardar){
			$res=$cg->guardarSolicitud($conexion,$dato);
			if($res['tipo']=="insert")
				$idProtocolo = $res['resultado'][0]['id_solicitud'];
			else
				$fila=$res['resultado'];

			$mensaje['id'] = $idProtocolo;
			$mensaje['dato'] = $res['resultado'];
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido actualizada';
		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

