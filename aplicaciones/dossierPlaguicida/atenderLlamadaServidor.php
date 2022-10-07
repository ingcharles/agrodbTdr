<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../ensayoEficacia/clases/Transaccion.php';

$conexion = new Transaccion();
$cr = new ControladorRequisitos();
$cg = new ControladorDossierPlaguicida();
$ce = new ControladorEnsayoEficacia();


$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$datos=array();


try{
	$opcion_llamada = $_POST['opcion_llamada'];
	switch($opcion_llamada){
		
		case 'obtenerInformesFinales':
		   $id_protocolo = htmlspecialchars ($_POST['id_protocolo'],ENT_NOQUOTES,'UTF-8');
		   $datos['informes'] = $ce->obtenerInformesFinales($conexion,$id_protocolo,'RIA-IF');
			$datos['ia'] = $ce->obtenerIngredientesActivos($conexion,$id_protocolo);
			$datos['formulacion'] = $ce->obtenerFormulacion($conexion,$id_protocolo);

			$datos['protocolo'] = $ce->obtenerProtocolo($conexion,$id_protocolo);
			$datos['tieneParaquat']=$ce->contieneParaquat($conexion,$id_protocolo);
		   $mensaje['mensaje'] = $datos;

		   $mensaje['estado'] = 'OK';
		   break;
		
		case 'recuperarInformesFinales':
		   $id_registro = htmlspecialchars ($_POST['id_registro'],ENT_NOQUOTES,'UTF-8');
			$id_producto= htmlspecialchars ($_POST['id_producto'],ENT_NOQUOTES,'UTF-8');
		   $datos['informes'] = $ce->obtenerInformesFinalesDelRegistro($conexion,$id_registro);
			$res = $cr->listarUsos($conexion,$id_producto);
			$usos=array();
			while($fila = pg_fetch_assoc($res)){
				$usos[]=$fila;
			}
			$datos['usos']=$usos;
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;

		case 'obtenerDatosProductoMadre':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$registro = htmlspecialchars ($_POST['registro'],ENT_NOQUOTES,'UTF-8');
			$usos=array();			
			
		    $datos['ias']=$ce->obtenerIaXregistro($conexion,$registro);
		    if($datos['ias'][0]['id_producto']!=null){
    			$respuesta=$cr->mostrarDatosGeneralesDeProducto($conexion,$datos['ias'][0]['id_producto']);    			
    			while($fila = pg_fetch_assoc($respuesta)){
    			   $usos[]=$fila;
    			}
    			$datos['usos']=$usos;
		    }
			
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
			break;
		
		case 'guardarFabricanteManufacturador':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$carta = htmlspecialchars ($_POST['carta'],ENT_NOQUOTES,'UTF-8');

			$datos['id'] = $cg->actualizarFabricante($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta);
			if($datos['id']!=null){
				$data = json_decode($_POST['manufacturadores'], true);
				foreach($data as $key=>$value){
					$id_solicitud_fabricante = $datos['id'];
					$nombre=htmlspecialchars ($value['nombre'],ENT_NOQUOTES,'UTF-8');
					$id_pais = htmlspecialchars ($value['id_pais'],ENT_NOQUOTES,'UTF-8');
					$direccion = htmlspecialchars ($value['direccion'],ENT_NOQUOTES,'UTF-8');
					$representante_legal = htmlspecialchars ($value['representante_legal'],ENT_NOQUOTES,'UTF-8');
					$correo = htmlspecialchars ($value['correo'],ENT_NOQUOTES,'UTF-8');
					$telefono = htmlspecialchars ($value['telefono'],ENT_NOQUOTES,'UTF-8');
					$d = $cg->agregarManufacturador($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono);

				}

			}

			$datos['datos'] = $cg->obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarFabricante':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$carta = htmlspecialchars ($_POST['carta'],ENT_NOQUOTES,'UTF-8');
			$datos['id'] = $cg->actualizarFabricante($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta);

			$datos['datos'] = $cg->obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricante':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->obtenerFabricante($conexion,$id_solicitud_fabricante);
			$manufacturadores=$cg->obtenerManufacturadores($conexion,$id_solicitud_fabricante);
			$datos['manufacturadores']=$manufacturadores;
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricantes':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarFabricante':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$cg->eliminarFabricante($conexion, $id_solicitud_fabricante);
			$datos=$cg->obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarManufacturador':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->agregarManufacturador($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerManufacturadores':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');

			$datos = $cg->obtenerManufacturadores($conexion,$id_solicitud_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarManufacturador':
			$id_solicitud_manufacturador = htmlspecialchars ($_POST['id_solicitud_manufacturador'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');

			$cg->eliminarManufacturador($conexion, $id_solicitud_manufacturador);
			$datos=$cg->obtenerManufacturadores($conexion,$id_solicitud_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$presentacion_tipo=htmlspecialchars ($_POST['presentacion'],ENT_NOQUOTES,'UTF-8');
			$cantidad=htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$id_unidad_medida= htmlspecialchars ($_POST['id_unidad_medida'],ENT_NOQUOTES,'UTF-8');

			$partida_arancelaria= htmlspecialchars ($_POST['partida_arancelaria'],ENT_NOQUOTES,'UTF-8');
			$codigo_complementario= htmlspecialchars ($_POST['codigo_complementario'],ENT_NOQUOTES,'UTF-8');
			$codigo_suplementario= htmlspecialchars ($_POST['codigo_suplementario'],ENT_NOQUOTES,'UTF-8');

			$d = $cg->agregarPresentacion($conexion,$id_solicitud,$presentacion_tipo,$cantidad,$id_unidad_medida,$partida_arancelaria,$codigo_complementario,$codigo_suplementario);
			$datos = $cg->obtenerPresentaciones($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarPresentacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_presentacion = htmlspecialchars ($_POST['id_solicitud_presentacion'],ENT_NOQUOTES,'UTF-8');

			$cg->eliminarPresentacion($conexion, $id_solicitud_presentacion);
			$datos=$cg->obtenerPresentaciones($conexion,$id_solicitud);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarAditivoToxicologico':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$cantidad=htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$id_unidad= htmlspecialchars ($_POST['id_unidad'],ENT_NOQUOTES,'UTF-8');

			$d = $cg->agregarAditivoToxicologico($conexion,$id_solicitud,$nombre,$cantidad,$id_unidad);
			$str=$cg->imprimirAditivosToxicologicos($conexion,$id_solicitud);

			$mensaje['mensaje'] = $str;
			$mensaje['estado'] = 'OK';
			break;

		case "borrarAditivoToxicologico":
			$id_solicitud_aditivo = $_POST['id_solicitud_aditivo'];
			$id_solicitud = $_POST['id_solicitud'];
			$cg->eliminarAditivoToxicologico($conexion,$id_solicitud_aditivo);

			$str=$cg->imprimirAditivosToxicologicos($conexion,$id_solicitud);

			$mensaje['mensaje'] = $str;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarFilaArchivoAnexo':
		   $id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_anexo = htmlspecialchars ($_POST['id_solicitud_anexo'],ENT_NOQUOTES,'UTF-8');
			$cg->eliminarArchivoAnexo($conexion, $id_solicitud_anexo);

		   $datos = $cg->imprimirArchivosAnexos($conexion,$id_solicitud);
		   $mensaje['mensaje'] = $datos;
		   $mensaje['estado'] = 'OK';
		   break;

			//***************************  MODIFICACIONES ***********************************
		case 'guardarFabricanteManufacturadorModificacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$carta = htmlspecialchars ($_POST['carta'],ENT_NOQUOTES,'UTF-8');

			$datos['id'] = $cg->actualizarFabricanteModificacion($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta);
			if($datos['id']!=null){
				$data = json_decode($_POST['manufacturadores'], true);
				foreach($data as $key=>$value){
					$id_solicitud_fabricante = $datos['id'];
					$nombre=htmlspecialchars ($value['nombre'],ENT_NOQUOTES,'UTF-8');
					$id_pais = htmlspecialchars ($value['id_pais'],ENT_NOQUOTES,'UTF-8');
					$direccion = htmlspecialchars ($value['direccion'],ENT_NOQUOTES,'UTF-8');
					$representante_legal = htmlspecialchars ($value['representante_legal'],ENT_NOQUOTES,'UTF-8');
					$correo = htmlspecialchars ($value['correo'],ENT_NOQUOTES,'UTF-8');
					$telefono = htmlspecialchars ($value['telefono'],ENT_NOQUOTES,'UTF-8');
					$d = $cg->agregarManufacturadorModificacion($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono);

				}

			}

			$datos['datos'] = $cg->obtenerFabricantesModificacion($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarFabricanteModificacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$carta = htmlspecialchars ($_POST['carta'],ENT_NOQUOTES,'UTF-8');
			$datos['id'] = $cg->actualizarFabricanteModificacion($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta);

			$datos['datos'] = $cg->obtenerFabricantesModificacion($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricanteModificacion':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->obtenerFabricanteModificacion($conexion,$id_solicitud_fabricante);
			$manufacturadores=$cg->obtenerManufacturadoresModificacion($conexion,$id_solicitud_fabricante);
			$datos['manufacturadores']=$manufacturadores;
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerFabricantesModificacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->obtenerFabricantesModificacion($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarFabricanteModificacion':
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
			$cg->eliminarFabricanteModificacion($conexion, $id_solicitud_fabricante);
			$datos=$cg->obtenerFabricantesModificacion($conexion,$id_solicitud,$tipo_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'agregarManufacturadorModificacion':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			
			$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
			$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
			$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
			$representante_legal = htmlspecialchars ($_POST['representante_legal'],ENT_NOQUOTES,'UTF-8');
			$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
			$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->agregarManufacturadorModificacion($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'obtenerManufacturadoresModificacion':
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$datos = $cg->obtenerManufacturadoresModificacion($conexion,$id_solicitud_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'borrarManufacturadorModificacion':
			$id_solicitud_manufacturador = htmlspecialchars ($_POST['id_solicitud_manufacturador'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');
			$cg->eliminarManufacturadorModificacion($conexion, $id_solicitud_manufacturador);
			$datos=$cg->obtenerManufacturadoresModificacion($conexion,$id_solicitud_fabricante);
			$mensaje['mensaje'] = $datos;
			$mensaje['estado'] = 'OK';
			break;

		case 'guardarObservacionesIA':
			$id_tramite_flujo = htmlspecialchars ($_POST['id_tramite_flujo'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_ia = htmlspecialchars ($_POST['id_solicitud_ia'],ENT_NOQUOTES,'UTF-8');
			foreach($_POST as $key=>$item){
				if(substr($key,0,7)=='obs_GI_'){
					$formato=$ce->obtenerFormatoDelElemeno($conexion,'GI',substr($key,7));
					//miro si ya tiene observaciones a este punto
					$doc=$ce->obtenerObservacionDelTramite($conexion,$id_tramite_flujo,$formato['id_enlace'],$id_solicitud_ia);
					$revision=1;
					if(sizeof($doc)>0){
						$revision=$doc['revision'];
						$revision++;
					}
					//agrego la observaciones pendienes a este punto de estado [S]
					$datos=$ce->agregarObservacionAlTramite($conexion,$id_tramite_flujo,$formato['id_enlace'],$item,$revision,'S',$id_solicitud_ia);
					
				}
			}
			$mensaje['mensaje'] = "Observaciones guardadas";
			$mensaje['estado'] = 'OK';
			break;

		case 'guardarSubsanacionesIA':
		  
			$id_tramite_flujo = htmlspecialchars ($_POST['id_tramite_flujo'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud_ia = htmlspecialchars ($_POST['id_solicitud_ia'],ENT_NOQUOTES,'UTF-8');
			$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
			$observacion='Observaciones subsanadas: ';
			$datosDocumento=array();
			$datosDocumento['id_solicitud_ia']=$id_solicitud_ia;
			$tieneElementos=false;
			try{
				$conexion->Begin();
				foreach($_POST as $key=>$item){
					if(substr($key,0,7)=="subsan_"){
						//obtengo los parametros del campo recuperado
						$formato=$ce->obtenerFormatoDelElemeno($conexion,'GI',substr($key,7));
						if($formato==null)
							continue;
						
						//Guardo los items modificados en las observaciones
						if($formato['campo']==null || trim($formato['campo'])==''){	//Verifica si son directamente para guardar en campos
							//miro que tipo de elemento hay que procesar
						}
						else{
							$datosDocumento[$formato['campo']]=$item;
							$tieneElementos=true;
						}

						//obtengo las observaciones pendienes a este punto de estado [S]
						$doc=$ce->obtenerObservacionesDelDocumentoPorEnlace($conexion,$id_solicitud,'DG',$formato['id_enlace'],'S',$id_solicitud_ia);
						$revision=1;
						if(sizeof($doc)>0){
							foreach($doc as $k=>$v){
								//corrige todas observaciones de este punto pendienes como subsanadas estado ya no pendienes [N]
								$ce->actualizarObservacionTramiteEstado($conexion,$v['id_tramite_observacion'],'N');
								
							}
						}
						
					}
				}
				if($tieneElementos){
					//Guarda los datos del ingrediente activo
					$cg->guardarIngredientesSolicitud($conexion,$datosDocumento);
				}
				$conexion->Commit();
				$mensaje['mensaje'] = "Las subsanaciones fueron guardadas";
				$mensaje['estado'] = 'OK';
			}catch(Exception $e){
				$conexion->Rollback();
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