<?php

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsolidaciones.php';

define('IN_MSG',' >>> ');
set_time_limit(3600);

$inicio = $_GET['inicio'];
$fin = $_GET['fin'];

//$conexionSaite = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'saite', $usuario = 'postgres', $clave = 'admin');
$conexionSaite = new Conexion($servidor = '192.168.200.5', $puerto = '5432', $baseDatos = 'saite', $usuario = 'postgres', $clave = 'P4r9rT2@rtosQ09');
//$conexionSinacoi = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'sinacoi', $usuario = 'postgres', $clave = 'admin');
$conexionSinacoi = new Conexion($servidor = '192.168.200.5', $puerto = '5432', $baseDatos = 'sinacoi', $usuario = 'postgres', $clave = 'P4r9rT2@rtosQ09');

$cc = new ControladorConsolidaciones();

$empresasSinacoi = $cc->obtenerEmpresasSinacoi($conexionSinacoi, $inicio, $fin);
$registrosEmpSinacoi =1;
$actualizacionSinacoi=1;
while ($empSinacoi = pg_fetch_assoc($empresasSinacoi)){	
	$cantidadRegistro =strlen($empSinacoi['emp_ruc']);
	
	echo '</br>'.IN_MSG.'---------------------------------------------------------------INICIO DE EMPRESA SINACOI--------------------------------------------------------------</br>';
	echo IN_MSG.$registrosEmpSinacoi++.'.- RUC DE LA EMPRSA SINACOI: '.$empSinacoi['emp_ruc'].' TOTAL DIGITOS: '.$cantidadRegistro.' CON IDENTIFICADOR DE EMPRESA SINACOI: '.$empSinacoi['emp_id'].'</br>';
			
		$tramiteEmpresa = $cc->obtenerTramitesPersona($conexionSinacoi, $empSinacoi['emp_id']);																				 
		
		if(pg_num_rows($tramiteEmpresa) == 0){								
			$tramiteEmpresa = $cc->obtenerBoletasPrimeras($conexionSinacoi, $empSinacoi['emp_id']);
		}
		
		if(pg_num_rows($tramiteEmpresa) != 0){
			$contadorTramite = 1;
			while ($traEmpresa = pg_fetch_assoc($tramiteEmpresa)){
				
				echo IN_MSG.'CONTADOR TRAMITE: '.$contadorTramite++.'</br>';
				
				$empleadoSinacoi = pg_fetch_assoc($cc->obtenerEmpeladoSinacoi($conexionSinacoi, $traEmpresa['empl_id']));
				
				echo IN_MSG.'--- EMPLEADO SINACOI: '.$empleadoSinacoi['empl_numero_documento'].'</br>';
				
				$empleadoSaite = $cc->obtenerEmpeladoSaite($conexionSaite, $empleadoSinacoi['empl_numero_documento']);
				
				echo IN_MSG.'--- BUSQUEDA EMPLEADO SAITE</br>';
				
				if(pg_num_rows($empleadoSaite) != 0){
					echo IN_MSG.'--- BUSQUEDA DEL CONTRATO DEL EMPLEADO</br>';
					$contratosSaite = $cc->obtenerContratoSaite($conexionSaite, pg_fetch_result($empleadoSaite, 0, 'c_empleado_id'));
					$cantidadSaite= 1;
				}else{
					$cantidadSaite = 0;
				}				
								
				if ($cantidadSaite==1 && pg_num_rows($contratosSaite) != 0){
					$contadorContratos = 1;
					
					if(pg_num_rows($contratosSaite)!=1){
						while ($conSaite = pg_fetch_assoc($contratosSaite)){
							echo IN_MSG.'CONTADOR CONTRATOS: '.$contadorContratos++.'</br>';
							echo IN_MSG.'----- BUSQUEDA DE LA EMPRESA EN SAITE</br>';
						
							$empresaSaite = pg_fetch_assoc($cc->obtenerEmpresaSaite($conexionSaite, $conSaite['c_institucion_id']));
						
							$cadenaEliminar = array(" CIA. LTDA.", " CIA. LTDA", " CIA LTDA.", " CIA LTDA", " S. A.", "  S.A", " SA.", " CIA."," LTDA", " LTDA."," S.", " CIA", " A.", "A.", "EMPRESA", " EMPRESA", "LTDA", "UNIVERSIDAD", "ASOCIADOS", "SEGURIDAD", "HOSPITAL", "PANADERIA", "COMPANIA", "TRANSPORTES", "TIENDAS", "TIENDA", "COMERCIALES", "COMERCIAL", "SERVICIOS", "MEDICOS", "SR.", "(", ")", '"',"'", "-", ".");
						
							$nombreSaite = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empresaSaite['razon_social']))));
							$nombreSinacoi = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empSinacoi['emp_nombre']))));
							$razonSinacoi = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empSinacoi['emp_razon_social']))));
						
							$arrayNombreSaite = explode(" ", $nombreSaite);
							$arrayNombreSinacoi = explode(" ", $nombreSinacoi);
							$arrayRazonSinacoi = explode(" ", $razonSinacoi);
						
							sort($arrayNombreSaite);
							sort($arrayNombreSinacoi);
							sort($arrayRazonSinacoi);
						
							$nombreSaite = implode(" ", $arrayNombreSaite);
							$nombreSinacoi = implode(" ", $arrayNombreSinacoi);
							$razonSinacoi = implode(" ", $arrayRazonSinacoi);
						
							echo '</br>'.IN_MSG.'------ Razon social saite: '.$nombreSaite.'</br>';
							echo IN_MSG.'------ Nombre sinacoi: '.$nombreSinacoi.'</br>';
							echo IN_MSG.'------ Razon social Sinacoi: '.$razonSinacoi.'</br></br>';
								
							echo IN_MSG.'------ COMPRACIÓN PORCENTAJE</br>';
							similar_text($nombreSaite, $nombreSinacoi, $porcentaje1);
							echo IN_MSG.'--------- Comparación Nombre: '.$porcentaje1.'</br>';
							similar_text($nombreSaite, $razonSinacoi, $porcentaje2);
							echo IN_MSG.'--------- Comparación Razón social: '.$porcentaje2.'</br>';
						
							if($porcentaje2 >= 60){
								$actualizacionSinacoi++;
								$cc->actualizarEmpresasSinacoi($conexionSinacoi, $empresaSaite['identificacion'], $empresaSaite['razon_social'], $empSinacoi['emp_id'], $empSinacoi['emp_razon_social']);
								echo IN_MSG.'*******************'.$actualizacionSinacoi.'.- ACTUALIZACIÓN DE REGISTRO CON RAZON SOCIAL---------------------</br>';
								$bandera = 'SI';
								break;
							}else if($porcentaje1 >= 60){
								$actualizacionSinacoi++;
								$cc->actualizarEmpresasSinacoi($conexionSinacoi, $empresaSaite['identificacion'], $empresaSaite['razon_social'], $empSinacoi['emp_id'], $empSinacoi['emp_razon_social']);
								echo IN_MSG.'*******************'.$actualizacionSinacoi.'.- ACTUALIZACIÓN DE REGISTRO CON NOMBREL---------------------</br>';
								$bandera = 'SI';
								break;
							}else{
								$bandera = 'NO';
								echo IN_MSG.'------ NO SE ENCUENTRA NINGUNA RELACION</br>';
							}
						}	
					}else{
						$bandera = 'SI';
						echo IN_MSG.'*******************'.$actualizacionSinacoi.'.- ACTUALIZACIÓN DE REGISTRO POR TENER UN CONTRATO---------------------</br>';
						$conSaite = pg_fetch_assoc($contratosSaite);
						$empresaSaite = pg_fetch_assoc($cc->obtenerEmpresaSaite($conexionSaite, $conSaite['c_institucion_id']));
						$cc->actualizarEmpresasSinacoi($conexionSinacoi, $empresaSaite['identificacion'], $empresaSaite['razon_social'], $empSinacoi['emp_id'], $empSinacoi['emp_razon_social']);
						break;
					}
					
					if($bandera == 'NO'){
						$cc->actualizarEmpresasSinacoiProcesada($conexionSinacoi, $empSinacoi['emp_id'], 'NONO');
						echo IN_MSG.'------ ACTUALIZACION DE REGISTRO A PROCESADO, NO SE ENCUENTRA NINGUNA RELACION</br>';
					}
					
				}else{
					$bandera = 'NO';
					echo IN_MSG.'--- NO EXISTE EL EMPLEADO EN SAITE CON CÉDULA: '.$empleadoSinacoi['empl_numero_documento'].' EN LA EMPRESA: '.$empSinacoi['emp_nombre']. ' Y RAZÓN SOCIAL: '.$empSinacoi['emp_razon_social'].'</br>';
					$cc->actualizarEmpresasSinacoiProcesada($conexionSinacoi, $empSinacoi['emp_id'], 'NONO');
				}								
			}
		}else{
			$bandera = 'NO';
			echo IN_MSG.'NO EXISTE NI EN TRAMITES NI EN BOLETAS: '.$empSinacoi['emp_id'].'</br>';
			$cc->actualizarEmpresasSinacoiProcesada($conexionSinacoi, $empSinacoi['emp_id'], 'NONO');
		}	
}


?>
