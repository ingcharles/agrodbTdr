<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

$conexion = new Conexion();
$cg=new ControladorDossierPlaguicida();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idUsuario= $_SESSION['usuario'];
	$pasoOpcion= $_POST['pasoOpcion'];
	$idSolicitud=$_POST['id_solicitud'];
	$idProtocoloIa=$_POST['id_protocolo_ia'];
	$ingrediente=$cg->obtenerIngredienteSolicitudProtocolo($conexion,$idSolicitud,$idProtocoloIa);
	$dato=array();
	if($ingrediente['id_solicitud_ia']==null){
		$dato['id_solicitud'] = $_POST['id_solicitud'];
		$dato['id_protocolo_ia'] = $_POST['id_protocolo_ia'];
	}else{
		$dato['id_solicitud_ia'] = $ingrediente['id_solicitud_ia'];
	}


	$id_solicitud_ia = $_POST['id_solicitud_ia'];

	$paraGuardar=true;
	switch($pasoOpcion){
		case '1':
			
			$dato['id_ingrediente_activo'] = $_POST['id_ingrediente_activo'];
			$dato['tiene_carta_acceso']=$ce->normalizarBoolean($_POST['tiene_carta_acceso']);
			$dato['carta_acceso'] = htmlspecialchars ($_POST['carta_acceso'],ENT_NOQUOTES,'UTF-8');
			$dato['formula_estructural'] = htmlspecialchars ($_POST['formula_estructural'],ENT_NOQUOTES,'UTF-8');
			$dato['formula_estructural_ruta'] = htmlspecialchars ($_POST['rutaArchivo'],ENT_NOQUOTES,'UTF-8');
			$dato['grado_pureza'] = htmlspecialchars ($_POST['grado_pureza'],ENT_NOQUOTES,'UTF-8');
			$dato['isomeros'] = htmlspecialchars ($_POST['isomeros'],ENT_NOQUOTES,'UTF-8');
			$dato['impurezas'] = htmlspecialchars ($_POST['impurezas'],ENT_NOQUOTES,'UTF-8');
			$dato['aditivos'] = htmlspecialchars ($_POST['aditivos'],ENT_NOQUOTES,'UTF-8');
			$dato['peso_molecular'] = htmlspecialchars ($_POST['peso'],ENT_NOQUOTES,'UTF-8');

			$dato['peso_molecular_ref'] = htmlspecialchars ($_POST['peso_molecular_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['formula_estructural_ref'] = htmlspecialchars ($_POST['formula_estructural_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['grado_pureza_ref'] = htmlspecialchars ($_POST['grado_pureza_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['isomeros_ref'] = htmlspecialchars ($_POST['isomeros_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['impurezas_ref'] = htmlspecialchars ($_POST['impurezas_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['aditivos_ref'] = htmlspecialchars ($_POST['aditivos_ref'],ENT_NOQUOTES,'UTF-8');
			



			break;
		case '2':
			$paraGuardar=false;
			break;
		case '3':

			$dato['estado_fisico'] = htmlspecialchars ($_POST['estado_fisico'],ENT_NOQUOTES,'UTF-8');
			$dato['color'] = htmlspecialchars ($_POST['color'],ENT_NOQUOTES,'UTF-8');
			$dato['olor'] = htmlspecialchars ($_POST['olor'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_fusion'] = htmlspecialchars ($_POST['punto_fusion'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_ebullicion'] = htmlspecialchars ($_POST['punto_ebullicion'],ENT_NOQUOTES,'UTF-8');
			$dato['densidad'] = htmlspecialchars ($_POST['densidad'],ENT_NOQUOTES,'UTF-8');
			$dato['presion_vapor'] = htmlspecialchars ($_POST['presion_vapor'],ENT_NOQUOTES,'UTF-8');
			$dato['espectro_absorcion'] = htmlspecialchars ($_POST['espectro_absorcion'],ENT_NOQUOTES,'UTF-8');
			$dato['solubilidad_agua'] = htmlspecialchars ($_POST['solubilidad_agua'],ENT_NOQUOTES,'UTF-8');
			$dato['solubilidad_disolventes'] = htmlspecialchars ($_POST['solubilidad_disolventes'],ENT_NOQUOTES,'UTF-8');

			$dato['coeficiente_particion'] = htmlspecialchars ($_POST['coeficiente_particion'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_ignicion'] = htmlspecialchars ($_POST['punto_ignicion'],ENT_NOQUOTES,'UTF-8');
			$dato['tension_superficial'] = htmlspecialchars ($_POST['tension_superficial'],ENT_NOQUOTES,'UTF-8');
			$dato['propiedades_explosivas'] = htmlspecialchars ($_POST['propiedades_explosivas'],ENT_NOQUOTES,'UTF-8');
			$dato['propiedades_oxidantes'] = htmlspecialchars ($_POST['propiedades_oxidantes'],ENT_NOQUOTES,'UTF-8');
			$dato['reactividad_envase'] = htmlspecialchars ($_POST['reactividad_envase'],ENT_NOQUOTES,'UTF-8');
			$dato['viscosidad'] = htmlspecialchars ($_POST['viscosidad'],ENT_NOQUOTES,'UTF-8');

			$dato['estado_fisico_ref'] = htmlspecialchars ($_POST['estado_fisico_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['color_ref'] = htmlspecialchars ($_POST['color_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['olor_ref'] = htmlspecialchars ($_POST['olor_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_fusion_ref'] = htmlspecialchars ($_POST['punto_fusion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_ebullicion_ref'] = htmlspecialchars ($_POST['punto_ebullicion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['densidad_ref'] = htmlspecialchars ($_POST['densidad_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['presion_vapor_ref'] = htmlspecialchars ($_POST['presion_vapor_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['espectro_absorcion_ref'] = htmlspecialchars ($_POST['espectro_absorcion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['solubilidad_agua_ref'] = htmlspecialchars ($_POST['solubilidad_agua_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['solubilidad_disolventes_ref'] = htmlspecialchars ($_POST['solubilidad_disolventes_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['coeficiente_particion_ref'] = htmlspecialchars ($_POST['coeficiente_particion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['punto_ignicion_ref'] = htmlspecialchars ($_POST['punto_ignicion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['tension_superficial_ref'] = htmlspecialchars ($_POST['tension_superficial_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['propiedades_explosivas_ref'] = htmlspecialchars ($_POST['propiedades_explosivas_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['propiedades_oxidantes_ref'] = htmlspecialchars ($_POST['propiedades_oxidantes_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['reactividad_envase_ref'] = htmlspecialchars ($_POST['reactividad_envase_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['viscosidad_ref'] = htmlspecialchars ($_POST['viscosidad_ref'],ENT_NOQUOTES,'UTF-8');
			

			break;
		case '4':
			$dato['modo_accion'] = htmlspecialchars ($_POST['modo_accion'],ENT_NOQUOTES,'UTF-8');
			$dato['organismos_nocivos'] = htmlspecialchars ($_POST['organismos_nocivos'],ENT_NOQUOTES,'UTF-8');
			$dato['mecanismo_accion'] = htmlspecialchars ($_POST['mecanismo_accion'],ENT_NOQUOTES,'UTF-8');
			$dato['ambito_aplicacion'] = htmlspecialchars ($_POST['ambito_aplicacion'],ENT_NOQUOTES,'UTF-8');
			$dato['condiciones_fitosanitarias'] = htmlspecialchars ($_POST['condiciones_fitosanitarias'],ENT_NOQUOTES,'UTF-8');
			$dato['resistencia'] = htmlspecialchars ($_POST['resistencia'],ENT_NOQUOTES,'UTF-8');
			$dato['hoja_seguridad'] = htmlspecialchars ($_POST['hoja_seguridad'],ENT_NOQUOTES,'UTF-8');

			$dato['modo_accion_ref'] = htmlspecialchars ($_POST['modo_accion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['organismos_nocivos_ref'] = htmlspecialchars ($_POST['organismos_nocivos_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['mecanismo_accion_ref'] = htmlspecialchars ($_POST['mecanismo_accion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['ambito_aplicacion_ref'] = htmlspecialchars ($_POST['ambito_aplicacion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['condiciones_fitosanitarias_ref'] = htmlspecialchars ($_POST['condiciones_fitosanitarias_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['resistencia_ref'] = htmlspecialchars ($_POST['resistencia_ref'],ENT_NOQUOTES,'UTF-8');
			
			break;
		case '5':
			$dato['metodo_sustancia'] = htmlspecialchars ($_POST['metodo_sustancia'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_sustancia_ref'] = htmlspecialchars ($_POST['metodo_sustancia_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_degradacion'] = htmlspecialchars ($_POST['metodo_degradacion'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_degradacion_ref'] = htmlspecialchars ($_POST['metodo_degradacion_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_residuos'] = htmlspecialchars ($_POST['metodo_residuos'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_residuos_ref'] = htmlspecialchars ($_POST['metodo_residuos_ref'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_aire'] = htmlspecialchars ($_POST['metodo_aire'],ENT_NOQUOTES,'UTF-8');
			$dato['metodo_aire_ref'] = htmlspecialchars ($_POST['metodo_aire_ref'],ENT_NOQUOTES,'UTF-8');

			break;
	}

	try {


		if($paraGuardar){
			$res=$cg->guardarIngredientesSolicitud($conexion,$dato);
			if($res['tipo']=="insert")
				$id_solicitud_ia = $res['resultado'][0]['id_solicitud_ia'];
			else
				$fila=$res['resultado'];

			$mensaje['id'] = $id_solicitud_ia;
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido guardada';
		}
		else{
			$mensaje['id'] = $id_solicitud_ia;
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Acción completada';
		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar petición";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}


?>