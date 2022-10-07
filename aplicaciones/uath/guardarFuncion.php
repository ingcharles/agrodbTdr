<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}


/*while ($tipoPregunta = pg_fetch_assoc($tipoPreguntas)){
$tipo = quitar_tildes($tipoPregunta['tipo']);
echo '<div id="'.strtolower(str_replace(' ', '',$tipo)).'">
<h2>'.$tipoPregunta['tipo'].'</h2>
<div class="elementos"></div>
</div>';

}*/



try{

	$idFuncion=$_POST['idFuncion'];//id Funcion existente

	$idPuestoInstitucional=$_POST['idPuesto'];//id del puesto

	$nombreFuncion=$_POST['nombreFuncion'];//nombre de funcion nueva o existente

	$nombreFuncionComparacion=str_replace(' ', '',strtolower(quitar_tildes($nombreFuncion)));//nombre de funcion nueva o existente
	
	//echo "</br>numerofuncion". $idFuncion;
	//echo "</br>nombrefuncion". $funcion;
	//echo "</br>nombregestion". $gestion;
	//echo "</br>idpuesto". $puestoInstitucional;
	//echo "</br>idpuesto". $idPuestoInstitucional;
	//echo "</br>nombrepuesto". $nombreFuncion;

	try {


		$conexion = new Conexion();
		$cc = new ControladorCatalogos();

		$qRegistroFuncion=$cc->listarFunciones($conexion);


	if($idFuncion==0){
		
		$ban=0;
		
		while ( $registroFuncion = pg_fetch_assoc ( $qRegistroFuncion ) ) {
		
			if(str_replace(' ', '',strtolower(quitar_tildes($registroFuncion['descripcion'])))==$nombreFuncionComparacion){
				$ban=1;
			}
		
		}
		
		if($ban!=1){
				
				$qFuncion =$cc->guardarFunciones($conexion, $nombreFuncion);
				$idFuncion = pg_fetch_result($qFuncion, 0, 'id_funcion');

				$cc->guardarDetallePuestoFuncion($conexion, $idPuestoInstitucional, $idFuncion);

				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cc->imprimirFuncionesXPuesto($idPuestoInstitucional,$idFuncion, $nombreFuncion);//'Se ha guardado con exito';//*/
			
		}else{
			
				$mensaje['mensaje'] = "La función ya fue registrada.";
		}
		

	}else{

		if(pg_num_rows($cc->buscarDetallePuestoFuncion($conexion, $idPuestoInstitucional, $idFuncion))==0){
						
			$cc->guardarDetallePuestoFuncion($conexion, $idPuestoInstitucional, $idFuncion);
						
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cc->imprimirFuncionesXPuesto($idPuestoInstitucional,$idFuncion, $nombreFuncion);//'Se ha guardado con exito';//				
						
		}else{
						
				$mensaje['mensaje'] = "La función ya ha sido asignada al puesto.";
				
			}
		
		}

		$conexion->desconectar();
		echo json_encode($mensaje);

	} catch (Exception $ex){
		
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
		
	}

} catch (Exception $ex) {
	
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
	
}

?>
