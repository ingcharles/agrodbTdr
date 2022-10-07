<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$cm = new ControladorMovilizacionAnimal();
// Guardar movilización animal
$idEventoMovilizacion = htmlspecialchars ($_POST['idEventoMovilizacion'],ENT_NOQUOTES,'UTF-8');

//TODO: Generar el secuencial del Numero de certificado
$SecuencialAutogenerado=$cm->AutogenerarNumerosCertificadosMovilizacion($conexion, $_POST['codigoProvinciaOrigen'], $_POST['codigoProvinciaDestino']);
$fila = pg_fetch_assoc($SecuencialAutogenerado);
$fecha= date('dmy');

$secuencial = ($fila['valor'])+1;
$secuencialCertificado = str_pad($secuencial, 4, "0", STR_PAD_LEFT);

$numero_certificado= $_POST['codigoProvinciaOrigen'].$_POST['codigoProvinciaDestino'].$secuencialCertificado.$fecha;

//$numero_certificado = htmlspecialchars ($_POST['numeroCertificadoMovilizacion'],ENT_NOQUOTES,'UTF-8');
$hora = htmlspecialchars ($_POST['hora'],ENT_NOQUOTES,'UTF-8');

$tiempoPorHora = htmlspecialchars ($_POST['cmbTiempo'],ENT_NOQUOTES,'UTF-8');
$fecha_desde = htmlspecialchars ($_POST['fecha_movilizacion'],ENT_NOQUOTES,'UTF-8')." ".$hora;
$fecha_movilizacion_desde = str_replace("/","-",$fecha_desde);	
$MinASumar = $tiempoPorHora*60; //Transformar a minutos

$fecha_desde = str_replace("/", " ", $fecha_desde);
$fecha_desde = str_replace(":", " ", $fecha_desde);
$FechaOrigen = explode(" ", $fecha_desde);

$Ano = $FechaOrigen[2];
$Mes = $FechaOrigen[1];
$Dia = $FechaOrigen[0];
	
$Horas = $FechaOrigen[3];
$Minutos = $FechaOrigen[4];
$Segundos = $FechaOrigen[5];
$Minutos = ((int)$Minutos) + ((int)$MinASumar);
$fecha_movilizacion_hasta = date("Y-m-d H:i:s",mktime($Horas,$Minutos,$Segundos,$Mes,$Dia,$Ano));
	
$fecha_registro = date('d-m-Y H.i');
$filename = "CSMI ".$numero_certificado." ".$fecha_registro.'.pdf';
$rutaArchivo = 'aplicaciones/movilizacionAnimal/documentos/'.$filename;
$estadoCM = false;

$datos = array(//cabecera de movilización
		'numero_certificado' => $numero_certificado
		,'id_tipo_autorizado' => 1//'propietario'
		,'identificador_autorizado' => htmlspecialchars ($_POST['identificador_autorizado'],ENT_NOQUOTES,'UTF-8')
		,'lugar_emision' => htmlspecialchars ($_POST['lugarEmision'],ENT_NOQUOTES,'UTF-8')
		,'id_tipo_movilizacion_origen' => htmlspecialchars ($_POST['idTipoMovilizacionOrigen'],ENT_NOQUOTES,'UTF-8') 
		,'id_sitio_origen' => htmlspecialchars ($_POST['id_sitio_origen'],ENT_NOQUOTES,'UTF-8')
		,'id_area_origen' => htmlspecialchars ($_POST['id_area_origen'],ENT_NOQUOTES,'UTF-8')
		,'id_tipo_movilizacion_destino' => htmlspecialchars ($_POST['cmbTipoMovilizacionDestinoAutoservicio'],ENT_NOQUOTES,'UTF-8') 
		,'id_sitio_destino' => htmlspecialchars ($_POST['id_sitio_destino'],ENT_NOQUOTES,'UTF-8')
		,'id_area_destino' => htmlspecialchars ($_POST['id_area_destino'],ENT_NOQUOTES,'UTF-8')
		,'medios_transporte' => htmlspecialchars ($_POST['cmbMedioTransporte'],ENT_NOQUOTES,'UTF-8')
		,'placa' => htmlspecialchars ($_POST['txtPlaca'],ENT_NOQUOTES,'UTF-8')
		,'identificacion_conductor' => htmlspecialchars ($_POST['identificacion_conductor'],ENT_NOQUOTES,'UTF-8') 		
		,'descripcion_transporte' => htmlspecialchars ($_POST['txtDescripcionTransporte'],ENT_NOQUOTES,'UTF-8')			
		,'usuario_empresa' => htmlspecialchars ($_POST['usuario_empresa'],ENT_NOQUOTES,'UTF-8')
		,'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8')
		,'cantidad' => 0
		,'costo' => 0 //por reglamento se normo que el costo de la emisión es de $0 para todas las especies
		,'total' => htmlspecialchars ($_POST['total_movilizados'],ENT_NOQUOTES,'UTF-8')
		,'estado' => 'activo'
		,'observacion' => 'Na'
		,'ruta_numero_certificado' => $rutaArchivo
		,'hora' => htmlspecialchars ($_POST['hora'],ENT_NOQUOTES,'UTF-8')			
		,'fecha_movilizacion_desde' => $fecha_movilizacion_desde
		,'fecha_movilizacion_hasta' => $fecha_movilizacion_hasta
		,'secuencial_certificado_movilizacion' => $secuencialCertificado
		,'codigo_provincia_origen' => htmlspecialchars ($_POST['codigoProvinciaOrigen'],ENT_NOQUOTES,'UTF-8')
		,'codigo_provincia_destino' => htmlspecialchars ($_POST['codigoProvinciaDestino'],ENT_NOQUOTES,'UTF-8')
);


