<?php

require 'class.phpmailer.php';
require 'class.smtp.php';


class ControladorMail{

	public function enviarMail($destinatarios,$asunto, $cuerpoMensaje, $adjuntos=null){
		
		$mail = new PHPMailer(true); 
		$mail->IsSMTP();   
		try {                                   
			$mail->Host = 'mail.agrocalidad.gob.ec';             
			$mail->Port = 587;                                   
			$mail->SMTPAuth = true;                              
			$mail->Username = 'sistema.guia@agrocalidad.gob.ec'; 			
			$mail->Password = 'epscj2015GUIA@';                               
			$mail->SMTPSecure = 'tls';                          
			$mail->SetFrom('sistema.guia@agrocalidad.gob.ec', 'Sistema integrado G.U.I.A. AGROCALIDAD');
			
			for ($i=0; $i<count($destinatarios); $i++){
				$mail->AddAddress($destinatarios[$i]); 
			}
			
					
			$mail->IsHTML(true);                                
			$mail->CharSet='UTF-8';
			$mail->Priority=1;									
		
			$mail->Subject = $asunto;
			$mail->Body    = $cuerpoMensaje;
			
			for ($i=0; $i<count($adjuntos); $i++){
				$mail->AddAttachment($adjuntos[$i]);
			}
		
			$mail->Send(); 	  
		
			return 'Mail enviado.';
		    }
		    
		  catch (phpmailerException $e){
				return $e->errorMessage();
		  }catch (Exception $e){
				return $e->getMessage();
		  } 
	}
}
