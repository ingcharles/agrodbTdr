<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{

	$paso_catalogo=$_POST['paso_catalogo'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_catalogo){

			case "C1":
				$clase=htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
				$codigo=trim(htmlspecialchars ($_POST['codigoCatalogo'],ENT_NOQUOTES,'UTF-8'));
				$nombre=trim(htmlspecialchars ($_POST['nombreCatalogo'],ENT_NOQUOTES,'UTF-8'));
				$resultado=array();
				if($_POST['tipo']=='extendido'){
					$nombre2=trim(htmlspecialchars ($_POST['nombre2'],ENT_NOQUOTES,'UTF-8'));
					$nombre3=trim(htmlspecialchars ($_POST['nombre3'],ENT_NOQUOTES,'UTF-8'));
					$resultado=$ce->guardarItemDelCatalogoEx($conexion,$clase,$codigo,$nombre,$nombre2,$nombre3);

				}
				else{
					$resultado=$ce->guardarItemDelCatalogo($conexion,$clase,$codigo,$nombre);
				}
				if($resultado['tipo']=='insert'){
					$dato=array('codigo'=>$codigo,'nombre'=>$nombre);
					$mensaje['mensaje'] = $ce->imprimirLineaCatalogo($nombre,$tipo,$clase,$dato);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';
				break;

			case "S1":
				$clase=htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
				$tabla=htmlspecialchars ($_POST['tabla'],ENT_NOQUOTES,'UTF-8');
				$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
				$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
				$elementos=htmlspecialchars ($_POST['elementos'],ENT_NOQUOTES,'UTF-8');
				$tablaNombres=explode(',',$tabla);
				$tablaNombre=array_slice($tablaNombres,0,1);
				$camposFijos=array_slice($tablaNombres,1);
				$tablaEsquema=explode('.',$tablaNombre[0]);

				$esquema=$ce->obtenerEsquema($conexion,$tablaEsquema[0],$tablaEsquema[1]);
				$dato=array();
				if(key_exists($clase, $_POST)){
					$dato[$clase]=$_POST[$clase];
				}
				foreach($esquema as $info){
					if($info['column_name']==$clase)
						continue;
					$dato[$info['column_name']]=trim(htmlspecialchars ($_POST[$info['column_name']],ENT_NOQUOTES,'UTF-8'));
				}
				$resultado=$ce->guardarTablaStandar($conexion,$tablaNombre[0],$clase,$dato);
				if($resultado['tipo']=='insert'){
					$dato[$clase]=$resultado['resultado'][0][$clase];
					$mensaje['mensaje'] = $ce->imprimirLineaTablaStandar($nombre,$tipo,$tabla,$camposFijos,$clase,$dato,$elementos);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';

				break;


			case "E1":	//Elimina items de las tablas catalogo_ef_
				$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
				$codigo=trim(htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8'));
				if($tipo=='extendido')
					$ce->eliminarItemDelCatalogoEx($conexion,$codigo);
				else
					$ce->eliminarItemDelCatalogo($conexion,$codigo);
				$mensaje['mensaje'] = $codigo;
				break;
			case "D1":	//Elimina items de las tablas catalogo_ef_
				$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
				$tabla=htmlspecialchars ($_POST['tabla'],ENT_NOQUOTES,'UTF-8');
				$clave=htmlspecialchars ($_POST['clave'],ENT_NOQUOTES,'UTF-8');
				$claveValor=htmlspecialchars ($_POST['claveValor'],ENT_NOQUOTES,'UTF-8');
				$tablaNombres=explode(',',$tabla);
				$tablaNombre=array_slice($tablaNombres,0,1);

				$ce->eliminarTablaStandar($conexion,$tablaNombre[0],$clave,$claveValor);
				$mensaje['mensaje'] = $claveValor;
				break;
		}
		if($esGuardar){
			$mensaje['estado'] = 'exito';
			
		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

