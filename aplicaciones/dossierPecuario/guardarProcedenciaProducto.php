<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{

	$conexion = new Conexion();
	$cp=new ControladorDossierPecuario();
	$id_solicitud = $_POST['id_solicitud'];

	$es_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
	$ruc = htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');
	$id_sitio = htmlspecialchars ($_POST['id_sitio'],ENT_NOQUOTES,'UTF-8');
	$id_area = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
	$empresa = htmlspecialchars ($_POST['empresa'],ENT_NOQUOTES,'UTF-8');
	$direccion = trim(htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'));
	$id_pais = htmlspecialchars ($_POST['id_pais'],ENT_NOQUOTES,'UTF-8');
	$registro_oficial = htmlspecialchars ($_POST['registro_oficial'],ENT_NOQUOTES,'UTF-8');
	$tecnico_contrato = htmlspecialchars ($_POST['tecnico_contrato'],ENT_NOQUOTES,'UTF-8');
	$tecnico_titulo = htmlspecialchars ($_POST['tecnico_titulo'],ENT_NOQUOTES,'UTF-8');
	$tecnico_registro = htmlspecialchars ($_POST['registroSenesyt'],ENT_NOQUOTES,'UTF-8');
	$tecnico_nombre = htmlspecialchars ($_POST['tecnico_nombre'],ENT_NOQUOTES,'UTF-8');
	
	//CampoSI/NO
	$esFabricanteSN = htmlspecialchars ($_POST['es_fabricante'],ENT_NOQUOTES,'UTF-8');
	$esContratoSN = htmlspecialchars ($_POST['es_por_contrato'],ENT_NOQUOTES,'UTF-8');
	
	if($esFabricanteSN == 'SI'){
	    $esFabricanteSN = 'N';
	}else{
	    if($esContratoSN == 'SI'){
	        $esFabricanteSN = 'C';
	    }else{
	        $esFabricanteSN = 'E';
	    }
	}

	try{
		$cp->agregarFabricanteDossier($conexion,$id_solicitud,$ruc,$id_sitio,$id_area,$es_fabricante,$tecnico_contrato,$direccion,$empresa,$id_pais,$tecnico_titulo,$tecnico_registro,$registro_oficial,$tecnico_nombre);
		
		//Guardar radios es fabricante
		$cp->actualizarFabricanteSolicitud($conexion, $id_solicitud, $esFabricanteSN);
		
		
		$datos=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);
		$tieneExtranjero=0;
		foreach($datos as $items){
			if($items['tipo']=='E'){
				$tieneExtranjero=1;
				break;
			}
		}
		$mensaje['tieneExtranjero']=$tieneExtranjero;
		$mensaje['datos'] =$cp->imprimirFabricantesDosier($datos);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Fabricante a sido agregado";
		$conexion->desconectar();
		echo json_encode($mensaje);
	}
	catch(Exception $e){
		$conexion->desconectar();
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al guardar procedencia del producto";
		echo json_encode($mensaje);
	}

}
catch (Exception $ex) {
	pg_close($conexion);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}


?>



