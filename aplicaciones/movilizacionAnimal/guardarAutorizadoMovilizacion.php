<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
//print_r($_POST);

if($opcion==1){//Busqueda del propietario que va a autorizar el documento
	$tipoSitio = htmlspecialchars ($_POST['tipoBusquedaSitio'],ENT_NOQUOTES,'UTF-8');
	$varSitio = htmlspecialchars ($_POST['txtSitioBusqueda'],ENT_NOQUOTES,'UTF-8');

	$sitios = $cm->listaSitioArea($conexion,$tipoSitio, $varSitio);
	echo '<label>Nombre del sitio: </label>';
	echo '<select id="cmbSitio" name="cmbSitio">';
	echo '<option value="0">Seleccione sitio....</option>';
	while ($fila = pg_fetch_assoc($sitios)){ 
		echo '<option value="'. $fila['id_sitio'].'" data-area="'.$fila['id_area'].'" data-identificador-propiedatio="'.$fila['identificador_operador'].'">'.$fila['provincia'].' - '.$fila['identificador_operador'].' - '.$fila['nombres'].' - '.$fila['granja'].' -  '.$fila['nombre_area'].'</option>';
	}
	echo '</select>';
}
if($opcion==2){//autorizado para realizar trámites de movilización
	$tipo = htmlspecialchars ($_POST['tipoBusqueda'],ENT_NOQUOTES,'UTF-8');
	$busqueda = htmlspecialchars ($_POST['autorizadoMovilizacion'],ENT_NOQUOTES,'UTF-8');

	$autorizado = $cm->listaAutorizados($conexion,$tipo, $busqueda);
	echo '<label>Nombre del sitio: </label>';
	echo '<select id="cmbAutorizado" name="cmbAutorizado">';
	echo '<option value="0">Seleccione autorizado....</option>';
	while ($fila = pg_fetch_assoc($autorizado)){
		echo '<option value="'. $fila['identificador'].'">'.$fila['identificador'].' - '.$fila['nombre_autorizado'].'</option>';
	}
	echo '</select>';
}
if($opcion==10){			
		$datos = array(
				'id_sitio' => htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8'),
				'id_area' => htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8'),
			    'identificador_propietario' => htmlspecialchars ($_POST['identificador_propietario'],ENT_NOQUOTES,'UTF-8'), 
			    'identificador_autorizado' => htmlspecialchars ($_POST['cmbAutorizado'],ENT_NOQUOTES,'UTF-8'),
			    'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),				   		
				'fecha_autorizacion' => htmlspecialchars ($_POST['fecha_autorizacion'],ENT_NOQUOTES,'UTF-8'),
				'estado' => 'activo'
				);
		
		$dMovilizador = $cm->guardarAutorizacionMovilizacion($conexion
				, $datos['id_sitio'], $datos['id_area'], $datos['identificador_propietario'], $datos['identificador_autorizado']
				, $datos['observacion'], $datos['fecha_autorizacion'], $datos['estado']);
		
		echo " --> guardar autorizado de movilización";
		$conexion->desconectar();
}	
?>
</body>
<script type="text/javascript">

	$("#cmbSitio").change(function(){		
    	if ($("#cmbSitio").val() != 0){			
			$("#id_area").val($('#cmbSitio option:selected').attr('data-area'));				   				
			$("#identificador_propietario").val($('#cmbSitio option:selected').attr('data-identificador-propiedatio'));
		}
	});

</script>
</html>






