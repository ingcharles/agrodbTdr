<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorVacaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorRegistro = $_POST['identificadorRegistro'];

try {
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$cai = new ControladorAccidentesIndicentes();

	$conexion->ejecutarConsulta("begin;");
	$datos = array(
			'identificadorUsuario' =>  htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8'),
			'edad' => htmlspecialchars ($_POST['edad'],ENT_NOQUOTES,'UTF-8'),
			'opcion' => htmlspecialchars ( $_POST['opcion'],ENT_NOQUOTES,'UTF-8'),
			'escolaridad' => htmlspecialchars ( $_POST['escolaridad'],ENT_NOQUOTES,'UTF-8'),
			'profesion' => htmlspecialchars ( $_POST['profesion'],ENT_NOQUOTES,'UTF-8'),
			'horarioTrab' => htmlspecialchars ($_POST['horarioTrab'],ENT_NOQUOTES,'UTF-8'),
			'tiempoPuesto' =>  htmlspecialchars ($_POST['tiempoPuesto'],ENT_NOQUOTES,'UTF-8'),
			'diaSemana' => htmlspecialchars ($_POST['diaSemana'],ENT_NOQUOTES,'UTF-8'),
			'fechaSuceso' => htmlspecialchars ($_POST['fechaSuceso'],ENT_NOQUOTES,'UTF-8'),
			'horaAccidente' => htmlspecialchars ($_POST['horaAccidente'],ENT_NOQUOTES,'UTF-8'),
			'tipoAccidente' => htmlspecialchars ($_POST['tipoAccidente'],ENT_NOQUOTES,'UTF-8'),
			'lugarAccidente' =>  htmlspecialchars ($_POST['lugarAccidente'],ENT_NOQUOTES,'UTF-8'),
			'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
			'referenciaAccidente' => htmlspecialchars ($_POST['referenciaAccidente'],ENT_NOQUOTES,'UTF-8'),
			'provinciaAccidente' => htmlspecialchars ($_POST['provinciaAccidente'],ENT_NOQUOTES,'UTF-8'),
			'cantonAccidente' => htmlspecialchars ($_POST['cantonAccidente'],ENT_NOQUOTES,'UTF-8'),
			'parroquiaAccidente' => htmlspecialchars ($_POST['parroquiaAccidente'],ENT_NOQUOTES,'UTF-8'),
			/////
			'describirAccidente' => htmlspecialchars ($_POST['describirAccidente'],ENT_NOQUOTES,'UTF-8'),
			'trabajoHabitual' => htmlspecialchars ($_POST['trabajoHabitual'],ENT_NOQUOTES,'UTF-8'),
			'accidenteTrabajo' => htmlspecialchars ($_POST['accidenteTrabajo'],ENT_NOQUOTES,'UTF-8'),
			'partesLesionadas' => htmlspecialchars ($_POST['partesLesionadas'],ENT_NOQUOTES,'UTF-8'),
			'personaAtendio' => htmlspecialchars ($_POST['personaAtendio'],ENT_NOQUOTES,'UTF-8'),
			'trasladoAccidente' => htmlspecialchars ($_POST['trasladoAccidente'],ENT_NOQUOTES,'UTF-8'),

			'nombreTestigo' => htmlspecialchars ($_POST['nombreTestigo'],ENT_NOQUOTES,'UTF-8'),
			'direccionTestigo' => htmlspecialchars ($_POST['direccionTestigo'],ENT_NOQUOTES,'UTF-8'),
			'telefonoTestigo' => htmlspecialchars ($_POST['telefonoTestigo'],ENT_NOQUOTES,'UTF-8'));
	
	$datosFicha = array(
			'lugarAtencion' => htmlspecialchars ($_POST['lugarAtencion'],ENT_NOQUOTES,'UTF-8'),
			'fechaAtencion' => htmlspecialchars ($_POST['fechaAtencion'],ENT_NOQUOTES,'UTF-8'),
			'horaAtencion' => htmlspecialchars ($_POST['horaAtencion'],ENT_NOQUOTES,'UTF-8'),
			'presentaSintomas' => htmlspecialchars ($_POST['presentaSintomas'],ENT_NOQUOTES,'UTF-8'),
			'otrosDatos' => htmlspecialchars ($_POST['otrosDatos'],ENT_NOQUOTES,'UTF-8'),
			'descripcionLesiones' => htmlspecialchars ($_POST['descripcionLesiones'],ENT_NOQUOTES,'UTF-8'),
			'trasladoCentroSalud' => htmlspecialchars ($_POST['trasladoCentroSalud'],ENT_NOQUOTES,'UTF-8'),
			'nombreMedico' => htmlspecialchars ($_POST['nombreMedico'],ENT_NOQUOTES,'UTF-8'),
			'fechaReposoDesde' => htmlspecialchars ($_POST['fechaReposoDesde'],ENT_NOQUOTES,'UTF-8'),
			'fechaReposoHasta' => htmlspecialchars ($_POST['fechaReposoHasta'],ENT_NOQUOTES,'UTF-8'));

	$id_area_padre=$cv->devolverJefeImnediato($conexion, $identificadorRegistro);
		
	$cai->actualizarDatosFichaEmpleado($conexion, $datos['identificadorUsuario'], $datos['edad']);
	$consulta=$cai->guardarNuevoRegistroSso($conexion, $datos['identificadorUsuario'],$datos['escolaridad'],$datos['profesion'],$datos['horarioTrab'],
			$datos['tiempoPuesto'],$id_area_padre['idarea'],$datos['opcion'],$identificadorRegistro);
	$ingreso = pg_fetch_assoc($consulta);

	$cod_registro = $ingreso['cod_datos_accidente'];
	$cai->guardarInformeAccidente($conexion, $cod_registro,$datos['diaSemana'],$datos['fechaSuceso'],$datos['horaAccidente'],$datos['tipoAccidente'],
			$datos['lugarAccidente'],$datos['direccion'],$datos['referenciaAccidente'],$datos['provinciaAccidente'],$datos['cantonAccidente'],$datos['parroquiaAccidente']);

	$cai->guardarCircunstanciasAccidentes($conexion, $cod_registro,$datos['describirAccidente'],$datos['trabajoHabitual'],$datos['accidenteTrabajo'],$datos['partesLesionadas'],
			$datos['personaAtendio'],$datos['trasladoAccidente'],$datos['nombreTestigo'],$datos['direccionTestigo'],$datos['telefonoTestigo']);

	/*$ban=0;
	foreach ($datosFicha as $vericarVacios){
		if (trim($vericarVacios)!="")
			$ban=1;
	}if($ban)*/
	$cai->guardarFichaMedica($conexion, $cod_registro,$datosFicha['lugarAtencion'],$datosFicha['fechaAtencion'],$datosFicha['horaAtencion'],$datosFicha['presentaSintomas'],
			$datosFicha['otrosDatos'],$datosFicha['descripcionLesiones'],$datosFicha['trasladoCentroSalud'],$datosFicha['nombreMedico'],$datosFicha['fechaReposoDesde'],$datosFicha['fechaReposoHasta']);

	$conexion->ejecutarConsulta("commit;");
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
	echo json_encode($mensaje);

} catch (Exception $e) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia".$e;
	echo json_encode($mensaje);
} finally {
	$conexion->desconectar();
}

?>