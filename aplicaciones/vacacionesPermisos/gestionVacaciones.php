<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatastro.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//$TRES_DIAS = 259200;
$TRES_DIAS = 345600;

try{
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$ca = new ControladorAreas(); 
	$cd = new ControladorCatastro();
		
	$identificadorUsuario = $_POST['identificador'];
	$opcion=$_POST['opcion'];
	$identificador=$_POST['identificador'];
	$rutaArchivo=$_POST['archivo'];
	$subTipoSolicitud=$_POST['subTipoSolicitud'];
	
	//Cambio de formato en tiempo
	$horaSalida= substr($_POST['horaSalida'], 0, 2);
	$minutosSalida= substr($_POST['horaSalida'], 3, 2);
	
	$horaRetorno= substr($_POST['horaRetorno'], 0, 2);
	$minutosRetorno= substr($_POST['horaRetorno'], 3, 2);
	
	//Área de usuario para revisión y aprobación de jefe inmediato	
	$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificadorUsuario));
	
	$subtipoPermiso = pg_fetch_assoc($cv->obtenerSubTipoPermiso($conexion,null,$_POST['subTipoSolicitud']));
	$minutos=$subtipoPermiso['minutos_permitidos'];	
	
	if($_POST['fechaSuceso']!="")
		$fechaSuceso= $_POST['fechaSuceso'];
	else{
		$fechaSuceso= $_POST['fechaSalida'];
	}	

	$fechaSalida = new DateTime($_POST['fechaSalida']);
	date_time_set($fechaSalida,$horaSalida,$minutosSalida);

	$fechaRetorno = new DateTime($_POST['fechaRetorno']);
	date_time_set($fechaRetorno,$horaRetorno,$minutosRetorno);

	$fechaSalida=date_format($fechaSalida, 'Y-m-d H:i:s');
	$fechaRetorno=date_format($fechaRetorno, 'Y-m-d H:i:s');

	//-----------------------------------------------------------------------
	$diaSemana = date('l', strtotime($_POST['fechaRetorno']));
	switch ($diaSemana){
		case 'Monday': $TRES_DIAS = 345600; break;
		case 'Tuesday': $TRES_DIAS = 345600;  break;
		case 'Wednesday':$TRES_DIAS = 115200 * 4; break;
		case 'Thursday': $TRES_DIAS = 115200 * 4; break;
		case 'Friday': $TRES_DIAS = 115200 * 4; break;
		default: $TRES_DIAS = 345600;
	}
//----------------------------------------------------------------------------
	//----------------------------------------------------------------------
	$minutos_utilizados=$_POST['minutosDisponibles'];
	//----------------------------------------------------------------------
			
	//Fecha para presentación de documento de respaldo
	if($subtipoPermiso['presentacion_despues_reintegro']==true){
		$fecha_maxima_justificar = date('Y-m-d H:i:s', strtotime($fechaRetorno) + $TRES_DIAS);
	}else{
		$fecha_maxima_justificar = date('Y-m-d', strtotime($fechaSuceso) + $TRES_DIAS);
	}
	
//--------------------------------------------------------------------------------------------------------------------		
	if($_POST['lugarComisionLocal']!="")
		$destinoComision= $_POST['lugarComisionLocal'];
	else if($_POST['lugarComisionProvincial']!=""){
		$destinoComision= $_POST['lugarComisionProvincial'];
	}else {
			$destinoComision='';
	}	
	if($_POST['opcionTipoPermiso'] == 'PE-CE'){
	    if($_POST['lugarComisionExterior']!=""){
			$destinoComision = $_POST['lugarComisionExterior'];
	    }
	}
//--------------------------------------------------------------------------------------------------------------------
	$resultadoConsulta=$cv->devolverJefeImnediato($conexion, $identificador);	
