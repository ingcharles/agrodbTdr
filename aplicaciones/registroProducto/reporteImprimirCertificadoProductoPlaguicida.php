<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorReportes.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	
	$bProducto = false;
	$bProductoInocuidad = false;
	$bPartidas = false;
	$bComposicion = false;
	$bFabForm = false;
	$bUso = false;
	$bMensaje= '';
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();		
		
		//Producto
		if(pg_num_rows($cr->abrirProducto($conexion, $idProducto)) != 0){
		    $producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
		    
		        if($producto['nombre_comun']!=''){
		            $bProducto = true;
		        }else{
		            $bProducto = false;
		            $bMensaje.= " Nombre, "; 
		        }
		}else{
		    $bProducto = false;
		    $bMensaje.= " Nombre, ";
		}
		
		
		//Producto Inocuidad		
		if(pg_num_rows($cr->buscarProductoInocuidad($conexion,$idProducto)) != 0){		
		    $productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProducto));
		    
		    if(($productoInocuidad['formulacion']!='') && ($productoInocuidad['numero_registro']!='') && 
		        ($productoInocuidad['categoria_toxicologica']!='') && ($productoInocuidad['fecha_registro']!='') &&
		        ($productoInocuidad['id_operador']!='') && ($productoInocuidad['declaracion_venta']!='') && 
		        ($productoInocuidad['estabilidad']!='')){
		            $bProductoInocuidad = true;
		    }else{
		          $bProductoInocuidad = false;
		          $bMensaje.= " Producto Inocuidad (formulación, num registro, cat toxicológica, fecha reg, operador, declaración venta, estabilidad), ";
		          }
		}else{
		    $bProductoInocuidad = false;
		    $bMensaje.= " Producto Inocuidad (formulación, num registro, cat toxicológica, fecha reg, operador, declaración venta, estabilidad), ";
		}
		
		//Partidas, Códigos Comp Supl, Presentaciones
		if(pg_num_rows($cr->listarPartidasCodigosPresentaciones($conexion, $idProducto)) != 0){
		    $bPartidas = true;
		}else{
		    $bPartidas = false;
		    $bMensaje.= " Partidas, ";
		}
		
		
		//Composición
		if(pg_num_rows($cr->listarComposicionPlaguicida($conexion, $idProducto)) != 0){
		    if(pg_num_rows($cr->listarCompuestosProducto($conexion, $idProducto, 'Ingrediente activo')) != 0){
		        $bComposicion = true;
		    }else{
		        $bComposicion = false;
		        $bMensaje.= " Composición Ingrediente Activo, ";
		    }
		}else{
		    $bComposicion = false;
		    $bMensaje.= " Composición Ingrediente Activo, ";
		}
		
		//Fabricante, Formulador, Manufacturador
		if(pg_num_rows($cr->listarFabricanteManufacturador($conexion,$idProducto)) != 0){
		    $bFabForm = true;
		}else{
		    $bFabForm = false;
		    $bMensaje.= " Fabricante/Formulador, ";
		}
		
		//Usos
		if(pg_num_rows($cr->listarUsoPlaguicida($conexion,$idProducto)) != 0){
		    $bUso = true;
		}else{
		    $bUso = false;
		    $bMensaje.= " Usos";
		}		
		
		//Generar reporte
		if($bProducto == true && $bProductoInocuidad == true && $bPartidas == true && $bComposicion == true &&
		    $bFabForm == true && $bUso == true ){
		    ///JASPER///
		    $jru = new ControladorReportes();
		    
		    //Verificar tipo reporte
		    if(strripos($productoInocuidad['numero_registro'], '/NA', strlen($productoInocuidad['numero_registro'])-3) > 0){
		        $ReporteJasper='aplicaciones/registroProducto/reportes/PlaguicidasComunidadAndina.jrxml';
		        $filename = "CertificadoComunidadAndina_".$idProducto.'.pdf';
			}else if(strripos($productoInocuidad['numero_registro'], '/NA-CL') > 0){
		        $ReporteJasper='aplicaciones/registroProducto/reportes/PlaguicidasComunidadAndina.jrxml';
		        $filename = "CertificadoComunidadAndina_".$idProducto.'.pdf';
		    }else{
		        $ReporteJasper='aplicaciones/registroProducto/reportes/PlaguicidasNormaNacional.jrxml';
		        $filename = "CertificadoNormaNacional_".$idProducto.'.pdf';
		    }
		    
		    $salidaReporte = 'aplicaciones/registroProducto/certificadosPlaguicidas/'.$filename;
			
			$parameters['parametrosReporte'] = array(
		    	'idProducto'=>(int)$idProducto,
			    'ruta' => $constg::RUTA_DOMINIO.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte
		    );
		    
		    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'CertificadoVeterinarioFertilizantePlaguicida');
		    $cr -> guardarRutaCertificado($conexion, $idProducto, $salidaReporte);
		    
		    $mensaje['estado'] = 'exito';
		    $mensaje['mensaje'] = $idProducto;
		    $mensaje['salidaReporte'] = $salidaReporte;
		}else{
		    $mensaje['estado'] = 'error';
		    $mensaje['mensaje'] = "Debe completar todo el registro: ".$bMensaje;
		}
	    		   
	   $conexion->desconectar();
	   echo json_encode($mensaje);
					
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia - ".$ex;
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>