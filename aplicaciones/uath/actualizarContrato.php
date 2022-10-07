<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorVacaciones.php';



$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$datos = array('regimenLaboral' => htmlspecialchars ( $_POST['nombreRegimenLaboral'],ENT_NOQUOTES,'UTF-8'),
			'tipoContrato' =>  htmlspecialchars ($_POST['nombreModalidadContrato'],ENT_NOQUOTES,'UTF-8'),
			'partidaIndividual' => htmlspecialchars ($_POST['partida_individual'],ENT_NOQUOTES,'UTF-8'),
			'fuente' => htmlspecialchars ($_POST['fuente'],ENT_NOQUOTES,'UTF-8'),
			'presupuesto' => htmlspecialchars ( $_POST['presupuesto'],ENT_NOQUOTES,'UTF-8'),
			'puestoInstitucional' => htmlspecialchars ($_POST['puesto_institucional'],ENT_NOQUOTES,'UTF-8'),
			'grupoOcupacional' =>  htmlspecialchars ($_POST['grupo_ocupacional'],ENT_NOQUOTES,'UTF-8'),
			'lugarContratado' => htmlspecialchars ($_POST['lugar_contratado'],ENT_NOQUOTES,'UTF-8'),
			'numeroContrato' => htmlspecialchars ($_POST['numero_contrato'],ENT_NOQUOTES,'UTF-8'),
			'fechaInicio' => htmlspecialchars ($_POST['fecha_inicio'],ENT_NOQUOTES,'UTF-8'),
			'fechaFin' => htmlspecialchars ($_POST['fecha_fin'],ENT_NOQUOTES,'UTF-8'),
			'numeroNotaria' =>  htmlspecialchars ($_POST['numero_notaria'],ENT_NOQUOTES,'UTF-8'),
			'fechaDeclaracion' => htmlspecialchars ($_POST['fecha_declaracion'],ENT_NOQUOTES,'UTF-8'),
			'lugarNotaria' => htmlspecialchars ($_POST['lugar_notaria'],ENT_NOQUOTES,'UTF-8'),
			'remuneracion' => htmlspecialchars ($_POST['remuneracion'],ENT_NOQUOTES,'UTF-8'),
			'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
			'grado' => htmlspecialchars ($_POST['grado'],ENT_NOQUOTES,'UTF-8'),
			'provincia' =>  htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'),
			
			//cambio/
			'idProvincia' =>  htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
			/////
			
			'canton' => htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'),
			
			//cambio/
			'idCanton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
			/////
			
			'oficina' => htmlspecialchars ($_POST['oficina'],ENT_NOQUOTES,'UTF-8'),
			'nombreOficina' => htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8'),
			'coordinacion' => htmlspecialchars ($_POST['nombreCoordinacion'],ENT_NOQUOTES,'UTF-8'),
			'direccion' => htmlspecialchars ($_POST['nombreDireccion'],ENT_NOQUOTES,'UTF-8'),
			'gestion' => htmlspecialchars ($_POST['nombreGestion'],ENT_NOQUOTES,'UTF-8'),
			
			//cambio/
			'idGestion' => htmlspecialchars ($_POST['idGestion'],ENT_NOQUOTES,'UTF-8'),
			/////
			
			'estado' => htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8'),
			'terminacion_laboral' => htmlspecialchars ($_POST['terminacion_laboral'],ENT_NOQUOTES,'UTF-8'),
			'calificacion' => htmlspecialchars ($_POST['calificacion'],ENT_NOQUOTES,'UTF-8'),
			'escala_calificacion' => htmlspecialchars ($_POST['escala_calificacion'],ENT_NOQUOTES,'UTF-8'),
			'fecha_salida' => htmlspecialchars ($_POST['fecha_salida'],ENT_NOQUOTES,'UTF-8'),
    	    'provinciaNotaria' =>  htmlspecialchars ($_POST['nombreProvinciaNotaria'],ENT_NOQUOTES,'UTF-8'),
    	    'cantonNotaria' => htmlspecialchars ($_POST['nombreCantonNotaria'],ENT_NOQUOTES,'UTF-8'),
    	    'rol' => htmlspecialchars ($_POST['nombreRol'],ENT_NOQUOTES,'UTF-8'),
    	    'informacion_puesto' => htmlspecialchars ($_POST['informacion_puesto'],ENT_NOQUOTES,'UTF-8'),
    	    'pluriempleo' => htmlspecialchars ($_POST['pluriempleo'],ENT_NOQUOTES,'UTF-8'),
    	    'fecha_ingreso_sector_publico' => htmlspecialchars ($_POST['fecha_ingreso_sector_publico'],ENT_NOQUOTES,'UTF-8'),
    	    'impedimento' => htmlspecialchars ($_POST['impedimento'],ENT_NOQUOTES,'UTF-8')
	    
	);
	
	$archivo= $_POST['archivo'];
	$id_datos_contrato=$_POST['id_datos_contrato'];
	$usuario_seleccionado = $_POST['identificador'];


	$arrayPresupuesto =  explode(' - ', $_POST['presupuesto']) ;
	
	$presupuesto = $arrayPresupuesto[0];
	$partidaPresupuestaria = $arrayPresupuesto[1];
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$cv = new ControladorVacaciones();
				
				//***************proceso de asignación de saldo para liquidación de vacaciones******************************
				
				if($datos['estado'] == 3){
					$saldo = 0;
					$anio = array();
					$result = $cv->consultarSaldoFuncionario($conexion, $usuario_seleccionado);
					if(pg_num_rows($result)>0){
						$consulta = pg_fetch_assoc($result);
						$saldo = $consulta['minutos_disponibles'];
						$anio []= $consulta['anio'];
					}
					$result = $cv->consultarSaldoFuncionarioNuevo($conexion, $usuario_seleccionado);
					if(pg_num_rows($result)>0){
						$consulta = pg_fetch_assoc($result);
						$saldo = $saldo + $consulta['minutos_disponibles'];
						$anio []= $consulta['anio'];
					}
					$anio = array_unique($anio);
					foreach (array_unique($anio) as $file){
						$item .= $file.' ';
					}
					//*********verificar saldo proporcional**************************************************************
					$consult = pg_fetch_assoc($cv->devolverFechasUltimoContratoTiempo($conexion, $usuario_seleccionado));
					$tiempoProporcional = round($cv->devolverTiempoProporcional($datos['fecha_salida'], $consult['tiempo']));
					$saldo = $saldo + $tiempoProporcional;
					
					//***************************************************************************************************
					$fila = $cv->guardarLiquidacionVacaciones($conexion, $usuario_seleccionado,$saldo, $item, $id_datos_contrato,$_SESSION['usuario'], $tiempoProporcional);
					$idLiquidacion = pg_fetch_assoc($fila);
					$idLiquidacion = $idLiquidacion['id_liquidacion_vacaciones'];
					
					//***************guardar historico de saldo liquidado*******************************
					
					$consulta = $cv->devolverSaldoFuncionarios($conexion, $usuario_seleccionado);

					while ($fila = pg_fetch_assoc($consulta)){
						if($fila['minutos_disponibles'] > 0){
							$cv->insertarDetalleLiquidacion($conexion, $idLiquidacion, $fila['anio'],'',$fila['minutos_disponibles']);
						}
					}
					$consulta = $cv->devolverSaldoFuncionariosXMes($conexion, $usuario_seleccionado);
					while ($fila = pg_fetch_assoc($consulta)){
						if($fila['minutos_disponibles'] > 0){
							$cv->insertarDetalleLiquidacion($conexion, $idLiquidacion, $fila['anio'],$fila['mes'],$fila['minutos_disponibles']);
						}
					}
					
					$fechaFinalContr = date('Y-m-d', strtotime($datos['fecha_salida']));
					$anio = date("Y", strtotime($fechaFinalContr));
					$mes = date("m", strtotime($fechaFinalContr));
					
					$nombreMes = $cv->devolverMes($mes);
					$cv->insertarDetalleLiquidacion($conexion, $idLiquidacion,$anio,$nombreMes,$tiempoProporcional);
					
					//***************Proceso para encerar el saldo**************************************
					$cv->encerarSaldoFuncionario($conexion, $usuario_seleccionado, $idLiquidacion);
					//**********************************************************************************
										
					$cc->inactivarMinutosDisponibles($conexion, $usuario_seleccionado, 'False');
					$cc->inactivarTiempoDisponibles($conexion, $usuario_seleccionado, 'False');
				}
				
				//**********************************************************************************************************
				
				
					$cc->actualizarDatosContrato($conexion, $id_datos_contrato, $usuario_seleccionado, $datos['tipoContrato'], $datos['numeroContrato'], $datos['fechaInicio'], $datos['fechaFin'],
							$datos['observacion'], $archivo, $datos['lugarContrato'], $datos['regimenLaboral'], $datos['numeroNotaria'], $datos['lugarNotaria'],
							$datos['fechaDeclaracion'], $partidaPresupuestaria, $datos['grupoOcupacional'], $datos['puestoInstitucional'],
							$presupuesto, $datos['remuneracion'], $datos['fuente'], $datos['grado'],$datos['provincia'], $datos['canton'], $datos['direccion'], $datos['coordinacion'], $datos['idGestion'], $datos['gestion'],
					    $datos['partidaIndividual'], $datos['oficina'], $datos['nombreOficina'], $datos['estado'],$datos['terminacion_laboral'],$datos['calificacion'],$datos['escala_calificacion'],$datos['fecha_salida'],
					    $datos['provinciaNotaria'],$datos['cantonNotaria'],$datos['rol'],$datos['informacion_puesto'],$datos['pluriempleo'],$datos['fecha_ingreso_sector_publico'],$datos['impedimento']);
					
					
					/////NUEVO ACTUALIZAR O INSERTAR FUNCIONARIO///

					$tipoUsuario = pg_fetch_result($cc->filtroObtenerDatosFuncionario($conexion, $usuario_seleccionado), 0, 'tipo_empleado');
					
					if($tipoUsuario == 'Interno'){
						
						if(pg_num_rows($cc->buscarFuncionario($conexion, $usuario_seleccionado))==0){
								
							$cc->guardarFuncionario($conexion, $datos['idGestion'], $usuario_seleccionado, 0, 1, $datos['idProvincia'], $datos['idCanton'], $datos['oficina'], 1);
								
						}else{
							if($datos['estado'] == 1)	
								
								$cc->actualizarFuncionario($conexion, $datos['idGestion'], $usuario_seleccionado,0, 1, $datos['idProvincia'], $datos['idCanton'], $datos['oficina'], 1);
								
							//echo ($datos['idGestion']. '</br>'.$usuario_seleccionado.'</br>'.'0'.'</br>'.'1'.'</br>'. $datos['idProvincia'].'</br>'. $datos['idCanton'].'</br>'. $datos['idOficina'].'</br>'.'1');
									
						}
						
					}
					
					///FIN NUEVO ACTUALIZAR O INSERTAR FUNCIONARIO///
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
								
				
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