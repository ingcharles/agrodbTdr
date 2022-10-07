<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierFertilizante.php';

$conexion = new Conexion();
$cf = new ControladorDossierFertilizante();

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion_llamada = $_POST['opcion_llamada'];
	switch($opcion_llamada){

		case 'agregarCultivoDeclarado':
               $id_solicitud=htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
         $datos['id_solicitud'] = $id_solicitud;
               $datos['cultivo_codigo'] = htmlspecialchars ($_POST['cultivo_codigo'],ENT_NOQUOTES,'UTF-8');
         $datos = $cf->guardarCultivoProtocolo($conexion,$datos);
               $datos = $cf->obtenerCultivosProtocolo($conexion,$id_solicitud);
         $mensaje['mensaje'] = $datos;
         $mensaje['estado'] = 'OK';
         break;

		case 'actualizarPlagaDeclarada':
			$id_solicitud=htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos['id_solicitud'] = $id_solicitud;
		   $datos['id_solicitud_plagas'] = htmlspecialchars ($_POST['id_solicitud_plagas'],ENT_NOQUOTES,'UTF-8');
			
			$datos['clase'] = htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
			$datos['orden'] = htmlspecialchars ($_POST['orden'],ENT_NOQUOTES,'UTF-8');
			$datos['familia'] = htmlspecialchars ($_POST['familia'],ENT_NOQUOTES,'UTF-8');
			$datos['genero'] = htmlspecialchars ($_POST['genero'],ENT_NOQUOTES,'UTF-8');
			$datos['ciclo'] = htmlspecialchars ($_POST['ciclo'],ENT_NOQUOTES,'UTF-8');
			$datos['habito'] = htmlspecialchars ($_POST['habito'],ENT_NOQUOTES,'UTF-8');
			$datos['comportamiento'] = htmlspecialchars ($_POST['comportamiento'],ENT_NOQUOTES,'UTF-8');
			$datos['estadio'] = htmlspecialchars ($_POST['estadio'],ENT_NOQUOTES,'UTF-8');
                       $datos = $elegirCultivoDeclarado->guardarPlagaProtocolo($conexion,$datos);
			$datos = $elegirCultivoDeclarado->obtenerPlagasProtocolo($conexion,$id_solicitud);
                       $mensaje['mensaje'] = $datos;
                       $mensaje['estado'] = 'OK';
                       break;

		case 'obtenerPlagaDeclarada':
         $id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
         $datos = $elegirCultivoDeclarado->obtenerPlagasProtocolo($conexion,$id_solicitud);
         $mensaje['mensaje'] = $datos;
         $mensaje['estado'] = 'OK';
         break;

		case 'representantesTecnicosPorArea':
			$id_area = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
			$repTecnicos = $cf->obtenerRepresentantesTecnicos($conexion, $id_area);
			$mensaje['mensaje'] = $repTecnicos;
			$mensaje['estado'] = 'OK';
			break;

		case 'datosRepresentanteTecnico':
			$identificacion_representante = htmlspecialchars ($_POST['ci_representante_tecnico'],ENT_NOQUOTES,'UTF-8');
			$id_area = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
			$repTecnico = $cf->obtenerRepresentanteTecnico($conexion,$id_area, $identificacion_representante);

			$mensaje['mensaje'] = $repTecnico;
			$mensaje['estado'] = 'OK';
			break;

		case 'datosOperadorSitiosAreas':
			$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerOperadorConSitiosAreas($conexion, $identificador);

			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarFabricanteDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$ruc=htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');
			$id_sitio=$_POST['sitio'];
			$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$razon=htmlspecialchars ($_POST['razon'],ENT_NOQUOTES,'UTF-8');
			$id_pais=htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');

			$datos = $cf->agregarFabricanteDossier($conexion,$id_solicitud,$ruc,$id_sitio,$tipo,$direccion,$razon,$id_pais);

			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricantesDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerFabricantesDossier($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarFabricanteDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarFabricante($conexion, $id_solicitud_fabricante);
			$datos=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarFabricantesDeSolicitudDossier':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			
			$cf->eliminarFabricantesDeSolicitud($conexion, $id_solicitud);
			$datos=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		
		case 'validarNombre':
			$areaTematica = htmlspecialchars ($_POST['areaTematica'],ENT_NOQUOTES,'UTF-8');
			$nombre = htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->validarNombreProductosPorAreaTematica ($conexion,$areaTematica,$nombre);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		
		case 'agregarComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$elemento = htmlspecialchars ($_POST['id_elemento'],ENT_NOQUOTES,'UTF-8');
			$valor = htmlspecialchars ($_POST['valor'],ENT_NOQUOTES,'UTF-8');
			$unidad = htmlspecialchars ($_POST['unidad'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->agregarComposicionProducto ($conexion,$id_solicitud,$elemento,$valor,$unidad);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerComposicionProducto($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarComposicion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_composicion = htmlspecialchars ($_POST['id_solicitud_composicion'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarComposicionProducto($conexion, $id_solicitud_composicion);
			$datos=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerIA':
			$grupo = htmlspecialchars ($_POST['grupo'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerIAporGrupo ($conexion,$grupo);
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
			$datos = $cf->agregarDosis($conexion,$id_solicitud,$id_especie,$id_via,$cantidad,$id_unidad1,$peso,$id_unidad2,$duracion,$id_unidad3,$id_subtipo_producto,$detalle,$referencia);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerDosis':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarDosis':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_dosis = htmlspecialchars ($_POST['id_solicitud_dosis'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarDosis($conexion, $id_solicitud_dosis);
			$datos=$cf->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarEfectos':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$codigo=htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
			$descripcion = htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->agregarEfectosNoDeseados($conexion,$id_solicitud,$codigo,$descripcion);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerEfectos':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerDosis($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarEfecto':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_efecto = htmlspecialchars ($_POST['id_solicitud_efecto'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarEfectosNoDeseados($conexion, $id_solicitud_efecto);
			$datos=$cf->obtenerEfectosNoDeseados($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_especie=htmlspecialchars ($_POST['id_especie'],ENT_NOQUOTES,'UTF-8');
			$id_consumible = htmlspecialchars ($_POST['id_consumible'],ENT_NOQUOTES,'UTF-8');
			$tiempo = htmlspecialchars ($_POST['tiempo'],ENT_NOQUOTES,'UTF-8');
			$id_unidad = htmlspecialchars ($_POST['id_unidad'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->agregarPeriodosDeRetiro($conexion,$id_solicitud,$id_especie,$id_consumible,$tiempo,$id_unidad);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarTiemposRetiro':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_retiro = htmlspecialchars ($_POST['id_solicitud_retiro'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarPeriodosDeRetiro($conexion, $id_solicitud_retiro);
			$datos=$cf->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;


		case 'agregarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$presentacion=htmlspecialchars ($_POST['presentacion'],ENT_NOQUOTES,'UTF-8');
			$cantidad=htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$id_unidad_medida = htmlspecialchars ($_POST['id_unidad_medida'],ENT_NOQUOTES,'UTF-8');
			$descripcion = htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->agregarPresentacion($conexion,$id_solicitud,$presentacion,$cantidad,$id_unidad_medida,$descripcion);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$datos = $cf->obtenerPresentacion($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;
		case 'borrarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_presentacion = htmlspecialchars ($_POST['id_solicitud_presentacion'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarPresentacion($conexion, $id_solicitud_presentacion);
			$datos=$cf->obtenerPresentacion($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borraFilaArchivoAnexo':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_anexos = htmlspecialchars ($_POST['id_solicitud_anexos'],ENT_NOQUOTES,'UTF-8');
			$cf->eliminarArchivoAnexo($conexion,$id_solicitud_anexos);
			$datos=$cf->listarArchivosAnexos($conexion,$id_solicitud);
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