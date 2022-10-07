<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMail.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorMailMasivo.php';

$conexion = new Conexion();
$cMail = new ControladorMail();
$cro = new ControladorRegistroOperador();
$cmm = new ControladorMailMasivo()

$nombreRequerimiento="GLPI #51883";
$areas = "'IAV','IAP'";
$operaciones = "'ALM'";

$datosOperador=$cro->obtenerOperadoresPorTipoOperacionYarea($conexion,"(".$operaciones.")","(".$areas.")");

define('IN_MSG','<br/> >>> ');
$asunto = 'IMPORTANTE AGROCALIDAD INFORMA';

$familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";

$cuerpoMensaje='<html xmlns="http://www.w3.org/1999/xhtml"><tbody><table>	
<tr><td style="'.$familiaLetra.'; padding-top:10px; font-size:14px;color:#2a2a2a;">Estimado Almacenista,<td></tr>
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">AGROCALIDAD con el apoyo de la industria de plaguicidas APCSA e INNOVAGRO, se encuentra desarrollando a nivel nacional el proyecto emblema “Inventario de plaguicidas obsoletos” con el objetivo de identificar en detalle las existencias de productos caducados, productos sin registro, frasco y/o fundas de plaguicidas deteriorados, lo cual contribuirá a determinar la magnitud de la problemática para planificar la implementación de las fases posteriores como son salvaguarda, disposición final y prevención.</td></tr>
		
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">El inicio del inventario será este 8 de mayo del 2017 a nivel nacional  y el cronograma detallado se indica a continuación:</td></tr>
		
<tr><td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/procesosAutomaticos/mailMasivo/img/1.png"></td></tr>
<tr><td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/procesosAutomaticos/mailMasivo/img/2.png"></td></tr>
<tr><td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/procesosAutomaticos/mailMasivo/img/3.png"></td></tr>
<tr><td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/procesosAutomaticos/mailMasivo/img/4.png"></td></tr>
<tr><td><img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/procesosAutomaticos/mailMasivo/img/5.png"></td></tr>

		
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">Por todas estas razones, es labor de todos los involucrados en el sector el deshacernos de esas substancias para facilitar el comercio y disminuir los riesgos en sus locales de expendio y bodegas.</td></tr>

<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">Finalizando el proyecto de inventario en campo en el mes de junio del presente año, por lo cual realizamos las siguientes recomendaciones a todos los almacenistas de expendio de plaguicidas en todo el país:</td></tr>
		
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">• Organizar el área de producto no conforme en su almacén de acuerdo a las capacitaciones impartidas por AGROCALIDAD</td></tr>
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">• Colocar los productos caducados, sin registro, frascos o fundas deteriorados, moléculas prohibidas, material contaminado en área de producto no conforme</td></tr>
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">• Permitir el ingreso del personal de la consultora ADVANCE autorizado por AGROCALIDAD que portara un carnet de identificación</td></tr>
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">• Brindar todo el apoyo que el personal autorizado le solicite para la correcta ejecución del inventario</td></tr>
								
<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a; width: 80%; text-align:justify;">Para mayor información usted puede contactarse con el líder del proyecto: Ing. Diana Ayala (diana.ayala@agrocalidad.gob.ec)</td></tr>
<tr><td style="'.$familiaLetra.'; padding-top:60px; font-size:14px;color:#2a2a2a;">Con la ayuda de todos, lograremos alcanzar un gran objetivo: Ecuador libre de plaguicidas obsoletos!!</td></tr>
</table></tbody></html>';

while ($fila = pg_fetch_assoc($datosOperador)){
	
	$destinatario = array();
	if($fila['correo']!= ''){
		array_push($destinatario, $fila['correo']);
	}
	
	$fecha = date("Y-m-d h:m:s");
	
	echo IN_MSG . $fecha;
	echo IN_MSG . 'Envio correo electronico: '.$fila['nombres'].' '.$fila['identificador'];	
	
	$estadoMail = $cMail->enviarMail($destinatario, $asunto, $cuerpoMensaje);	
	$cmm->guardarMailMasivo($conexion, $fila['identificador'], $fila['correo'] ,$nombreRequerimiento, str_replace("'", '' ,$operaciones), str_replace("'", '' ,$areas), $estadoMail);
	
	echo IN_MSG . 'Envio correo masivo asunto: '.$asunto.'.';
	echo '<br>';
}
?>