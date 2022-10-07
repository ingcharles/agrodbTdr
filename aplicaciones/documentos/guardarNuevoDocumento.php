<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$cd = new ControladorDocumentos();
$ca = new ControladorAuditoria();

//Validar sesion
$conexion->verificarSesion();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<?php

	$datos = array('codigoPlantilla' => htmlspecialchars ($_POST['codigoPlantilla'],ENT_NOQUOTES,'UTF-8'), 
				'codigoVersion' => htmlspecialchars ($_POST['versionPlantilla'],ENT_NOQUOTES,'UTF-8'),
				'archivoPlantilla' =>  htmlspecialchars ($_POST['archivoPlantilla'],ENT_NOQUOTES,'UTF-8'), 
				'descripcionDocumento' => htmlspecialchars ($_POST['descripcionDocumento'],ENT_QUOTES,'UTF-8'), 
				'identificadorUsuario' =>  htmlspecialchars ($_SESSION['usuario'],ENT_NOQUOTES,'UTF-8'));
	
	$registrador_id= ($_POST['registrador_id']);
	$registrador_nombre= ($_POST['registrador_nombre']);
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	//$tipo_aplicacion = 1;
	

	
		
	$idDocumento = 'TMP-' . $_SESSION['usuario'] . '-' . date("hmsdmY");
	//$idDocumento = $cd->generarNombreArchivo($conexion,$datos['codigoVersion'],$datos['codigoPlantilla'],$datos['archivoPlantilla']); //nombre definitivo

	//esta linea especifica el idioma para la fecha completa
	setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	
	$valores = array(//'#*NUMERO*#' => $idDocumento,
					 '_FECHA_' => date('d/m/Y'),
					 //'#*FECHA COMPLETA*#' => date('d \d\e F \d\e\l Y'),
					//'#*FECHA COMPLETA*#' => strftime("%d de %B del %Y"),
					 '_Autor_' => $datos['identificadorUsuario']);
	
	$cd->rtf($datos['archivoPlantilla'], $idDocumento, $valores);
	
	$res = $cd -> ingresaSolicitud($conexion,'documento');
	$fila =  pg_fetch_assoc($res);
	$cd->ingresarNuevoDocumento($conexion, $idDocumento, $datos, $fila['id_solicitud']);
	
	$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
	$qTransaccion = $ca ->guardarTransaccion($conexion, $fila['id_solicitud'], pg_fetch_result($qLog, 0, 'id_log'));
		
	for ($i = 0; $i < count ($registrador_id); $i++) {
		 $cd -> ingresaRegistradores($conexion, $fila['id_solicitud'], $registrador_id[$i],'Revisor');
	}
	
	echo '<header>
			<h1>Documento generado</h1>
		</header>
			<p>El documento <a href="aplicaciones/documentos/generados/'.$idDocumento.'.docx" target="_black">'.$idDocumento.'</a> se ha generado satisfactoriamente.</p>
			<p><a href="aplicaciones/documentos/generados/'.$idDocumento.'.docx" target="_black" class="descarga"></a></p>
			
			<p>Los <b>funcionarios asignados</b> para revisar este documento son: </p>
			
            
            <ol>';
	
			for ($i = 0; $i < count ($registrador_nombre); $i++) {
               echo'<li>'.$registrador_nombre[$i].'</li>';
               $registrador .= $registrador_nombre[$i]. ', ';
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> genera el documento '.$idDocumento.' y asigno a:  ' .$registrador);
                
           echo '</ol>
					
		<p class="nota">Este documento estará disponible a los "revisores" una vez que haya subido el primer borrador del documento final.</p>';
	?>
</body>
<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
</script>
</html>
