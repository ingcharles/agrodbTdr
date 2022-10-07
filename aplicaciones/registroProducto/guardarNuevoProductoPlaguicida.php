<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAuditoria.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

    function reemplazarCaracteres($cadena){
    	$cadena = str_replace('á', 'a', $cadena);
    	$cadena = str_replace('é', 'e', $cadena);
    	$cadena = str_replace('í', 'i', $cadena);
    	$cadena = str_replace('ó', 'o', $cadena);
    	$cadena = str_replace('ú', 'u', $cadena);
    	$cadena = str_replace('ñ', 'n', $cadena);
    	$cadena = strtoupper(str_replace(' ', '', $cadena));
    
    	return $cadena;
    }


    try{
    	
    	$idTipoProducto = htmlspecialchars ($_POST['idTipoProducto'],ENT_NOQUOTES,'UTF-8');
    	$idSubtipoProducto = htmlspecialchars ($_POST['idSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
    	$nombreSubtipoProducto = htmlspecialchars ($_POST['nombreSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
    	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
    	$identificadorCreacion = $_POST['identificadorCreacion'];
    	
    	$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
    	$numeroRegistro = htmlspecialchars ($_POST['numeroRegistro'],ENT_NOQUOTES,'UTF-8');
    	$empresa = htmlspecialchars ($_POST['empresa'],ENT_NOQUOTES,'UTF-8');
    	$razonSocial = htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8');
    	$fechaRegistro = $_POST['fecha_registro'];
    	$idDeclaracionVenta = htmlspecialchars ($_POST['idDeclaracionVenta'],ENT_NOQUOTES,'UTF-8');
    	$declaracionVenta = htmlspecialchars ($_POST['declaracionVenta'],ENT_NOQUOTES,'UTF-8');	
    	
    	$idFormulacion = htmlspecialchars ($_POST['formulacion'],ENT_NOQUOTES,'UTF-8');
    	$nombreFormulacion = htmlspecialchars ($_POST['nombreFormulacion'],ENT_NOQUOTES,'UTF-8');
    	$idCategoriaToxicologica = htmlspecialchars ($_POST['caToxicologica'],ENT_NOQUOTES,'UTF-8');
    	$CategoriaToxicologica = htmlspecialchars ($_POST['nombreCategoria'],ENT_NOQUOTES,'UTF-8');
    	$periodoReingreso = htmlspecialchars ($_POST['periodoReingreso'],ENT_NOQUOTES,'UTF-8');
    	$estabilidad = $_POST['estabilidad'];
    	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
    	
    	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
    	
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	$programa = 'NO';
    	$trazabilidad = 'NO';
    	$movilizacion = 'NO';
    	
    	$codigoProducto = 0;
    	$nombreCientifico = null;
    	$partidaArancelaria = null;
    	$unidadMedida = null;
    	
    	$dosis = null;
    	$unidadMedidaDosis = null;
    	$periodoCarencia = null;
    	
        try {
        		$conexion = new Conexion();
        		$cr = new ControladorRequisitos();
        		$cc = new ControladorCatalogos();
        		$ca = new ControladorAuditoria();
        		
        		if($empresa != null && $razonSocial != null ){
            		$producto = $cc->buscarProductoXNombre($conexion, $idSubtipoProducto, $nombreProducto);
            		$categoria = reemplazarCaracteres($numeroRegistro);
            		
            		$numeroTotalRegistro = pg_num_rows($cr->buscarNombreRegistroProducto($conexion,$categoria));        				
            		
            		if(pg_num_rows($producto) == 0){
            			
            			if($numeroTotalRegistro == 0) {
            			
            				$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, 
            				                                                        $archivo, $unidadMedida, $programa, $trazabilidad, $identificadorCreacion, $movilizacion),0,'id_producto');
            						
            				if ($idCategoriaToxicologica == ''){
            				    $idCategoriaToxicologica = 0;
            				}
            				
            				if ($idFormulacion == ''){
            				    $idFormulacion = 0;
            				}
            				
            				if($fechaRegistro == ''){
            				    $fechaRegistro = date('Y-m-d');
            				}
            				
            				$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion,$nombreFormulacion , $categoria, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, 
            				                                    $observaciones, $idCategoriaToxicologica, $CategoriaToxicologica, $fechaRegistro, $empresa, $idDeclaracionVenta, $declaracionVenta, $razonSocial, $estabilidad);
            				
            				
            				$empresaRegistro = $cc->listarEmpresa($conexion, $empresa);
            				
            				if(pg_num_rows($empresaRegistro) == 0){
            				    $cc->guardarEmpresa($conexion, $empresa, $razonSocial);
            				}
            			
            				/**AUDITORIA***/
            					
            				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
            				$qTransaccion = $ca ->guardarTransaccion($conexion,$idProducto , pg_fetch_result($qLog, 0, 'id_log'));
            				$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el producto '.
            				                            $nombreProducto.' del subtipo con ID '.$idSubtipoProducto);
            					
            				/**FIN AUDITORIA***/
            				
            				$mensaje['estado'] = 'exito';
            				$mensaje['mensaje'] = $cr->imprimirLineaProducto($idProducto, $nombreProducto, $idSubtipoProducto,$area, 'registroProducto', 'Plaguicida');
            			
            			}else{
            				$mensaje['estado'] = 'error';
            				$mensaje['mensaje'] = 'El producto tiene número de registro que ya ha sido ingresado.';
            			}
            			
            			
            		}else{
            			$mensaje['estado'] = 'error';
            			$mensaje['mensaje'] = "El producto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
            		}
        		
        		}else{
        		    $mensaje['estado'] = 'error';
        		    $mensaje['mensaje'] = "El número de cédula del operador ingresado no existe.";
        		}
        		
        		$conexion->desconectar();
        		echo json_encode($mensaje);
        		
        	} catch (Exception $ex){
        		pg_close($conexion);
        		$mensaje['estado'] = 'error';
        		$mensaje['mensaje'] = "Error al ejecutar sentencia";
        		echo json_encode($mensaje);
        	}
    } catch (Exception $ex) {
    	$mensaje['estado'] = 'error';
    	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
    	echo json_encode($mensaje);
    }
?>