//--------------------------------------------------------------------------------------------------------------------
	$idAreaPermiso=$resultadoConsulta['idarea'];
	$idAreaJefe=$resultadoConsulta['idareajefe'];
	$identificadorJefe=$resultadoConsulta['identificador'];
	$idareafuncionario=$resultadoConsulta['idareafuncionario'];
	
	try {	
		
		if($minutos_utilizados > 0 and $minutos_utilizados <= $minutos){
			        $conexion->ejecutarConsulta("begin;");
					if($_POST['opcion']=="Nuevo"){
						
						//buscar permisos creado con el mismo rango de fechas
						$permisosActuales = $cv->buscarPermisosRangoFecha($conexion, $fechaSalida, $fechaRetorno, $identificadorUsuario);
				//---------------verificar contrato activo-----------------------------------------------------------			
					if(pg_num_rows($cd->obtenerInformacionFuncionarioContratoActivo($conexion, $identificador)) != 0){	
				//---------------------------------------------------------------------------------------------------		
						//---------------verificar area del permiso-----------------------------------------------------------
					   if($idAreaPermiso != '' and $identificadorJefe != '' and $idAreaJefe != ''){
							//---------------------------------------------------------------------------------------------------
						  if(pg_num_rows($permisosActuales) == 0){
							
						
							$fila=$cv->nuevoPermiso($conexion,$subTipoSolicitud,$fechaSalida,$fechaRetorno,$horaSalida,$horaRetorno,$identificador,
									$minutos_utilizados,$fecha_maxima_justificar,$rutaArchivo,'',$fechaSuceso, $idAreaPermiso, $destinoComision, $_POST['opcionPermiso']);
							$permiso = pg_fetch_assoc($fila);
							
							$id_permiso = $permiso['id_permiso_empleado'];
							
							//---------------------Registrar encargo de puesto -------------------------------------------------------------
							if($minutos_utilizados >= 480 and $_POST['responsableEncargado'] != '' and $_POST['opcionTipoPermiso'] != 'PE-CL' and $_POST['opcionTipoPermiso'] != 'PE-CP'){
								$puesto = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $identificadorUsuario), 0, 'nombre_puesto');
								$puesto_encargado = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['responsableEncargado']), 0, 'nombre_puesto');
								$id_area_encargado = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['responsableEncargado']), 0, 'id_gestion');
								
							if(pg_num_rows($cd->verificarResponsable($conexion,$identificadorUsuario, $idareafuncionario))){
									$prioridad = pg_fetch_result($cd->verificarResponsable($conexion,$identificadorUsuario, $idareafuncionario), 0, 'prioridad');
									if($prioridad==1 or $prioridad==3)$designacion='Titular';
									else $designacion='Subrogante';
								}								
								if(pg_num_rows($cd->verificarResponsablePuesto($conexion,$identificadorUsuario, $idareafuncionario))){
									$designacion='Encargado';								
								}
									
								$cv->nuevoEncargado($conexion,$identificador,$_POST['responsableEncargado'],$idareafuncionario,$id_area_encargado,$puesto,$puesto_encargado,$fechaSalida,$fechaRetorno,$id_permiso,$designacion,$_POST['archivoSub']);								
							}
							
							//---asignar jefe revisor del permiso----------------------------------------------------				
							$cv->identificadorJefeSuperior($conexion, $identificadorJefe, $id_permiso, $idAreaJefe);
							//---------------------------------------------------------------------------------------
							//Registro de observaciones del proceso
							$cv->agregarObservacion($conexion, 'El usuario '.$identificador.' ha creado la solicitud de '.$subtipoPermiso['descripcion_subtipo'].' con fecha de salida '
									.$fechaSalida.', fecha de retorno '.$fechaRetorno.' y con '.$minutos_utilizados.' minutos solicitados', $id_permiso, $identificador);
							$mensaje['estado'] = 'exito';
							$mensaje['mensaje'] = 'Los datos han sido registrados satisfactoriamente';
							$conexion->ejecutarConsulta("commit;");							
						 }else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = "Ya posee un permiso en el rango de fechas seleccionado...!!";
						 }
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = "Debe solicitar la actualización de su catastro en talento humano...!!";
						}
					  }else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = "No dispone de un contrato activo...!!";
						}
					}
