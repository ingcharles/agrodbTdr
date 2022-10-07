<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cp = new ControladorDossierPecuario();
$cr = new ControladorRegistroOperador();

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion_llamada = $_POST['opcion_llamada'];
	switch($opcion_llamada){
		case 'representantesTecnicosPorArea':
			$id_area = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
			$repTecnicos = $cp->obtenerRepresentantesTecnicos($conexion, $id_area);
			$mensaje['mensaje'] = $repTecnicos;
			$mensaje['estado'] = 'OK';
			break;
		case 'datosRepresentanteTecnico':
			$identificacion_representante = htmlspecialchars ($_POST['ci_representante_tecnico'],ENT_NOQUOTES,'UTF-8');
			$id_area = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
			$repTecnico = $cp->obtenerRepresentanteTecnico($conexion,$id_area, $identificacion_representante);

			$mensaje['mensaje'] = $repTecnico;
			$mensaje['estado'] = 'OK';
			break;

		case 'datosOperadorSitiosAreas':
			$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerOperadorConSitiosAreas($conexion, $identificador);

			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricantesDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerFabricantesDossier($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarFabricanteDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarFabricante($conexion, $id_solicitud_fabricante);
			$datos=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);
			$tieneExtranjero=0;
			foreach($datos as $items){
				if($items['tipo']=='E'){
					$tieneExtranjero=1;
					break;
				}
			}
			$mensaje['mensaje']=array();
			$mensaje['mensaje']['tieneExtranjero']=$tieneExtranjero;
			$mensaje['mensaje']['datos'] = $cp->imprimirFabricantesDosier($datos);
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarFabricantesDeSolicitudDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');

			$cp->eliminarFabricantesDeSolicitud($conexion, $id_solicitud);
			$datos=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'validarNombre':
			$areaTematica = htmlspecialchars ($_POST['areaTematica'],ENT_NOQUOTES,'UTF-8');
			$nombre = htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->validarNombreProductosPorAreaTematica ($conexion,$areaTematica,$nombre);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_elemento = htmlspecialchars ($_POST['id_elemento'],ENT_NOQUOTES,'UTF-8');
			$valor = htmlspecialchars ($_POST['valor'],ENT_NOQUOTES,'UTF-8');
			$unidad = htmlspecialchars ($_POST['unidad'],ENT_NOQUOTES,'UTF-8');
			$grupo = htmlspecialchars ($_POST['grupo'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->agregarComposicionProducto ($conexion,$id_solicitud,$grupo,$id_elemento,$valor,$unidad);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerComposicionProducto($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_composicion = htmlspecialchars ($_POST['id_solicitud_composicion'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarComposicionProducto($conexion, $id_solicitud_composicion);
			$datos=$cp->obtenerComposicionProducto($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerIA':
			$grupo = htmlspecialchars ($_POST['grupo'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerIAporGrupo ($conexion,$grupo);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarDosis':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_especie = htmlspecialchars ($_POST['id_especie'],ENT_NOQUOTES,'UTF-8');
			$id_via = htmlspecialchars ($_POST['id_via'],ENT_NOQUOTES,'UTF-8');
			$cantidad = htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$id_unidad1 = htmlspecialchars ($_POST['id_unidad1'],ENT_NOQUOTES,'UTF-8');
			$peso = htmlspecialchars ($_POST['peso'],ENT_NOQUOTES,'UTF-8');
			$id_unidad2 = htmlspecialchars ($_POST['id_unidad2'],ENT_NOQUOTES,'UTF-8');
			$duracion = htmlspecialchars ($_POST['duracion'],ENT_NOQUOTES,'UTF-8');
			$id_unidad3 = htmlspecialchars ($_POST['id_unidad3'],ENT_NOQUOTES,'UTF-8');
			$id_subtipo_producto=htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
			$detalle = htmlspecialchars ($_POST['detalle'],ENT_NOQUOTES,'UTF-8');
			$referencia= htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8');
			$modo_aplicacion=htmlspecialchars ($_POST['modo_aplicacion'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->agregarDosis($conexion,$id_solicitud,$id_especie,$id_via,$cantidad,$id_unidad1,$peso,$id_unidad2,$duracion,$id_unidad3,$id_subtipo_producto,$detalle,$referencia,$modo_aplicacion);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerDosis':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarDosis':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_dosis = htmlspecialchars ($_POST['id_solicitud_dosis'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarDosis($conexion, $id_solicitud_dosis);
			$datos=$cp->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarEfectos':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$codigo=htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
			$descripcion = trim(htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8'));
			$referencia = htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->agregarEfectosNoDeseados($conexion,$id_solicitud,$codigo,$descripcion,$referencia);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerEfectos':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarEfecto':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_efecto = htmlspecialchars ($_POST['id_solicitud_efecto'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarEfectosNoDeseados($conexion, $id_solicitud_efecto);
			$datos=$cp->obtenerEfectosNoDeseados($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_especie=htmlspecialchars ($_POST['id_especie'],ENT_NOQUOTES,'UTF-8');
			$id_consumible = htmlspecialchars ($_POST['id_consumible'],ENT_NOQUOTES,'UTF-8');
			$tiempo = htmlspecialchars ($_POST['tiempo'],ENT_NOQUOTES,'UTF-8');
			$id_unidad = htmlspecialchars ($_POST['id_unidad'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->agregarPeriodosDeRetiro($conexion,$id_solicitud,$id_especie,$id_consumible,$tiempo,$id_unidad);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_retiro = htmlspecialchars ($_POST['id_solicitud_retiro'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarPeriodosDeRetiro($conexion, $id_solicitud_retiro);
			$datos=$cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;


		case 'agregarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$presentacion=htmlspecialchars ($_POST['presentacion'],ENT_NOQUOTES,'UTF-8');
			$cantidad=htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$id_unidad_medida = htmlspecialchars ($_POST['id_unidad_medida'],ENT_NOQUOTES,'UTF-8');
			$descripcion = trim(htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8'));
			$datos = $cp->agregarPresentacion($conexion,$id_solicitud,$presentacion,$cantidad,$id_unidad_medida,$descripcion);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cp->obtenerPresentacion($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_presentacion = htmlspecialchars ($_POST['id_solicitud_presentacion'],ENT_NOQUOTES,'UTF-8');
			$cp->eliminarPresentacion($conexion, $id_solicitud_presentacion);
			$datos=$cp->obtenerPresentacion($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerOperador':
			$rucOperador = htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');
			$query=$cr->buscarOperador($conexion,$rucOperador);
			$datos=array();
			if(pg_num_rows($query)>0){
				$datos=pg_fetch_assoc($query,0);				
			}
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		}


	$conexion->desconectar();
}
catch(Exception $ex ){
	pg_close($conexion);
}

echo json_encode($mensaje);

?>