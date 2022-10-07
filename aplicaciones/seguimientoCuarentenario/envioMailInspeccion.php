<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';
require_once '../../clases/ControladorMail.php';

$conexion = new Conexion();
$csc = new ControladorSeguimientoCuarentenario();
$cMail = new ControladorMail();

define('PRO_MSG', '<br/> ... ');
define('IN_MSG','<br/> >>> ');
echo '<center><h1>ENVIO A INSERTAR CORREOS A TECNICOS INSPECTORES SEGUIMIENTOS DDA</h1></center>';
$contador=1;
$fecha = date("Y-m-d h:m:s");

$qBuscarDDAEnvioMail=$csc->buscarDDAEnvioMail($conexion);
foreach ($qBuscarDDAEnvioMail as $filaDDA){
	echo PRO_MSG .'<b>PROCESO #'.($contador).'</b>';
	echo IN_MSG .'<b>Fecha:'.$fecha.'</b>';
	echo IN_MSG .'<b>Solicitud: </b>'.$filaDDA['idDestinacionAduanera'];

	$qListadoTecnico=$csc->listarTecnicoInspectorProvinciaDDA($conexion, $filaDDA['nombreProvincia'], "('PFL_INSDO_DESAD','PFL_REIND_DESAD')");
	$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
			<style type="text/css">
			.titulo  {
			margin-top: 30px;
			width: 800px;
			text-align: center;
			font-size: 14px;
			font-weight: bold;
			font-family:Times New Roman;
			}
			.lineaDos{
			font-style: oblique;
			font-weight: normal;
			}
			.lineaLeft{
			text-align: left;
			}
			.lineaEspacio{
			height: 35px;
			}
			.lineaEspacioMedio{
			height: 50px;
			}
			.espacioLeft{
			padding-left: 15px;
			}
			</style>';
	$cuerpoMensaje.='<table class="titulo">
			<thead>
			<tr><th>Se ha registrado un documento de destinacion aduanera al proceso de cuarentena.</th></tr>
			</thead>
			<tbody>
			<tr><td class="lineaDos lineaEspacio">Por favor ingrese al Módulo de Seguimientos Cuarentenarios en el Sistema GUIA para consultar la información.</td>	</tr>
			<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
			</tbody>
			<tfooter>
			<tr><td class="lineaEspacioMedio"></td></tr>
			<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
			<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
			</tfooter>
			</table>';

	$asunto = 'SEGUIMIENTO CUARENTENARIO';
	$codigoModulo='PRG_DDA';
	$tablaModulo='g_dda.destinacion_aduanera';

	foreach ((array)$qListadoTecnico['listado_usuarios'] as $fila) {
		$destinatarios = array();
		if($fila['mail_institucional']!= ''){
			$destinatarios  = explode('; ',$fila['mail_institucional']);

		}else if($fila['mail_personal'] !=''){
			$destinatarios  = explode('; ',$fila['mail_personal']);
		}
			
		$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $filaDDA['idDestinacionAduanera']);
		$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
		$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
	}

	$csc->actualizarEstadoMailSeguimientoDDA($conexion, $filaDDA['idDestinacionAduanera']);
	echo IN_MSG . '<b>FIN PROCESO </b><br>';
}
?>