$consulta= $cm-> listaMovilizacionA($conexion, $datos['numero_certificado']);

//inicio if
if (pg_num_rows($consulta) <= 0) {

$movilizacionAnimal = $cm-> guardarMovilizacionAnimal($conexion
		, $datos['numero_certificado']
		, $datos['id_tipo_autorizado'], $datos['identificador_autorizado'], $datos['lugar_emision'], $datos['id_tipo_movilizacion_origen'], $datos['id_sitio_origen']
		, $datos['id_area_origen'], $datos['id_tipo_movilizacion_destino'], $datos['id_sitio_destino'], $datos['id_area_destino'], $datos['medios_transporte']
		, $datos['placa'], $datos['identificacion_conductor'], $datos['descripcion_transporte'], $datos['usuario_empresa'], $datos['usuario_responsable'], $datos['cantidad'], $datos['costo'], $datos['total']
		, $datos['estado'], $datos['observacion'], $datos['ruta_numero_certificado'], $datos['hora'], $datos['fecha_movilizacion_desde'], $datos['fecha_movilizacion_hasta'],
		$datos['codigo_provincia_origen'] ,$datos['codigo_provincia_destino'],$datos['secuencial_certificado_movilizacion']);

$idMovilizacionAnimal = pg_fetch_result($movilizacionAnimal, 0, 'id_movilizacion_animal');
	
//echo " ....Grabo cabecera";
	
$idProducto = $_POST['hCodigoAnimal'];
$numeroCertificado = $_POST['hNumeroCertificado'];	
$fechaCertificado = $_POST['hFechaCertificado'];
$cantidad = $_POST['hCantidad'];
$fechaNacimientoProducto = $_POST['hFechaNacimiento'];
$edadProducto = $_POST['hEdadProducto'];

for($i=0; $i<count($idProducto); $i++){//inicio for//caso 0, 1
	$detalle = array(//detalle de movilización
			'id_movilizacion_animal' => $idMovilizacionAnimal
			,'id_especie' => htmlspecialchars ($_POST['cmbEspecie'],ENT_NOQUOTES,'UTF-8') 
			,'nombre_especie' => htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8')  
			,'id_producto' => $idProducto[$i]
			,'cantidad' => $cantidad[$i]
			,'costo' => 0 //por reglamento se normo que el costo de la emisión es de $0 para todas las especies
			,'total' => 0
			,'observacion' => 'Na'
			,'numero_certificado' => $numeroCertificado[$i]
			,'fecha_certificado' => $fechaCertificado[$i]
			,'fecha_nacimiento_producto' => $fechaNacimientoProducto[$i]
			,'edad_producto' => $edadProducto[$i]
	);		
			
	$movilizacionAnimalDetalle = $cm-> guardarMovilizacionAnimalDetalle($conexion
			,$detalle['id_movilizacion_animal'], $detalle['id_especie'], $detalle['nombre_especie'], $detalle['id_producto']
			,$detalle['cantidad'], $detalle['costo'], $detalle['total']
			,$detalle['observacion'], $detalle['numero_certificado'], $detalle['fecha_certificado'],'');	
	
	$idMovilizacionAnimalDetalle = pg_fetch_result($movilizacionAnimalDetalle, 0, 'id_movilizacion_animal_detalle');// 36;//
	
	//echo " ....Grabo detalle";
	//echo " ....valor --> ".$idMovilizacionAnimalDetalle;
	
	$id_producto_condicion = 0;
	$controlProductoCondicion = $vdr->validarProductoCondicion($conexion,$detalle['id_especie'], $detalle['id_producto']);
	while ($fila = pg_fetch_assoc($controlProductoCondicion)){
		$id_producto_condicion = $fila['id_producto'];		
	}
		
	//-- ------ Catastro -------		
	//echo "Inicia catastro";				
	//Movimiento origen (resta)
	$idCoeficiente = -1;	
	$movientoOrigen = array(
			'id_sitio' => $datos['id_sitio_origen']
			,'id_area' => $datos['id_area_origen']
			,'id_especie' => $detalle['id_especie']
			,'nombre_especie' => $detalle['nombre_especie']
			,'id_concepto_catastro' => 8 //Movimiento origen
			,'numero_documento' => $detalle['numero_certificado']
			,'edad_producto' => $detalle['edad_producto'] //fecha de nacimiento del producto
			,'id_producto' => $detalle['id_producto']
			,'coeficiente' => $idCoeficiente //Movimiento destino (suma)
			,'cantidad' => $detalle['cantidad']
			,'subTotal' => $detalle['cantidad'] * $idCoeficiente
			,'usuario_responsable' => $datos['usuario_responsable']
			,'estado' => 'creado'				
			,'fecha_nacimiento' => $detalle['fecha_nacimiento_producto']
			,'numero_documento_referencia' => $datos['numero_certificado']
	);
	
	$saldo = 0;				
	$control = $vdr->validarCatastroAnimal($conexion, $movientoOrigen['id_sitio'], $movientoOrigen['id_area'], $movientoOrigen['id_especie'], $movientoOrigen['id_producto']);
	
	while ($fila = pg_fetch_assoc($control)){
		$saldo = $fila['total'];
	}
	$total =  $saldo - $detalle['cantidad'];
	
	$MovientoAnimalOrigen = $vdr->guardarDatosCatastro($conexion, $movientoOrigen['id_sitio'], $movientoOrigen['id_area'], $movientoOrigen['id_especie'], $movientoOrigen['nombre_especie']
			, $movientoOrigen['id_concepto_catastro'], $movientoOrigen['numero_documento'], $movientoOrigen['edad_producto'], $movientoOrigen['id_producto'], $movientoOrigen['coeficiente']
			, $movientoOrigen['cantidad'], $total, $movientoOrigen['estado'], $movientoOrigen['fecha_nacimiento'], '',$movientoOrigen['usuario_responsable'], $movientoOrigen['numero_documento_referencia']);
	
	//echo "...Movimiento origen-resta";		
	
	if($id_producto_condicion==0){//si es diferente de lechon, que no se vacuna
		$saldo = 0;
		$controlVO = $vdr->validarCatastroAnimalVacunados($conexion, $movientoOrigen['id_sitio'], $movientoOrigen['id_area'], $movientoOrigen['id_especie'], $movientoOrigen['id_producto'], $detalle['numero_certificado']);
		
		while ($fila = pg_fetch_assoc($controlVO)){
			$id_concepto_catastro = 11;//"Vacunación origen"
			$saldo = $fila['total'];
			$fecha_vacunacion = $fila['fecha_vacunacion'];
			$numero_documento = $fila['numero_documento'];					
		}
		
		$total =  $saldo - $detalle['cantidad'];
		$MovientoAnimalOrigen = $vdr->guardarDatosCatastroVacunacion($conexion, $movientoOrigen['id_sitio'], $movientoOrigen['id_area'], $movientoOrigen['id_especie'], $movientoOrigen['nombre_especie']
				, $id_concepto_catastro, $numero_documento, $movientoOrigen['edad_producto'], $movientoOrigen['id_producto'], $movientoOrigen['coeficiente']
				, $movientoOrigen['cantidad'], $total, $movientoOrigen['estado'], $movientoOrigen['fecha_nacimiento'], $fecha_vacunacion, $movientoOrigen['usuario_responsable'], $movientoOrigen['numero_documento_referencia']);
			
		//echo "...Movimiento origen-resta para vacunados -- fin";
	}
	//echo "...Movimiento origen-resta fin";
	
	//Movimiento destino (suma)
	$idCoeficiente = 1;
	$movientoDestino = array(
			'id_sitio' => $datos['id_sitio_destino']
			,'id_area' => $datos['id_area_destino']
			,'id_especie' => $detalle['id_especie']
			,'nombre_especie' => $detalle['nombre_especie']
			,'id_concepto_catastro' => 9//Movimiento destino
			,'numero_documento' => $detalle['numero_certificado']
			,'edad_producto' => $detalle['edad_producto']//fecha de nacimiento del producto
			,'id_producto' => $detalle['id_producto']
			,'coeficiente' => $idCoeficiente //Movimiento destino (suma)
			,'cantidad' => $detalle['cantidad']
			,'subTotal' => $detalle['cantidad'] * $idCoeficiente
			,'usuario_responsable' => $datos['usuario_responsable']
			,'estado' => 'creado'
			,'fecha_nacimiento' => $detalle['fecha_nacimiento_producto']
			,'numero_documento_referencia' => $datos['numero_certificado']
	);	
	$saldo = 0;
	$control = $vdr->validarCatastroAnimal($conexion, $movientoDestino['id_sitio'], $movientoDestino['id_area'], $movientoDestino['id_especie'], $movientoDestino['id_producto']);
	while ($fila = pg_fetch_assoc($control)){
		$saldo = $fila['total'];
	}
	$total =  $movientoDestino['subTotal']+$saldo;
	
	$MovientoAnimalDestino = $vdr->guardarDatosCatastro($conexion, $movientoDestino['id_sitio'], $movientoDestino['id_area'], $movientoDestino['id_especie'], $movientoDestino['nombre_especie']
			, $movientoDestino['id_concepto_catastro'], $movientoDestino['numero_documento'], $movientoDestino['edad_producto'], $movientoDestino['id_producto'], $movientoDestino['coeficiente']
			, $movientoDestino['cantidad'], $total, $movientoDestino['estado'], $movientoDestino['fecha_nacimiento'],'',$movientoDestino['usuario_responsable'],$movientoDestino['numero_documento_referencia']);
	
	
//	echo "...Movimiento destino-suma";		
	//echo "...Movimiento destino-suma vacunados resta";
	
	if($id_producto_condicion==0){//si es diferente de lechon, que no se vacuna
		$saldo = 0;
		$controlVD = $vdr->validarCatastroAnimalVacunados($conexion, $movientoDestino['id_sitio'], $movientoDestino['id_area'], $movientoDestino['id_especie'], $movientoDestino['id_producto'], $detalle['numero_certificado']);
		
		while ($fila = pg_fetch_assoc($controlVD)){
			$id_concepto_catastro = 10;//"Vacunación destino"
			$saldo = $fila['total'];
			$fecha_vacunacion = $fila['fecha_vacunacion'];					
		}
	
		$total =  $movientoDestino['cantidad']+$saldo;
		$MovientoAnimalDestino = $vdr->guardarDatosCatastroVacunacion($conexion, $movientoDestino['id_sitio'], $movientoDestino['id_area'], $movientoDestino['id_especie'], $movientoDestino['nombre_especie']
				, 12, $numero_documento, $movientoDestino['edad_producto'], $movientoDestino['id_producto'], $movientoDestino['coeficiente']
				, $movientoDestino['cantidad'], $total, $movientoDestino['estado'], $movientoDestino['fecha_nacimiento'],$fecha_vacunacion,$movientoDestino['usuario_responsable'],$movientoDestino['numero_documento_referencia']);	
	//	echo "...Movimiento destino-suma vacunados resta -- fin";
	}
	
	//echo "Fin catastro";
	$estadoCM = true;
}//fin for 
} else {
	echo('<script>alert("Movilizacion duplicada, no es posible su registro ")</script>');

}//fin if
if($estadoCM){
	$actualizarNumeroDocumento = array(
			'tipo_documento' => 'movilizacion'
			,'numero_documento' => $datos['numero_certificado']
			,'estado' => 'activo'
	);
	$EspecieDocumento = $cm->actualizarNumeroCertificadoMovilizacion($conexion, $actualizarNumeroDocumento['tipo_documento'], $actualizarNumeroDocumento['numero_documento'], $actualizarNumeroDocumento['estado']);	
}
//Generando archivo pdf - ruta del reporte compilado por Jasper y generado por IReports	

$jru = new ControladorReportes();

$ReporteJasper='aplicaciones/movilizacionAnimal/reportes/reporteMovilizacionCSMA.jrxml';
$salidaReporte='aplicaciones/movilizacionAnimal/documentos/'.$filename;	

$parameters['parametrosReporte'] = array(
	'id_movilizacion_animal' => (int)$idMovilizacionAnimal
);

$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'logoMovilizacion');

//fin pdf	
$conexion->desconectar();

?>
