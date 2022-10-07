<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

try {
	$conexion = new Conexion ();
	$vdr = new ControladorVacunacionAnimal();
	$mensaje = array ();
	$mensaje ['estado'] = 'error';
	$mensaje ['mensaje'] = 'Ha ocurrido un error!';
	
	$estadoCV=false;
	$fecha_vencimiento3 = htmlspecialchars ($_POST['fecha_emision'],ENT_NOQUOTES,'UTF-8');
	$fecha_emision=str_replace("/","-",$fecha_vencimiento3);
	$fecha_vencimiento1 = strtotime ('6 month',strtotime($fecha_emision)) ;
	$fecha_vencimiento = date('d-m-Y',$fecha_vencimiento1);
	
	$datos = array('id_sitio' => htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8')
			,'id_area' =>  htmlspecialchars ($_POST['areas'],ENT_NOQUOTES,'UTF-8')
			,'id_especie' => htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8')
			,'nombre_especie' => htmlspecialchars ($_POST['nombre_especie'],ENT_NOQUOTES,'UTF-8')
			,'identificador_administrador' => htmlspecialchars ($_POST['operadorVacunacion'],ENT_NOQUOTES,'UTF-8')
			,'identificador_distribuidor' => htmlspecialchars ($_POST['cmbDistribuidor'],ENT_NOQUOTES,'UTF-8')
			,'identificador_vacunador' => htmlspecialchars ($_POST['cmbVacunador'],ENT_NOQUOTES,'UTF-8')
			,'id_lote' => htmlspecialchars ($_POST['lote'],ENT_NOQUOTES,'UTF-8') //lote de vacución
			,'id_tipo_vacuna' => htmlspecialchars ($_POST['tipoVacuna'],ENT_NOQUOTES,'UTF-8')
			,'num_certificado' => htmlspecialchars ($_POST['numeroCertificadoVacunacion'],ENT_NOQUOTES,'UTF-8') //numero de la especie
			,'control_areteo' => 'si'//pasa el valor desde interface
			,'usuario_responsable' => htmlspecialchars ($_POST['usuarioResponsable'],ENT_NOQUOTES,'UTF-8')
			,'costo_vacuna' => htmlspecialchars ($_POST['costo_vacuna'],ENT_NOQUOTES,'UTF-8')
			,'estado_vacunado' => 'activo'
			,'fecha_vacunacion' => htmlspecialchars ($_POST['fecha_emision'],ENT_NOQUOTES,'UTF-8')
			,'fecha_vencimiento' => $fecha_vencimiento
	);
	
	$id_producto = $_POST['hCodProductos'];
	$existente = $_POST['hExistencias'];
	$vacunado = $_POST['hCantidad'];
	$observacion = $_POST['hObservacion'];
	$edadProducto = $_POST['hEdad'];
	$fechaNacimiento = $_POST['hFechaNacimiento'];
	$total_existente = 0;
	$total_vacunado = 0;
	//se corrigio esta parte para que no se guarde la vacunacion animal con valor 0
	for($k=0; $k<count($id_producto); $k++){
		if($vacunado[$k]>0){
			$detalles = array(
					'vacunados' => $vacunado[$k]
			);
			$total_vacunados = $total_vacunados + $detalles['vacunados'];
		}}
		if($total_vacunados!=0){
			$Vacuna = $vdr->guardarDatosVacunacion($conexion, $datos['id_sitio'], $datos['id_area'], $datos['id_especie'], $datos['nombre_especie']
					, $datos['identificador_administrador'], $datos['identificador_distribuidor'], $datos['identificador_vacunador']
					, $datos['id_lote'], $datos['id_tipo_vacuna'], $datos['num_certificado'], $datos['control_areteo'], $datos['usuario_responsable']
					, $datos['costo_vacuna'], $datos['estado_vacunado'], $datos['fecha_vacunacion']);
			$idVacuna = pg_fetch_result($Vacuna, 0, 'id_vacuna_animal');
		}
		//Vacuna por areas
		for($j=0; $j<count($id_producto); $j++){
	
		 if($vacunado[$j]>0){
		 	$detalle = array(
		 			'id_vacuna_animal' => $idVacuna,
		 			'id_producto' =>  $id_producto[$j],
		 			'existente' => $existente[$j],
		 			'vacunado' => $vacunado[$j],
		 			'observacion' => $observacion[$j]
		 	);
	
		 	$DetalleV = $vdr->guardarDetalleVacunacion($conexion, $detalle['id_vacuna_animal'], $detalle['id_producto'], $detalle['existente'], $detalle['vacunado'], $detalle['observacion']);
		 	$idDetalle = pg_fetch_result($DetalleV, 0, 'id_vacuna_animal_detalle');
		 	$total_existente = $total_existente + $detalle['existente'];
		 	$total_vacunado = $total_vacunado + $detalle['vacunado'];
		 		
		 	//Actualiza el catastro exclusivo de  catastro para vacunación
		 	$idConceptos = 10;//Concepto de catastro = 'Vacunación destino'
		 	$idCoeficiente = 1; //Registro de vacunación es en positivo
					
		 	$catastro = array(
		 			'id_sitio' => $datos['id_sitio']
		 			,'id_area' => $datos['id_area']
		 			,'id_especie' => $datos['id_especie']
		 			,'nombre_especie' => $datos['nombre_especie']
		 			,'id_concepto_catastro' => $idConceptos
		 			,'numero_documento' => $datos['num_certificado']
		 			,'edad_producto' => $edadProducto[$j]
		 			,'id_producto' => $detalle['id_producto']
		 			,'coeficiente' => $idCoeficiente
		 			,'cantidad_vacunado' => $detalle['vacunado']
		 			,'total_vacunado' => $detalle['vacunado'] * $idCoeficiente
		 			,'estado' => 'creado'
		 			,'usuario_responsable' => $datos['usuario_responsable']
		 			,'fecha_nacimiento' => $fechaNacimiento[$j]
		 			,'fecha_vacunacion' => $datos['fecha_vacunacion']
		 	);
	
		 	$Valida = $vdr-> validarProductoVacunacion($conexion, $catastro['id_especie'], $catastro['id_producto']);
		 	if(pg_num_rows($Valida) > 0 ){
		 		$saldo = 0;
		 		$control = $vdr->validarCatastroAnimalVacunado($conexion, $catastro['id_sitio'], $catastro['id_area'], $catastro['id_especie'], $catastro['id_producto'], $datos['num_certificado']);
		 		while ($fila = pg_fetch_assoc($control)){
		 			$saldo = $fila['total_vacunado'];
		 		}
		 		$total =  $catastro['total_vacunado']+$saldo;
		 			
		 		$Catastro = $vdr->guardarDatosCatastroVacunacion($conexion, $catastro['id_sitio'], $catastro['id_area'], $catastro['id_especie'], $catastro['nombre_especie']
		 				, $catastro['id_concepto_catastro'], $catastro['numero_documento'], $catastro['edad_producto'], $catastro['id_producto'], $catastro['coeficiente']
		 				, $catastro['cantidad_vacunado'], $total, $catastro['estado'], $catastro['fecha_nacimiento'], $catastro['fecha_vacunacion'], $catastro['usuario_responsable'],'');
		 	}
		 	//echo "Grabo catastro vacunación ---> ojo";
		 	//Actualiza los totales
		 	$Vacuna = $vdr->actualizarDatosVacunacionTotales($conexion, $idVacuna, $total_existente, $total_vacunado, $datos['costo_vacuna']);
		 	$estadoCV = true;
		 }//fin del control de cantidad de animales vacunados
		}//fin de detalle vacunacion
	
		//Grabar el contro areteo
		$id_areaArete = $_POST['hCodSerie_aretes'];
		$serie_inicio = $_POST['hSerie_inicio'];
		$serie_fin = $_POST['hSerie_fin'];
		//Control Areteo //si --> se aretea
		if($datos['control_areteo']=='si'){
			for($i=0; $i<count($id_areaArete); $i++){
				if($serie_inicio[$i] == $serie_fin[$i]){ //Serie Individual
					//echo "Serie Individual";
					$serie = $serie_inicio[$i];
					$Arete = $vdr->guardarDatosVacunacionAnimalArete($conexion, $idVacuna, $serie, $datos['fecha_vacunacion'], $datos['fecha_vencimiento']);
				}
				else{ //Serie colectivo
					//echo "Serie colectivo";
					$contador = ($serie_fin[$i] - $serie_inicio[$i])+1;
					$valor = 0;
					for($j=0; $j<$contador; $j++){
						$serie = $serie_inicio[$i]+$valor;
						$Arete = $vdr->guardarDatosVacunacionAnimalArete($conexion, $idVacuna, $serie, $datos['fecha_vacunacion'], $datos['fecha_vencimiento']);
						$valor++;
					}
				}
			}//fin del for
			//}
		}
	
		if($estadoCV){
			$actualizarNumeroDocumento = array(
					'id_especie' => $datos['id_especie']
					,'nombre_especie' => $datos['nombre_especie']
					,'tipo_documento' => 'vacunacion'
					,'numero_documento' => $datos['num_certificado']
					,'estado' => 'activo'
			);
			$EspecieDocumento = $vdr->actualizarNumeroCertificado($conexion, $actualizarNumeroDocumento['nombre_especie']
					, $actualizarNumeroDocumento['tipo_documento'], $actualizarNumeroDocumento['numero_documento'], $actualizarNumeroDocumento['estado']);
			//echo "actualización serie datos";
		}
		
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';

	$conexion->desconectar ();
	echo json_encode ( $mensaje );
} catch ( Exception $ex ) {
	pg_close ( $conexion );
	$mensaje ['estado'] = 'error';
	$mensaje ['mensaje'] = "Error al ejecutar sentencia";
	echo json_encode ( $mensaje );
}
?>