//----------------------------------------------------------------------------------------------------------------------------------------------					
					if($_POST['opcion']=="Actualizar"){
												
						//buscar permisos creado con el mismo rango de fechas
						$permisosActuales = $cv->buscarPermisosRangoFecha($conexion, $fechaSalida, $fechaRetorno, $identificadorUsuario, $_POST['id_registro']);

					//---------------verificar contrato activo-----------------------------------------------------------			
					if(pg_num_rows($cd->obtenerInformacionFuncionarioContratoActivo($conexion, $identificador)) != 0){	
				    //---------------------------------------------------------------------------------------------------	
						//---------------verificar area del permiso-----------------------------------------------------------
						if($idAreaPermiso != '' and $identificadorJefe != '' and $idAreaJefe != ''){
							//---------------------------------------------------------------------------------------------------
						  if(pg_num_rows($permisosActuales) == 0){
							
							$id_registro=$_POST['id_registro'];
							
							//------------------verificar archivo adjunto-----------------------------------------------------------------------
							if($rutaArchivo == 0 ){
								$rutaArchivo = pg_fetch_result($cv->obtenerPermisoSolicitado($conexion, $_POST['id_registro']), 0, 'ruta_archivo');
							}
						    //--------------------------------------------------------------------------------------------------------------
						    
							if($minutos_utilizados >= 480 and $_POST['responsableEncargado'] != '' and $_POST['opcionTipoPermiso'] != 'PE-CL' and $_POST['opcionTipoPermiso'] != 'PE-CP'){
								$puesto = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $identificadorUsuario), 0, 'nombre_puesto');
								$puesto_encargado = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['responsableEncargado']), 0, 'nombre_puesto');
							    $id_area_encargado = pg_fetch_result($cd->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['responsableEncargado']), 0, 'id_gestion');
								
							    
							  if(pg_num_rows($cd->verificarResponsable($conexion,$identificadorUsuario, $idareafuncionario))){
									$prioridad = pg_fetch_result($cd->verificarResponsable($conexion,$identificadorUsuario, $idareafuncionario), 0, 'prioridad');
									if($prioridad==1 or $prioridad==3)$designacion='Titular';
									else $designacion='Subrogante';
								}								
								if(pg_num_rows($cd->verificarResponsablePuesto($conexion,$identificadorUsuario, $idareafuncionario))){
									$designacion='Encargado';								
								}						    							 							    
								//---------------------Actualizar encargo de puesto -------------------------------------------------------------
								$cv->actualizarEncargado($conexion,$id_registro,$identificadorUsuario,$_POST['responsableEncargado'],$idareafuncionario,$id_area_encargado,$puesto,$puesto_encargado,$fechaSalida,$fechaRetorno,$designacion,$_POST['archivoSub']);
							}
							//-------------------------------------------------------------------------------------------------------------------														
							$cv->actualizarPermiso($conexion,$id_registro,$subTipoSolicitud,$fechaSalida,$fechaRetorno,$identificador,
									$minutos_utilizados,$fecha_maxima_justificar,$rutaArchivo,$fechaSuceso, $idAreaPermiso, $destinoComision,$_POST['opcionPermiso']);
							//---asignar jefe revisor del permiso----------------------------------------------------
							$cv->identificadorJefeSuperior($conexion, $identificadorJefe, $id_registro, $idAreaJefe);
							//---------------------------------------------------------------------------------------
							//Registro de observaciones del proceso
							$cv->agregarObservacion($conexion, 'El usuario '.$identificador.' ha actualizado la solicitud de '.$subtipoPermiso['descripcion_subtipo'].' con fecha de salida '
									.$fechaSalida.', fecha de retorno '.$fechaRetorno.' y con '.$minutos_utilizados.' minutos solicitados',
									$id_registro, $identificador);
							
							$mensaje['estado'] = 'exito';
							$mensaje['mensaje'] = 'Los datos han sido registrados satisfactoriamente';
							$conexion->ejecutarConsulta("commit;");
							
							}else{
								$mensaje['estado'] = 'error';
								$mensaje['mensaje'] = "Ya posee un permiso aprobado en el rango de fechas seleccionado.";
							}
						   }else{
								$mensaje['estado'] = 'error';
								$mensaje['mensaje'] = "Debe solicitar la actualización de su catastro en talento humano...!!";
							}
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = "No dispone de un contrato activo...!!";
						}
					}			
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El tiempo solicitado no puede ser cero, por favor seleccione correctamente la fecha y hora de finalización del permiso.";
		}
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
		$conexion->ejecutarLogsTryCatch($ex.'--'.$err);
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
		} finally {
			$conexion->desconectar();
	}
} catch (Exception $ex) {
	$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	$conexion->ejecutarLogsTryCatch($ex.'--'.$err);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>