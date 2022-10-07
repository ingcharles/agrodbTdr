<?php

class ControladorFirmaDocumentos
{

    public function buscarFirmante ($conexion, $identificador){
        
        $res = $conexion->ejecutarConsulta("SELECT
										      *
											FROM
												g_firma_documentos.firmantes
											WHERE
												identificador =  '$identificador';");
        return $res;
    }

    public function crearDocumentoParaFirmar($conexion, $parametrosFirma){

        $res = $conexion->ejecutarConsulta("INSERT INTO 
                                                g_firma_documentos.documentos(
                                                    identificador, razon_documento, 
                                                    archivo_entrada, archivo_salida, 
                                                    tabla_origen, id_origen, 
                                                    campo_origen, estado)
                                            VALUES ('".$parametrosFirma['identificador']."', '".$parametrosFirma['razon_documento']."', 
                                                    '".$parametrosFirma['archivo_entrada']."', '".$parametrosFirma['archivo_salida']."', 
                                                    '".$parametrosFirma['tabla_origen']."', ".$parametrosFirma['id_origen'].", 
                                                    '".$parametrosFirma['campo_origen']."', '".$parametrosFirma['estado']."');");
        
        return $res; 
    }
	
	 public function ingresoFirmaDocumento($conexion, $parametrosFirma) {
    	
    	$firma = $this->buscarFirmante($conexion, $parametrosFirma['identificador']);
    	
    	if(pg_num_rows($firma) > 0){
    		$this->crearDocumentoParaFirmar($conexion, $parametrosFirma);
    	}
    }
}