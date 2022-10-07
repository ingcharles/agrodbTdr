<?php
class autorizacionComprobante {
  public $claveAccesoComprobante; // string
}

class autorizacionComprobanteResponse {
  public $RespuestaAutorizacionComprobante; // respuestaComprobante
}

class respuestaComprobante {
  public $claveAccesoConsultada; // string
  public $numeroComprobantes; // string
  public $autorizaciones; // autorizaciones
}

class autorizaciones {
  public $autorizacion; // autorizacion
}

class autorizacion {
  public $estado; // string
  public $numeroAutorizacion; // string
  public $fechaAutorizacion; // dateTime
  public $ambiente; // string
  public $comprobante; // string
  public $claveAcceso; // string
  public $mensajes; // mensajes
}

/*class mensajes {
  public $mensaje; // mensaje
}

class mensaje {
  public $identificador; // string
  public $mensaje; // string
  public $informacionAdicional; // string
  public $tipo; // string
}*/

class autorizacionComprobanteLote {
  public $claveAccesoLote; // string
}

class autorizacionComprobanteLoteResponse {
  public $RespuestaAutorizacionLote; // respuestaLote
}

class respuestaLote {
  public $claveAccesoLoteConsultada; // string
  public $numeroComprobantesLote; // string
  public $autorizaciones; // autorizaciones
}

/*class autorizaciones {
  public $autorizacion; // autorizacion
}*/


/**
 * AutorizacionComprobantesService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class AutorizacionComprobantesService extends SoapClient {

  private static $classmap = array(
                                    'autorizacionComprobante' => 'autorizacionComprobante',
                                    'autorizacionComprobanteResponse' => 'autorizacionComprobanteResponse',
                                    'respuestaComprobante' => 'respuestaComprobante',
                                    'autorizaciones' => 'autorizaciones',
                                    'autorizacion' => 'autorizacion',
                                    'mensajes' => 'mensajes',
                                    'mensaje' => 'mensaje',
                                    'autorizacionComprobanteLote' => 'autorizacionComprobanteLote',
                                    'autorizacionComprobanteLoteResponse' => 'autorizacionComprobanteLoteResponse',
                                    'respuestaLote' => 'respuestaLote',
                                    'autorizaciones' => 'autorizaciones',
                                   );

  public function AutorizacionComprobantesService($wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl", $options = array()) {
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
   * @param autorizacionComprobante $parameters
   * @return autorizacionComprobanteResponse
   */
  public function autorizacionComprobante(autorizacionComprobante $parameters) {
    return $this->__soapCall('autorizacionComprobante', array($parameters),       array(
            'uri' => 'http://ec.gob.sri.ws.autorizacion',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param autorizacionComprobanteLote $parameters
   * @return autorizacionComprobanteLoteResponse
   */
  public function autorizacionComprobanteLote(autorizacionComprobanteLote $parameters) {
    return $this->__soapCall('autorizacionComprobanteLote', array($parameters),       array(
            'uri' => 'http://ec.gob.sri.ws.autorizacion',
            'soapaction' => ''
           )
      );
  }

}

?>
