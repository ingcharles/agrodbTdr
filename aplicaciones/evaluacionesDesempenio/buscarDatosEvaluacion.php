<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorAreas.php';
try {

	$conexion = new Conexion();
	$ced = new ControladorEvaluacionesDesempenio();
	$car = new ControladorAreas ();
	
	$consulta=$ced->buscarDatosServidor($conexion,$_POST["identificador"]);
	if(pg_num_rows($consulta) != 0){
		//---------verificar responsabilidad-------------------------------------------
		$responsable= $ced-> obtenerResponsabilidad($conexion, $_POST["identificador"]);
		$ban=1;
		while($fila = pg_fetch_assoc($responsable)){
			$ban=0;
			if(pg_num_rows($responsable) > 1){
				$result .= '<br>->';
				$result .= $fila['nombre'];
			}else{
				$result .= $fila['nombre'];
			}
		}
		if($ban)$result='No';
		//---------------------------------------------------------------------------------
		$pendientes= $ced->abrirEvaluacionDisponibleUsuario ($conexion, $_POST["identificador"], 'finalizado',$_POST["idEvaluacion"],'creado');
		$superior=''; $inferior=''; $pares=''; $autoevaluacion=''; $individual=''; $existeEvaluacion=0;
		while($fila = pg_fetch_assoc($pendientes)){
			if($fila['tipo']=='superior'){ $superior.=$fila['nombres_completos']; $superior.='<br>'; $superior1.='<br>'; $existeEvaluacion=1;}
			if($fila['tipo']=='inferior'){ $inferior.=$fila['nombres_completos'];$inferior.='<br>';$inferior1.='<br>'; $existeEvaluacion=1;}
			if($fila['tipo']=='pares'){$pares.=$fila['nombres_completos'];$pares.='<br>'; $pares1.='<br>'; $existeEvaluacion=1;}
			if($fila['tipo']=='autoevaluacion'){$autoevaluacion='Si'; $existeEvaluacion=1;}
			
		}
		//---------------------------------------------------------------------------------
		$qListaAplicantes=$ced->listarAplicantesEvaluacionIndividual($conexion, $_POST["identificador"],'finalizado',$_POST["idEvaluacion"]);
		while($aplicantes = pg_fetch_assoc($qListaAplicantes)){
			$listaAplicantes = $car->listarAplicantesEvaluacionIndividual($conexion,$aplicantes['identificador_evaluado'],'finalizado');
			$fila = pg_fetch_assoc($listaAplicantes);
			if($fila['identificador_evaluado'] != ''){
				$existeEvaluacion=1;
				$individual.=$fila['nombres_completos']; $individual.='<br>'; $individual1.='<br>';}
		}
		//---------------------------------------------------------------------------------
		$valores_datos=pg_fetch_array($consulta);
		$return = array(
				'nombre'=>ucfirst($valores_datos['nombre']),
				'apellido'=>ucfirst($valores_datos['apellido']),
				'provincia'=>$valores_datos['provincia'],
				'canton'=>$valores_datos['canton'],
				'oficina'=>$valores_datos['oficina'],
				'coordinacion'=>$valores_datos['coordinacion'],
				'direccion'=>$valores_datos['direccion'],
				'gestion'=>$valores_datos['gestion'],
				'responsable'=>$result,
				'superior'=>$superior,
				'superior1'=>$superior1,
				'inferior'=>$inferior,
				'inferior1'=>$inferior1,
				'pares'=>$pares,
				'pares1'=>$pares1,
				'autoevaluacion'=>$autoevaluacion,
				'individual'=>$individual,
				'individual1'=>$individual1,
				'existeEvaluacion'=>$existeEvaluacion
				
				);
	}else{
	
		$return = array('error'=>'No existe ningun registro..!!!');
	}
	
} catch (Exception $e) {
	$return = array('error'=>'Error Desconocido..!!!');
} finally {
	$conexion->desconectar();
	die(json_encode($return));
}
?>