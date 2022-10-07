<?php

class ControladorComplementos{
    
    public function cargarPopup($titulo, $subtitulo, $contenido, $claseTitulo, $claseSubtitulo, $claseContenido, $rutaImagen='aplicaciones/general/img/ventanaEmergente.png', $estiloImagen = null){
        
    	if(isset($estiloImagen)){
    		$estiloImagen = 'class="'.$estiloImagen.'"';
    	}
    	
        $popup =  '<div id="popup" style="display: none;">
    	               <div class="content-popup">
        	        	<div>
        		           <div class="'.$claseTitulo.'">'.$titulo.'</div>
        		           <hr/>
        					 <div >
        						<img '.$estiloImagen.' src="'.$rutaImagen.'"> 
        							<div class="'.$claseSubtitulo.'">'.$subtitulo.'</div>
        							<div class="'.$claseContenido.'">'.$contenido.'</div>
        					</div>
        				   <div style="text-align: center;">
        		           		<button style = "text-align: center;" id="close" type="button" >Continuar</button>
        		           </div>
        	        	</div>
        	    	</div>
                </div>
                <script type="text/javascript"> $("#popup #close").click(function(){ $("#popup").fadeOut("slow");  $(".popup-overlay").fadeOut("slow");});</script>';
        
        return $popup;
    }
    
    public function cargarMensaje($titulo, $subtitulo, $contenido, $claseTitulo, $claseSubtitulo, $claseContenido, $rutaImagen= '../../../aplicaciones/general/img/ventanaEmergente.png'){
        
        $mensaje =  '<div id="popup">
    	               <div>
        	        	<div>
        		           <div class="'.$claseTitulo.'">'.$titulo.'</div>
        		           <hr/>
        					 <div >
        						<img src="'.$rutaImagen.'">
        							<div class="'.$claseSubtitulo.'">'.$subtitulo.'</div>
        							<div class="'.$claseContenido.'">'.$contenido.'</div>
        					</div>
        	        	</div>
        	    	</div>
                </div>';
        
        return $mensaje;
    }
    
    public static function encriptarClave($input, $key){
    	$encryption_key = base64_decode($key);
    	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    	$encrypted = openssl_encrypt($input, 'aes-256-cbc', $encryption_key, 0, $iv);
    	return base64_encode($encrypted . '::' . $iv);
    }
    
    public static function desencriptarClave($input, $Key){
    	$encryption_key = base64_decode($Key);
    	//list ($encrypted_data, $iv) = explode('::', base64_decode($input), 2);
    	$datosEncriptacion = explode('::', base64_decode($input));
    	$encrypted_data = $datosEncriptacion[0];
    	if(isset($datosEncriptacion[1])){
    		$iv = $datosEncriptacion[1];
    	}else{
    		$iv = '';
    	}
    	
    	return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

}