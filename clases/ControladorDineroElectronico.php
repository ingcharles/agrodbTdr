<?php
class ControladorDineroElectronico{
//----------------------------------------------------------------------------------------------------------------------
	
	public function abrirOrdenPagoDineroElectronico ($conexion, $numeroSolicitud){
		try {
			$res = $conexion->ejecutarConsulta("select
						*
					from
						g_financiero.orden_pago o,
						g_financiero.clientes c
					where
						o.identificador_operador = c.identificador and
						o.numero_solicitud = '$numeroSolicitud';");
		return $res;
	 	} catch (Exception $e) {
			return $res='';
	 	}	
 	}
 //---------------------------------------------------------------------------------------------------------------------
public function abrirDetallePagoDineroElectronico ($conexion, $id_pago){
	try {
		$res = $conexion->ejecutarConsulta("SELECT
						d.*,
						s.unidad_medida
					FROM
						g_financiero.detalle_pago d,
						g_financiero.servicios s
					WHERE
						d.id_servicio = s.id_servicio and
						d.id_pago = $id_pago;");
		return $res;
	} catch (Exception $e) {
		return $res='';
	}	
 }

//----------------------------notificar error en generar xml-------------------------------------------
 public function notificarOrdenPago($conexion,$numTransaccion,$numSolicitud){
 	try {		
 		$res = $conexion->ejecutarConsulta("
 				UPDATE 
 						g_financiero.orden_pago
   				SET 
 						notificacion_dinero_electronico='$numTransaccion', estado=4 
 				WHERE numero_solicitud='$numSolicitud';");
 		return $res;
 	} catch (Exception $e) {
 		return '';
 	}
 }
 
//-------------------------------------------------------------------------------------------------------------------------------------------
 public function devolverProvinciaArea($conexion,$identificador){
 	try {
 		$res = $conexion->ejecutarConsulta("
	 				select
						f.id_area as idarea,
						r.provincia as nombreprovincia,
 				        r.clave_pfx as clavecertificado
	     			from 
						g_estructura.funcionarios f,
						g_financiero.oficina_recaudacion r
					where 
						f.identificador = r.identificador_firmante
						and f.identificador = '$identificador';");
 		return $res;
 	} catch (Exception $e) {
 		return $res='';
 	}
} 
//-----------------------------Obtener informacion del banco------------------------------------------------
 public function devolverDatosBanco($conexion,$entidad){
 	try {
 		$res = $conexion->ejecutarConsulta("
	 				select
						e.id_banco as idBanco,
						e.nombre as nombreBanco,
						c.id_cuenta_bancaria as idCuentaBanco,
						c.numero_cuenta as numeroCuentaBanco
				    from 
						g_catalogos.cuentas_bancarias c,
						g_catalogos.entidades_bancarias e
					where 
						c.id_banco=e.id_banco
						and e.nombre='$entidad';");
 		return $res;
 	} catch (Exception $e) {
 		
 	}
 }	

//-------funciones de verificaion de xml----------------------------------------------------------------
 public	function libxml_display_error($error)
 	{
 		$return = "<br/>\n";
 		switch ($error->level) {
 			case LIBXML_ERR_WARNING:
 				$return .= "<b>Warning $error->code</b>: ";
 				break;
 			case LIBXML_ERR_ERROR:
 				$return .= "<b>Error $error->code</b>: ";
 				break;
 			case LIBXML_ERR_FATAL:
 				$return .= "<b>Fatal Error $error->code</b>: ";
 				break;
 		}
 		$return .= trim($error->message);
 		if ($error->file) {
 			$return .=    " in <b>$error->file</b>";
 		}
 		$return .= " on line <b>$error->line</b>\n";
 	
 		return $return;
 	}
 //------------------------------------------------------------------------------------------------------------	
 public	function libxml_display_errors() {
 		$errors = libxml_get_errors();
 		foreach ($errors as $error) {
 			print libxml_display_error($error);
 		}
 	}
 	
//-------------------------cobro dinero electronico validar informacion-----------------------------------------
 public function cobroDineroElectronicoPre($monto, $cedula, $celular,$opcion){
 	
 	set_time_limit (70);
 	//if($opcion==1)$url='https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl'; //-----Quito
 	//else $url='https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl'; //--Guayaquil
 	if($opcion==1)$url='https://172.22.17.71:8443/mts_bce/services/MTSService?wsdl'; //-----Quito
 	else $url='https://172.17.48.71:8443/mts_bce/services/MTSService?wsdl'; //--Guayaquil
 	try { 		
 		$context = stream_context_create(array(
 				'ssl' => array(
 						'verify_peer' => false,
 						'verify_peer_name' => false,
 						'allow_self_signed' => true
 				)
 		)); 	
 		$soapParams = array(
 				'location' => $url,
 				//'login' => "AdmAGROCALIDAD01",
 				'login' => "MAAGROCALI1",
 				//'password' => "a4de5d89de",
 				'password' => "e047b2502e",
 				'stream_context' => $context,
 				'trace' => TRUE,
 				'soap_version' => SOAP_1_2,
 				'local_cert' => 'certificado/pruebasde.pem',
 				'passphrase' => 'bce1');	
 		$soapClient = new SoapClient($url, $soapParams);
 		$cash = new stdClass();
 		//print_r($soapClient);
 		$cash->dtoRequestCobroPre = new stdClass();
 		$cash->dtoRequestCobroPre->amount = $monto;
 		$cash->dtoRequestCobroPre->brandId = 1;
 		$cash->dtoRequestCobroPre->currency = 1;
 		$cash->dtoRequestCobroPre->document = $cedula;
 		$cash->dtoRequestCobroPre->language = "ES";
 		$cash->dtoRequestCobroPre->msisdnSource = $celular;
 		//$cash->dtoRequestCobroPre->msisdnTarget = "AdmAGROCALIDAD01";
 		//$cash->dtoRequestCobroPre->password = "a4de5d89de";
 		//$cash->dtoRequestCobroPre->pin = "a4de5d89de";
 		//$cash->dtoRequestCobroPre->user = "AdmAGROCALIDAD01";
 		$cash->dtoRequestCobroPre->msisdnTarget = "MAAGROCALI1";
 		$cash->dtoRequestCobroPre->password = "e047b2502e";
 		$cash->dtoRequestCobroPre->pin = "e047b2502e";
 		$cash->dtoRequestCobroPre->user = "MAAGROCALI1";
 		$cash->dtoRequestCobroPre->utfi = $this->utfi();
 		$res = $soapClient->cobroPre($cash);
 		$resultText = $res->return->resultText;
 		$resultCode= $res-> return->resultCode;
 		$resultCodeId= $res-> return->codeErrorId;
 		$resultTransaccion = array(
 				'codigo'	=>	$resultCode,
 				'text'	=>	$resultText,
 				'codigoId'	=>	$resultCodeId	 
 				);
 		return $resultTransaccion;	
 		} catch (Exception $e) {
 			$resultTransaccion['text']='Error de conexion con el Banco Central del Ecuador</br>Intentelo
 			mas tarde. Gracias por utilizar nuestro servicio.';
 			return $resultTransaccion;
 		}	 
 	}	
 
 //-------------------------cobro dinero electronico realizar pago-----------------------------------------
 public function cobroDineroElectronicoConfirm($monto, $cedula, $celular,$opcion){

 	set_time_limit (70);
 	//if($opcion==1)$url='https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl'; //-----Quito
 	//else $url='https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl'; //--Guayaquil
 	if($opcion==1)$url='https://172.22.17.71:8443/mts_bce/services/MTSService?wsdl'; //-----Quito
 	else $url='https://172.17.48.71:8443/mts_bce/services/MTSService?wsdl'; //--Guayaquil
 	try {
 		$context = stream_context_create(array(
 				'ssl' => array(
 						'verify_peer' => false,
 						'verify_peer_name' => false,
 						'allow_self_signed' => true
 				)
 		));	
 		$soapParams = array(
 				'location' => $url,
 				//'login' => "AdmAGROCALIDAD01",
 				'login' => "MAAGROCALI1",
 				//'password' => "a4de5d89de",
 				'password' => "e047b2502e",
 				'stream_context' => $context,
 				'trace' => TRUE,
 				'soap_version' => SOAP_1_2,
 				'local_cert' => 'certificado/pruebasde.pem',
 				'passphrase' => 'bce1');
 		$soapClient = new SoapClient($url, $soapParams);
 		$cash = new stdClass();
 		//print_r($soapClient);
 		$cash->dtoRequestCobroConfirm = new stdClass();
        $cash->dtoRequestCobroConfirm->amount = $monto;
        $cash->dtoRequestCobroConfirm->brandId = 1;
        $cash->dtoRequestCobroConfirm->currency = 1;
        $cash->dtoRequestCobroConfirm->document = $cedula;
        $cash->dtoRequestCobroConfirm->language = "ES";
        $cash->dtoRequestCobroConfirm->msisdnSource = $celular;
        //$cash->dtoRequestCobroConfirm->msisdnTarget = "AdmAGROCALIDAD01";
        //$cash->dtoRequestCobroConfirm->password = "a4de5d89de";
        //$cash->dtoRequestCobroConfirm->pin = "a4de5d89de";
        //$cash->dtoRequestCobroConfirm->user = "AdmAGROCALIDAD01";
        $cash->dtoRequestCobroPre->msisdnTarget = "MAAGROCALI1";
        $cash->dtoRequestCobroPre->password = "e047b2502e";
        $cash->dtoRequestCobroPre->pin = "e047b2502e";
        $cash->dtoRequestCobroPre->user = "MAAGROCALI1";
        $cash->dtoRequestCobroConfirm->utfi = $this->utfi();
        $res = $soapClient->cobroConfirm($cash);
 		$resultText = $res->return->resultText;
 		$resultCode = $res->return->resultCode;
 		$numeroTra= $res->return->transactionValues;
 		$transaccion = $numeroTra[13]->value; 
 		$timestamp = $numeroTra[14]->value; 
 		$resultTransaccion = array(
 				'codigo'	=>	$resultCode,
 				'text'	=>	$resultText,
 				'transaccion'	=>	$transaccion,
 				'timestamp'	=>	$timestamp	 
 				);
 		return $resultTransaccion;
 		} catch (Exception $e) {
 			$resultTransaccion['text']='Error de conexion con el Banco Central del Ecuador</br>Intentelo
 			mas tarde. Gracias por utilizar nuestro servicio.';
 			return $resultTransaccion;
 		}
 	}	
//-----------------------------------------------------------------------------------------------------------------------------------------
 public function utfi(){
 		$utfi=date('Y-m-d hh:mm:ss').rand(5,100);
 		return $utfi;
 	}

//-------------------------verificar conectividad-----------------------------------------------------------------------------
 	public function validarConexionBce(){	
 		set_time_limit (340);
 		$opcion=0;
 		$context = stream_context_create(array(
 				'ssl' => array(
 						'verify_peer' => false,
 						'verify_peer_name' => false,
 						'allow_self_signed' => true
 				)
 		));
 		try {		
 			//$url='https://181.211.102.40:8080/mts_bce/services/MTSService?wsdl';
 			$url='https://172.22.17.71:8443/mts_bce/services/MTSService?wsdl';
 			$soapParams = array(
 					'location' => $url,
 					//'login' => "AdmAGROCALIDAD01",
 					'login' => "MAAGROCALI1",
 					//'password' => "a4de5d89de",
 					'password' => "e047b2502e",
 					'stream_context' => $context,
 					'trace' => TRUE,
 					'soap_version' => SOAP_1_2,
 					'local_cert' => 'certificado/pruebasde.pem');
 			$soapClient = new SoapClient($url, $soapParams);
 			return 1;
 		} catch (Exception $e) {
 			$opcion = 0;
 		}
 		try {
 			//$url='https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl';
 			$url='https://172.17.48.71:8443/mts_bce/services/MTSService?wsdl';
 			$soapParams = array(
 					'location' => $url,
 					//'login' => "AdmAGROCALIDAD01",
 					'login' => "MAAGROCALI1",
 					//'password' => "a4de5d89de",
 					'password' => "e047b2502e",
 					'stream_context' => $context,
 					'trace' => TRUE,
 					'soap_version' => SOAP_1_2,
 					'local_cert' => 'certificado/pruebasde.pem');
 			$soapClient = new SoapClient($url, $soapParams);
 			return 2;
 		} catch (Exception $e) {			
 		   $opcion = 0;
 		}
 		return $opcion;
 	}
 	
//------------------------------------------------------------------------------------------------------------------------------------------
 Public function devolverFecha($fecha){
 	
 		$fechaN = explode(" ", $fecha);
 		$hora = explode(".",$fechaN[1]);
 		$fechaNu=date('d/m/Y',strtotime($fechaN[0]));
 	
 		$fechaNew = $fechaNu.' ('.$hora[0].')';
 		if($hora[0]=='')$fechaNew=$fechaNu;
 		if($fechaN[0]=='')$fechaNew='';
 		return $fechaNew;
 	}	
 }