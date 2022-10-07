<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMail.php';
require_once '../../../clases/ControladorRegistroOperador.php';


$cm = new ControladorMail();
$cr = new ControladorRegistroOperador();
$conexion = new Conexion();

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$mailUsuario = htmlspecialchars ($_POST['mail'],ENT_NOQUOTES,'UTF-8');
$idCrearOperador = htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$consulta=$cr->obtenerCrearOperador($conexion, $idCrearOperador);
$codigo = pg_fetch_result($consulta, 0, 'codigo_verificacion');
if($codigo != ''){
        if($mailUsuario != ''){
                $asunto = 'Creación de usuario Sistema GUIA';
                
                $familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
                $letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
                
                $cuerpoMensaje = '<table><tbody>
                							<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:#2a2a2a; font-weight:bold;">Cuenta Sistema GUIA</td></tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:16px; color:#2a2a2a;">Código para crear usuario </td></tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Le notificamos que se realizó una solicitud de creación de usuario en el Sistema GUIA..</tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">Tu código es: <span style="'.$letraCodigo.' font-size:14px; font-weight:bold; color:#2a2a2a;">'.$codigo.'</span></td></tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Si no es así, por favor comunicarse inmediatamente al 1800 AGRO00.</td></tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Recuerde que es su responsabilidad el cuidado de la información de acceso al sistema G.U.I.A. Por ningún motivo comparta su contraseña con terceros y si sospecha que alguien más tiene conocimiento de ésta, proceda al cambio inmediato.</td></tr>
                							<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">¡Gracias! </td></tr>
                							</tbody></table>';
                
                $destinatario = array();
                array_push($destinatario, $mailUsuario);
                $mensaje['mensaje'] = $cm->enviarMail($conexion, $destinatario, $asunto, $cuerpoMensaje);
                echo json_encode($mensaje);
        }else{
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = 'Error al enviar el mail..!!';
            echo json_encode($mensaje);
        }
}else{
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error al obtener el código..!!';
    echo json_encode($mensaje);
}