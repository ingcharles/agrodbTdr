<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';


try{
	$conexion = new Conexion();
	$cl = new ControladorLotes();
	$ca = new controladorAdministrarCaracteristicas();

	try {
		
		$serieLote = htmlspecialchars($_POST['serieLote'], ENT_NOQUOTES, 'UTF-8');
		$nrLote = htmlspecialchars($_POST['loteNro'], ENT_NOQUOTES, 'UTF-8');
		$idProducto = htmlspecialchars($_POST['idProducto'], ENT_NOQUOTES, 'UTF-8');
		$fecha = htmlspecialchars($_POST['fechaConformacion2'], ENT_NOQUOTES, 'UTF-8');
		$codigoLote = htmlspecialchars($_POST['codigoLote'], ENT_NOQUOTES, 'UTF-8');
		$cantidad = htmlspecialchars($_POST['cantidadLote'], ENT_NOQUOTES, 'UTF-8');
		//$idVariedad = htmlspecialchars($_POST['idLoteVariedad'], ENT_NOQUOTES, 'UTF-8');
		//$variedad = htmlspecialchars($_POST['loteVariedad'], ENT_NOQUOTES, 'UTF-8');
		$idLote = htmlspecialchars($_POST['loteTipo'], ENT_NOQUOTES, 'UTF-8');
		$tipo = htmlspecialchars($_POST['nLoteTipo'], ENT_NOQUOTES, 'UTF-8');
		$pais = htmlspecialchars($_POST['idPaisDestino'], ENT_NOQUOTES, 'UTF-8');
		$idPais = htmlspecialchars($_POST['paisDestino'], ENT_NOQUOTES, 'UTF-8');
		$tipoProducto = htmlspecialchars($_POST['tipoProducto'], ENT_NOQUOTES, 'UTF-8');
		$descripcion = htmlspecialchars($_POST['descripcionLote'], ENT_NOQUOTES, 'UTF-8');
		$nProducto = explode(" -&gt;",htmlspecialchars($_POST['nProducto'], ENT_NOQUOTES, 'UTF-8'));
		$opcion = $_POST['opcion'];
		$idRegistro= $_POST['idRegistro'];
		$operador= $_POST['idUsuario'];
		$proveedor= $_POST['idProveedor'];		
		$año= date("Y");
		$id="";
		
		$areaOperador= htmlspecialchars ($_POST['codigoAreaOperador'],ENT_NOQUOTES,'UTF-8');
		$areaProveedor= htmlspecialchars ($_POST['codigoAreaProveedor'],ENT_NOQUOTES,'UTF-8');
		$sitioProveedor= htmlspecialchars ($_POST['idAreaProveedor'],ENT_NOQUOTES,'UTF-8');	
		
		$caracteristica = $_POST['elCaracteristica'];
		$idElemento = $_POST['idElemento'];
		
		$mensaje = array();
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Ha ocurrido un error!';
		
		$conexion->ejecutarConsulta("begin;");
		
		if(stristr($nProducto[0], 'cacao') === FALSE) {
			$valor=0;
		} else{
			$valor=pg_num_rows($cl->comprobarCodigo($conexion,$operador,$codigoLote,$idProducto));
		}

		
		if($valor==0){
		    
		    $parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $idProducto));
		    
		    if ($parametro['proveedores']=="1"){
		        $id = pg_fetch_row($cl->guardarLote($conexion,$operador,$año,$serieLote,$nrLote,$fecha, $codigoLote,$cantidad,$idLote,$tipo,$idPais,$pais,$tipoProducto,$descripcion,$idProducto,$nProducto[0], $areaOperador,$areaProveedor,$sitioProveedor,$proveedor));
		    } else{
		        $id = pg_fetch_row($cl->guardarLote($conexion,$operador,$año,$serieLote,$nrLote,$fecha, $codigoLote,$cantidad,$idLote,$tipo,$idPais,$pais,$tipoProducto,$descripcion,$idProducto,$nProducto[0], $areaOperador,$areaProveedor,$sitioProveedor));
		    }		    
			
			for($i=0; $i< count($idRegistro);$i++){
			
			    $cl->guardarDetalleLote($conexion, $id[0], $idRegistro[$i]);
				$cl->estadoRegistro($conexion, 2, $idRegistro[$i]);
			}
			
			$fila=pg_fetch_assoc($ca->obtenerFormulario($conexion, "nuevoLote"));	
			
			for($i=0; $i< count($caracteristica);$i++){
			    $ca->guardarCaracteristicaRegistro($conexion, $id[0],$idElemento[$i], $caracteristica[$i], $fila['id_formulario']);
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		
		}else {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El Código de Lote (".$codigoLote.") ya se encuentra registrado";
		}
		
		$conexion->ejecutarConsulta("commit;");
	

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
	
