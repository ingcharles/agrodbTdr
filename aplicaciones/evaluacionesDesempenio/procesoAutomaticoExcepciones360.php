<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorCatastro.php';

define('IN_MSG','<br/> >>> ');
echo IN_MSG.'Inicio Proceso automático excepciones 360 ';
try{
	$conexion = new Conexion();
    $ced = new ControladorEvaluacionesDesempenio();
	
	$fechaActual=strtotime(date('Y-m-d'));
	
		try {
			
			$consulta360 = $ced->devolverEvaluacionVigente ($conexion,1);
				
			while($cons = pg_fetch_assoc($consulta360)){
				
				echo IN_MSG.'Proceso automático excepciones';	
				$consulta360ss=$ced->devolverExcepcionesVigente($conexion,$cons['id_evaluacion']);
				while($consulta = pg_fetch_assoc($consulta360ss)){
					$fechaIni = strtotime($consulta['fecha_inicio']);
					$fechaFin = strtotime($consulta['fecha_fin']);
					$conexion->ejecutarConsulta("begin;");
					//------------------------------------activar proceso de evaluacion--------------------------------------------------------------------------------
					if($fechaActual>=$fechaIni && $fechaActual <=$fechaFin){
						
						if($consulta['estado']=='activo' ){ 
						echo IN_MSG.'Activacion de excepciones..';
						$ced->excepcionAplicantesIndividual($conexion,'activo', 'activo',$cons['id_evaluacion'],'proceso',$consulta['identificador_evaluador']);
						$ced->excepcionAplicantes($conexion,'activo', 'activo',$cons['id_evaluacion'],'proceso',$consulta['identificador_evaluador']);
						$ced->actualizarExcepcionesVigente($conexion,$consulta['id_excepcion_evaluacion'],'proceso');
						}
					
					}
					if($fechaActual > $fechaFin){
						if($consulta['estado']=='proceso' ){
						echo IN_MSG.'Inactivacion de excepciones..';
						$ced->excepcionAplicantesIndividual($conexion,'finalizado', 'proceso',$cons['id_evaluacion'],'finalizado',$consulta['identificador_evaluador']);
						$ced->excepcionAplicantes($conexion,'finalizado', 'proceso',$cons['id_evaluacion'],'finalizado',$consulta['identificador_evaluador']);
						$ced->actualizarExcepcionesVigente($conexion,$consulta['id_excepcion_evaluacion'],'cerrado');
						}
					}
					$conexion->ejecutarConsulta("commit;");
			  }		
			}		
		} catch (Exception $ex){
			$conexion->ejecutarConsulta("rollback;");
			echo IN_MSG.'Error de ejecucion'.$ex;
		} finally {
			$conexion->desconectar();
		}
} catch (Exception $ex) {
	echo IN_MSG.'Error de conexión a la base de datos';
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
?>
</body>
</html>