<?php
class validarComprobante {
  public $xml; // base64Binary
}

class validarComprobanteResponse {
  public $RespuestaRecepcionComprobante; // respuestaSolicitud
}

class respuestaSolicitud {
  public $estado; // string
  public $comprobantes; // comprobantes
}

class comprobantes {
  public $comprobante; // comprobante
}

class comprobante {
  public $claveAcceso; // string
  public $mensajes; // mensajes
}

class mensajes {
  public $mensaje; // mensaje
}

class mensaje {
  public $identificador; // string
  public $mensaje; // string
  public $informacionAdicional; // string
  public $tipo; // string
}


/**
 * RecepcionComprobantesOfflineService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class RecepcionComprobantesOfflineService extends SoapClient {

  private static $classmap = array(
                                    'validarComprobante' => 'validarComprobante',
                                    'validarComprobanteResponse' => 'validarComprobanteResponse',
                                    'respuestaSolicitud' => 'respuestaSolicitud',
                                    'comprobantes' => 'comprobantes',
                                    'comprobante' => 'comprobante',
                                    'mensajes' => 'mensajes',
                                    'mensaje' => 'mensaje',
                                   );

  public function RecepcionComprobantesOfflineService($wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param validarComprobante $parameters
   * @return validarComprobanteResponse
   */
  public function validarComprobante(validarComprobante $parameters) {
    return $this->__soapCall('validarComprobante', array($parameters),       array(
            'uri' => 'http://ec.gob.sri.ws.recepcion',
            'soapaction' => ''
           )
      );
  }

}

?>
