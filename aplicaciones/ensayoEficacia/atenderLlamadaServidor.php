<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once './clases/Transaccion.php';

$conexion = new Transaccion();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion_llamada = $_POST['opcion_llamada'];
	switch($opcion_llamada){
		case 'obtenerTitulo':
			$id_protocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
			$esInformeFinal=false;
			if($_POST['esInformeFinal']=='SI')
				$esInformeFinal=true;
			$titulo=$ce->generarTituloDelEnsayo($conexion,$id_protocolo,$esInformeFinal);
			$mensaje['mensaje'] = $titulo;
			$mensaje['estado'] = 'OK';
			break;
		case 'obtenerLocalizacion':
			$codigo = htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
			$categoria = htmlspecialchars ($_POST['categoria'],ENT_NOQUOTES,'UTF-8');

			$cc = new ControladorCatalogos();
			
			$items=$cc->obtenerLocalizacionHijo($conexion,$categoria,'categoria is not null',$codigo);
			$catalogoLocalidades=array();
			while ($fila = pg_fetch_assoc($items)){
				$catalogoLocalidades[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
			}
			$mensaje['mensaje'] = $catalogoLocalidades;
			$mensaje['estado'] = 'OK';
			break;
		case 'agregarPlagaDeclarada':
			$id_solicitud=htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
		   $datos['id_protocolo'] = $id_solicitud;
			$datos['plaga_codigo'] = htmlspecialchars ($_POST['plaga_codigo'],ENT_NOQUOTES,'UTF-8');
			$datos['plaga_codigo_comun'] = htmlspecialchars ($_POST['plaga_codigo_comun'],ENT_NOQUOTES,'UTF-8');
		   $datos = $ce->guardarPlagaProtocolo($conexion,$datos);
			$datos = $ce->obtenerPlagasProtocolo($conexion,$id_solicitud);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;
		case 'actualizarPlagaDeclarada':
			$id_solicitud=htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
			$datos['id_protocolo'] = $id_solicitud;
		   $datos['id_protocolo_plagas'] = htmlspecialchars ($_POST['id_protocolo_plagas'],ENT_NOQUOTES,'UTF-8');

			$datos['clase'] = htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
			$datos['orden'] = htmlspecialchars ($_POST['orden'],ENT_NOQUOTES,'UTF-8');
			$datos['familia'] = htmlspecialchars ($_POST['familia'],ENT_NOQUOTES,'UTF-8');
			$datos['genero'] = htmlspecialchars ($_POST['genero'],ENT_NOQUOTES,'UTF-8');
			$datos['ciclo'] = trim(htmlspecialchars ($_POST['ciclo'],ENT_NOQUOTES,'UTF-8'));
			$datos['habito'] = trim(htmlspecialchars ($_POST['habito'],ENT_NOQUOTES,'UTF-8'));
			$datos['comportamiento'] = trim(htmlspecialchars ($_POST['comportamiento'],ENT_NOQUOTES,'UTF-8'));
			$datos['estadio'] = trim(htmlspecialchars ($_POST['estadio'],ENT_NOQUOTES,'UTF-8'));
		   $datos = $ce->guardarPlagaProtocolo($conexion,$datos);
			$datos = $ce->obtenerPlagasProtocolo($conexion,$id_solicitud);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;
		case 'obtenerPlagaDeclarada':
		   $id_solicitud = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
		   $datos = $ce->obtenerPlagasProtocolo($conexion,$id_solicitud);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;
		case 'borrarPlagaDeclarada':
			$id_solicitud = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
			$id_protocolo_plagas=htmlspecialchars ($_POST['id_protocolo_plagas'],ENT_NOQUOTES,'UTF-8');
		   $ce->borrarPlagaDeclaradaProtocolo($conexion, $id_protocolo_plagas);
		   $datos = $ce->obtenerPlagasProtocolo($conexion,$id_solicitud);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;

		case 'borraFilaArchivoAnexo':
		   $idProtocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
		   $archivo=htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
			$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
			if($tipo=='')
				$tipo='EP';
			$ce->eliminarArchivoAnexo($conexion, $idProtocolo,$archivo,$tipo);

		   $datos = $ce->listarArchivosAnexos($conexion,$idProtocolo,$tipo);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;
		case 'borrarFilaArchivoAnexo':
		   $idProtocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
		   $id_protocolo_anexos=htmlspecialchars ($_POST['id_protocolo_anexos'],ENT_NOQUOTES,'UTF-8');
			$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
			if($tipo=='')
				$tipo='EP';
			$ce->borrarArchivoAnexo($conexion, $id_protocolo_anexos);

		   $datos = $ce->listarArchivosAnexos($conexion,$idProtocolo,$tipo);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;

		case 'guardarSubsanacionesEvaluaciones':
			$idProtocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
			$numero=intval($_POST['plagaNoEvaluacion']);
			try{
				$datoProtocolo=array();
				$datoProtocolo['id_protocolo']=$idProtocolo;
				$datoProtocolo['plaga_eval_numero']=$numero;

				$conexion->Begin();
				$ce->guardarProtocolo($conexion,$datoProtocolo);
				$ce -> borrarEvaluacionesPlagas($conexion,$idProtocolo);
				for($i=0;$i<$numero;$i++){
					$dato=array();
					$dato['id_protocolo']=$idProtocolo;
					$dato['nombre']=htmlspecialchars ($_POST['evalPlaga_nombre_'.$i],ENT_NOQUOTES,'UTF-8');
					$dato['intervalo']=intval ($_POST['evalPlaga_intervalo_'.$i]);
					$dato['observacion']=htmlspecialchars ($_POST['evalPlaga_observacion_'.$i],ENT_NOQUOTES,'UTF-8');
					$ce -> guardarEvaluacionesPlagas($conexion,$dato);
				}
				$conexion->Commit();
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Items de la plaga han sido guardado';
			}
			catch(Exception $e){
				$conexion->Rollback();
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Error al ejecutar sentencia";
			}

			break;
		case 'guardarSubsanacionesTratamientos':
			$idProtocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
			$numero=intval($_POST['noTratamientos']);
			if($numero>8){
				$numero=8;
			}
			try{
				$datoProtocolo=array();
				$datoProtocolo['id_protocolo']=$idProtocolo;
				$datoProtocolo['tratamientos']=$numero;

				$conexion->Begin();
				$ce->guardarProtocolo($conexion,$datoProtocolo);
				$ce -> borrarTratamientos($conexion,$idProtocolo);
				for($i=1;$i<=$numero;$i++){
					$codigo="$i";
					$dosis=htmlspecialchars ($_POST['tratamientoT'.$i],ENT_NOQUOTES,'UTF-8');
					$ce -> guardarTratamientos($conexion,$idProtocolo,$codigo,$dosis);
				}
				$conexion->Commit();
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Items de los tratamientos han sido guardado';
			}
			catch(Exception $e){
				$conexion->Rollback();
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Error al ejecutar sentencia";
			}

			break;

		}


	$conexion->desconectar();
}
catch(Exception $ex ){
	pg_close($conexion);
}

echo json_encode($mensaje);

?>