<?php

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsolidaciones.php';

define('IN_MSG',' >>> ');
set_time_limit(1890);

$inicio = $_GET['inicio'];
$fin = $_GET['fin'];

//$conexionSaite = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'saite', $usuario = 'postgres', $clave = 'admin');
$conexionSaite = new Conexion($servidor = '192.168.200.5', $puerto = '5432', $baseDatos = 'saite', $usuario = 'postgres', $clave = 'P4r9rT2@rtosQ09');
//$conexionSinacoi = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'sinacoi', $usuario = 'postgres', $clave = 'admin');
$conexionSinacoi = new Conexion($servidor = '192.168.200.5', $puerto = '5432', $baseDatos = 'sinacoi', $usuario = 'postgres', $clave = 'P4r9rT2@rtosQ09');

$cc = new ControladorConsolidaciones();

$empresasSinacoi = $cc->obtenerEmpresasSinacoiSoloVacio($conexionSinacoi, $inicio, $fin);
$registrosEmpSinacoi =0;
$actualizacionSinacoi=0;
while ($empSinacoi = pg_fetch_assoc($empresasSinacoi)){


	echo '</br>'.IN_MSG.'---------------------------------------------------------------INICIO DE EMPRESA SINACOI--------------------------------------------------------------</br>';
	echo IN_MSG.$registrosEmpSinacoi++.'.- RUC DE LA EMPRSA SINACOI: '.$empSinacoi['emp_ruc'].' - '.$empSinacoi['emp_razon_social'].' TOTAL DIGITOS: '.$cantidadRegistro.' CON IDENTIFICADOR DE EMPRESA SINACOI: '.$empSinacoi['emp_id'].'</br>';

	$cadenaEliminar = array(" CIA. LTDA.", " CIA. LTDA", " CIA LTDA.", " CIA LTDA", " S. A.", "  S.A", " SA.", " CIA."," LTDA", " LTDA."," S.", " CIA", " A.", "A.", "EMPRESA", " EMPRESA", "LTDA", "UNIVERSIDAD", "ASOCIADOS", "SEGURIDAD", "HOSPITAL", "PANADERIA", "COMPANIA", "TRANSPORTES", "TIENDAS", "TIENDA", "COMERCIALES", "COMERCIAL", "SERVICIOS", "MEDICOS", "SR.", "(", ")", '"',"'", "-", ".");

	$nombreSinacoi = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empSinacoi['emp_nombre']))));
	$razonSinacoi = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empSinacoi['emp_razon_social']))));
	
	if(strlen($razonSinacoi) != 0 && strlen($razonSinacoi)>=3){
		$qEmpresaSaite = $cc->obtenerEmpresaSaiteRazonSocial($conexionSaite, $razonSinacoi);
	}

	if(pg_num_rows($qEmpresaSaite) == 0){
		if(strlen($nombreSinacoi) != 0 && strlen($nombreSinacoi) >=3){
			$qEmpresaSaite = $cc->obtenerEmpresaSaiteRazonSocial($conexionSaite, $nombreSinacoi);
		}
	}

	$bandera = 'SI';
	
	if(pg_num_rows($qEmpresaSaite) != 0){
		while ($empresaSaite = pg_fetch_assoc($qEmpresaSaite)){
		
			$nombreSaite = trim(str_replace($cadenaEliminar, "" ,strtoupper($cc->eliminar_tildes($empresaSaite['razon_social']))));
				
			$arrayNombreSaite = explode(" ", $nombreSaite);
			$arrayNombreSinacoi = explode(" ", $nombreSinacoi);
			$arrayRazonSinacoi = explode(" ", $razonSinacoi);
				
			sort($arrayNombreSinacoi);
			sort($arrayRazonSinacoi);
			sort($arrayNombreSaite);
				
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
		
			if($porcentaje2 >= 75){
				$actualizacionSinacoi++;
				$cc->actualizarEmpresasSinacoi($conexionSinacoi, $empresaSaite['identificacion'], $empresaSaite['razon_social'], $empSinacoi['emp_id'], $empSinacoi['emp_razon_social']);
				echo IN_MSG.'**************'.$actualizacionSinacoi.'.- ACTUALIZACIÓN DE REGISTRO CON RAZON SOCIAL---------------------</br>';
				$bandera = 'SI';
				break;
			}else if($porcentaje1 >= 75){
				$actualizacionSinacoi++;
				$cc->actualizarEmpresasSinacoi($conexionSinacoi, $empresaSaite['identificacion'], $empresaSaite['razon_social'], $empSinacoi['emp_id'], $empSinacoi['emp_razon_social']);
				$bandera = 'SI';
				echo IN_MSG.'**************'.$actualizacionSinacoi.'.- ACTUALIZACIÓN DE REGISTRO CON NOMBREL---------------------</br>';
				break;
			}else{
				$bandera = 'NO';
				echo IN_MSG.'------ NO SE ENCUENTRA NINGUNA RELACION</br>';
			}
		}

		if($bandera == 'NO'){
			$cc->actualizarEmpresasSinacoiProcesada($conexionSinacoi, $empSinacoi['emp_id'], 'NO');
			echo IN_MSG.'------ ACTUALIZACION DE REGISTRO A PROCESADO, NO SE ENCUENTRA NINGUNA RELACION</br>';
		}
		
	}else{
		$cc->actualizarEmpresasSinacoiProcesada($conexionSinacoi, $empSinacoi['emp_id'], 'NO');
		echo IN_MSG.'------ NO SE ENCUENTRA NINGUNA RELACION</br>';
	}
}

?>
