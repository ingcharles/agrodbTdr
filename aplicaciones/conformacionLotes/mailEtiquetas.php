<?php
//session_start();

class mailEtiquetas{

    public function generarMail($idModulo,$producto){
        
        require_once '../../clases/Conexion.php';
        require_once '../../clases/ControladorMail.php';
        require_once '../../clases/ControladorLotes.php';
        require_once '../../clases/ControladorCatastro.php';
        require_once '../../clases/ControladorAdministrarCaracteristicas.php';
        
        
        define('IN_MSG','<br/> >>> ');
        set_time_limit(1000);
        //echo IN_MSG.'Enviar mail encuesta<br>';
        
        try {
        	$conexion = new Conexion();
        	$cMail = new ControladorMail();        	
        	$cl = new ControladorLotes();
        	$cc = new ControladorAdministrarCaracteristicas();
        	$ca = new ControladorCatastro();
        	$conexion->ejecutarConsulta("begin;");	
        
        //------------------------------------enviar mail para asignar etiqueta--------------------------------------------------------------------------------
    
        	$consulta=$cc->obtenerUsuariosXmodulo($conexion, $idModulo);
        	
        	while($datos = pg_fetch_assoc($consulta)){
        		
        			$fila=pg_fetch_assoc($ca->obtenerDatosUsuarioAgrocalidad($conexion,$datos['identificador']));
        			//$fila=pg_fetch_assoc($ca->obtenerDatosUsuarioAgrocalidad($conexion,'1724096456'));
        			$asunto = 'PRODUCTO SIN ETIQUETA ASIGNADA PARA MÓDULO DE CONFORMACIÓN DE LOTES';
        			$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
        			$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
        			
        			$cuerpoMensaje = '<table><tbody>
        			<tr><td style="'.$familiaLetra.'; text-align:center; font-size:30px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Sistema GUIA <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
        			<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
        			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">Asignación de plantilla Módulo de conformación de Lotes</td></tr>
        			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
        			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Por favor Asignar la Plantilla correspondiente para el producto indicado "'.$producto.'". Para realizar esta asignación debe ingresar en Sistema GUIA >> ADMINISTRACIÓN DE ETIQUETAS >> ASIGNACIÓN DE PLANTILLAS</td></tr>   			
        			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>			
        			</tbody></table>';
        			
        			
        			$destinatario = array();
        			$mailDestino='';
        			if($fila['mail_institucional']!= ''){
        				if (filter_var($fila['mail_institucional'], FILTER_VALIDATE_EMAIL)) {
        					array_push($destinatario, $fila['mail_institucional']);
        					$mailDestino=$fila['mail_institucional'];
        				} else if($fila['mail_personal'] !=''){
        					if (filter_var($fila['mail_personal'], FILTER_VALIDATE_EMAIL)) {
        						array_push($destinatario, $fila['mail_personal']);
        						$mailDestino=$fila['mail_personal'];
        					}
        				}
        			} 
        			
        			$codigoModulo = '';
        			$tablaModulo = '';
        			
        			if (filter_var($mailDestino, FILTER_VALIDATE_EMAIL)) {
        			
        			//echo IN_MSG.'mail ->  '.$mailDestino.'->>'."mail valido";
        			
        			$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
        			$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
        			
        			$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
        			
        			} else{
        				//echo IN_MSG.'mail ->  '.$mailDestino.'->>'."mail no valido";
        			}
        		}
        	
        	$conexion->ejecutarConsulta("commit;");
        	//echo IN_MSG.'proceso terminado ';
        	
        } catch (Exception $ex) {
        	$conexion->ejecutarConsulta("rollback;");
        	//echo IN_MSG.'Error de ejecucion '.$ex;
        } finally {
        	$conexion->desconectar();
        }

    }

}
?>
