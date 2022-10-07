<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$fecha_fin=$_POST['fecha_fin'];
	
	$datos = array( 'regimenLaboral' => htmlspecialchars ( $_POST['nombreRegimenLaboral'],ENT_NOQUOTES,'UTF-8'),
			'tipoContrato' =>  htmlspecialchars ($_POST['nombreModalidadContrato'],ENT_NOQUOTES,'UTF-8'),
			'partidaIndividual' => htmlspecialchars ($_POST['partida_individual'],ENT_NOQUOTES,'UTF-8'),
			'fuente' => htmlspecialchars ($_POST['fuente'],ENT_NOQUOTES,'UTF-8'),
			'puestoInstitucional' => htmlspecialchars ($_POST['puesto_institucional'],ENT_NOQUOTES,'UTF-8'),
			'grupoOcupacional' =>  htmlspecialchars ($_POST['grupo_ocupacional'],ENT_NOQUOTES,'UTF-8'),
			'idProvincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
			'nombreProvincia' => htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'),
			'idCanton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),				
			'nombreCanton' => htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'),				
			'idOficina' => htmlspecialchars ($_POST['oficina'],ENT_NOQUOTES,'UTF-8'),							
			'nombreOficina' => htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8'),
			'coordinacion' => htmlspecialchars ($_POST['nombreCoordinacion'],ENT_NOQUOTES,'UTF-8'),							
			'direccion' => htmlspecialchars ($_POST['nombreDireccion'],ENT_NOQUOTES,'UTF-8'),				
			'idGestion' => htmlspecialchars ($_POST['gestion'],ENT_NOQUOTES,'UTF-8'),			
			'gestion' => htmlspecialchars ($_POST['nombreGestion'],ENT_NOQUOTES,'UTF-8'),
			'numeroContrato' => htmlspecialchars ($_POST['numero_contrato'],ENT_NOQUOTES,'UTF-8'),
			'fechaInicio' => htmlspecialchars ($_POST['fecha_inicio'],ENT_NOQUOTES,'UTF-8'),
			'fechaFin' => htmlspecialchars ($fecha_fin,ENT_NOQUOTES,'UTF-8'),
			'numeroNotaria' =>  htmlspecialchars ($_POST['numero_notaria'],ENT_NOQUOTES,'UTF-8'),
			'fechaDeclaracion' => htmlspecialchars ($_POST['fecha_declaracion'],ENT_NOQUOTES,'UTF-8'),
			'lugarNotaria' => htmlspecialchars ($_POST['lugar_notaria'],ENT_NOQUOTES,'UTF-8'),
			'remuneracion' => htmlspecialchars ($_POST['remuneracion'],ENT_NOQUOTES,'UTF-8'),
			'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
			'grado' => htmlspecialchars ($_POST['grado'],ENT_NOQUOTES,'UTF-8'),
			'estado' => htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8'),
	        'nombreProvinciaNotaria' => htmlspecialchars ($_POST['nombreProvinciaNotaria'],ENT_NOQUOTES,'UTF-8'),
    	    'nombreCantonNotaria' => htmlspecialchars ($_POST['nombreCantonNotaria'],ENT_NOQUOTES,'UTF-8'),
    	    'nombreRol' => htmlspecialchars ($_POST['nombreRol'],ENT_NOQUOTES,'UTF-8'),
    	    'informacion_puesto' => htmlspecialchars ($_POST['informacion_puesto'],ENT_NOQUOTES,'UTF-8'),
    	    'pluriempleo' => htmlspecialchars ($_POST['pluriempleo'],ENT_NOQUOTES,'UTF-8'),
    	    'fecha_ingreso_sector_publico' => htmlspecialchars ($_POST['fecha_ingreso_sector_publico'],ENT_NOQUOTES,'UTF-8'),
    	    'impedimento' => htmlspecialchars ($_POST['impedimento'],ENT_NOQUOTES,'UTF-8')
	    
	         );

	$arrayPresupuesto =  explode(' - ', $_POST['presupuesto']) ;
	
	$presupuesto = $arrayPresupuesto[0];
	$partidaPresupuestaria = $arrayPresupuesto[1];
	
	
	$archivo= $_POST['archivo'];
	$identificadorEmpleado=$_POST['identificadorEmpleado'];
	
	$fecha_salida='';
	if($_POST['condicion']=='3'){
		$fecha_salida=$fecha_fin;		
	}
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				
				$res = $cc->buscarContratosFechas($conexion, $datos['numeroContrato'] ,$datos['fechaInicio'], $datos['fechaFin']);
				
				if(pg_num_rows($res) == 0){
					
					//$cc->cambiarEstadoContrato($conexion, $usuarioSeleccionado);
						
					$cc->crearDatosContrato($conexion, $identificadorEmpleado, $datos['tipoContrato'], $datos['numeroContrato'], $datos['fechaInicio'], $datos['fechaFin'], 
											$datos['observacion'], $archivo, $datos['regimenLaboral'], ($datos['numeroNotaria']==''?0:$datos['numeroNotaria']), $datos['lugarNotaria'], $datos['fechaDeclaracion'], 
											$datos['grupoOcupacional'], $datos['puestoInstitucional'], $partidaPresupuestaria, $presupuesto,
											$datos['remuneracion'], $datos['fuente'], $datos['grado'],$datos['partidaIndividual'],$datos['nombreProvincia'],$datos['nombreCanton'],
					                        $datos['idOficina'], $datos['nombreOficina'], $datos['coordinacion'],$datos['direccion'],$datos['gestion'], $datos['idGestion'], $datos['estado'],$fecha_salida,
					                        $datos['nombreProvinciaNotaria'],$datos['nombreCantonNotaria'],$datos['nombreRol'],$datos['informacion_puesto'],$datos['pluriempleo'],$datos['fecha_ingreso_sector_publico'],$datos['impedimento']);
					
					$tipoUsuario = pg_fetch_result($cc->filtroObtenerDatosFuncionario($conexion, $identificadorEmpleado), 0, 'tipo_empleado');
					
					if($tipoUsuario == 'Interno'){
						
						if(pg_num_rows($cc->buscarFuncionario($conexion, $identificadorEmpleado))==0){
								
							$cc->guardarFuncionario($conexion, $datos['idGestion'], $identificadorEmpleado, 0, 1, $datos['idProvincia'], $datos['idCanton'], $datos['idOficina'], 1);
								
						}else{
								
							$cc->actualizarFuncionario($conexion, $datos['idGestion'], $identificadorEmpleado,0, 1, $datos['idProvincia'], $datos['idCanton'], $datos['idOficina'], 1);
								
							//echo ($datos['idGestion']. '</br>'.$usuario_seleccionado.'</br>'.'0'.'</br>'.'1'.'</br>'. $datos['idProvincia'].'</br>'. $datos['idCanton'].'</br>'. $datos['idOficina'].'</br>'.'1');
										
						}
						
					}
											
					///FIN NUEVO ACTUALIZAR O INSERTAR FUNCIONARIO///
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				
				}else{
					$mensaje['estado'] = 'alerta';
					$mensaje['mensaje'] = 'El número de contrato en las fechas indicadas ya se encuentra ingresado.';
				}
				
				$conexion->desconectar();
				echo json_encode($mensaje);
			} catch (Exception $ex){
					pg_close($conexion);
					$error=$ex->getMessage();
					//$firephp->warn('Captura Error:'.$error);
					$mensaje['estado'] = 'error';
					$suma_cod_error;
					$error_code=0;
					$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
					$error_code= $error_code + $suma_cod_error;
					$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
					$error_code= $error_code + $suma_cod_error;
					////$firephp->warn('Captura Error:'.$error);
					////$firephp->warn('Visor:'.stristr($error, 'duplicate key'));
					////$firephp->warn('Error Code:'.$error_code);
					
					////$firephp->warn('Error Code:'.$error_code);
					switch($error_code){
						case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
							break;	
						case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
							break;
					}
					echo json_encode($mensaje);
			}
/*	}catch (Exception $ex){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al subir el archivo";
			echo json_encode($mensaje);
	}*/
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>