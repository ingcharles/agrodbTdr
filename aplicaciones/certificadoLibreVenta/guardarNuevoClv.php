<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];

$estado = 'enviado';

try{	
	$datos = array('tipo_Producto' => htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8'),
				    'plaguicidaIdProducto' => htmlspecialchars ($_POST['producto_plaguicida'],ENT_NOQUOTES,'UTF-8'),
				    'plaguicidaNombreProducto' => htmlspecialchars ($_POST['nombre_producto_plaguicida'],ENT_NOQUOTES,'UTF-8'),
					'formulacionPlaguicida' => htmlspecialchars ($_POST['formulacionPlaguicida'],ENT_NOQUOTES,'UTF-8'),
					'veterinariIdProducto' => htmlspecialchars ($_POST['producto_veterinario'],ENT_NOQUOTES,'UTF-8'),
					'veterinarioNombreProducto' => htmlspecialchars ($_POST['nombre_producto_veterinario'],ENT_NOQUOTES,'UTF-8'));
			
	$archivoRegistroProducto = ($_POST['archivoRegistroProducto']);
	
	$ingredienteActivo = $_POST['hingrediente_activo'];
	$concentracion = $_POST['hconcentracion'];
	$unidad = $_POST['hUnidad'];
	
	$identificador = $_POST['identificador'];
	
	
	try {
			$conexion = new Conexion();
			$clv = new ControladorClv();
			$cc = new ControladorCatalogos();
			$cr = new ControladorRegistroOperador();
							
			//Crear código de identificación de solicitud para agrupar productos
			$res = $clv->generarNumeroCertificado($conexion, '%'.$identificador.'%');
			$solicitud = pg_fetch_assoc($res);
			$tmp= explode("-", $solicitud['numero']);
			$incremento = end($tmp)+1;
			
			$codigoCertificado = 'CLV-'.$identificador.'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
			
			$qOperador = $cr->buscarOperador($conexion, $identificador);
			$operador = pg_fetch_assoc($qOperador);
												
						
			if($datos['tipo_Producto'] == 'IAP'){
				$tipoOperacion = 'Formulador';
				$idProducto = $datos['plaguicidaIdProducto'];
				$nombreProducto = $datos['plaguicidaNombreProducto'];
			}				
				
			if($datos['tipo_Producto'] =='IAV'){
				$tipoOperacion = 'Fabricante';
				$idProducto = $datos['veterinariIdProducto'];
				$nombreProducto = $datos['veterinarioNombreProducto'];
			}
			
			$qIdClv = $clv->guardarCertificadoProducto($conexion,$identificador,$datos['tipo_Producto'], $tipoOperacion, $codigoCertificado, $idProducto,$nombreProducto,$estado, $datos['formulacionPlaguicida']);
			$idClv = pg_fetch_result($qIdClv, 0, 'id_clv');
							
			//Actualiza Certificado PRODUCTO Plaguicida						
			if($datos['tipo_Producto'] == 'IAP'){
				for($i=0; $i<count($ingredienteActivo); $i++){
					$cProductoDetalle = $clv->guardarDetalleCertificadoProductoP($conexion,$idClv,$ingredienteActivo[$i],$concentracion[$i], $unidad[$i]);
				}					
			}			
			//Archivos			
			if($archivoRegistroProducto != '0'){
				$clv ->guardarClvArchivos($conexion, $idClv,'Registro del producto',$archivoRegistroProducto, $datos['tipo_Producto']);
			}

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido grabados satisfactoriamente.';
			
			$conexion->desconectar();			
			echo json_encode($mensaje);
			
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>