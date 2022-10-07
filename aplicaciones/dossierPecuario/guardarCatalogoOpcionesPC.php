<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';


$ce = new ControladorEnsayoEficacia();
$cp = new ControladorDossierPecuario();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{

	$paso_catalogo=$_POST['paso_catalogo'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_catalogo){



			case "guardarStandar":
				$clase=htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
				$tabla=htmlspecialchars ($_POST['tabla'],ENT_NOQUOTES,'UTF-8');
				$nombre=htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');

				$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
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
					if($info['data_type']=='integer')
						$dato[$info['column_name']]=intval( trim(htmlspecialchars ($_POST[$info['column_name']],ENT_NOQUOTES,'UTF-8')));
					else
						$dato[$info['column_name']]=trim(htmlspecialchars ($_POST[$info['column_name']],ENT_NOQUOTES,'UTF-8'));
				}
				$resultado=$ce->guardarTablaStandar($conexion,$tablaNombre[0],$clase,$dato);
				if($resultado['tipo']=='insert'){
					$dato[$clase]=$resultado['resultado'][0][$clase];
					$nombreLista='';
					switch($clase){
						case 'id_clasificacion_subtipo':
							$nombreLista=$_POST['nombre_subtipo'] .' : '.$dato['nombre'];
							break;
						case 'id_especie_consumible':
							$nombreLista=$_POST['nombre_especie'].' : '.$_POST['nombre_consumible'];
							break;
						case 'id_fabricante_extranjero':
							$nombreLista=$_POST['identificador'].' : '.$_POST['nombre'].' - '.$_POST['pais'];
							break;
						case 'id_ingrediente_activo_grupo':
							$nombreLista=$_POST['nombre_grupo'].' : '.$_POST['ingrediente_activo'];
							break;
						case 'id_subtipo_producto_grupo':
							$nombreLista=$_POST['nombre_subtipo'].' : '.$_POST['nombre_grupo'];
							break;

					}

					$mensaje['mensaje'] = $cp->imprimirLineaCatalogoPecuario($nombreLista,$tipo,$tabla,$clase,$dato);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';

				break;


			case "borrarClasificacion":
				$tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');

				$id_clasificacion_subtipo=trim(htmlspecialchars ($_POST['id_clasificacion_subtipo'],ENT_NOQUOTES,'UTF-8'));
				$ce->eliminarTablaStandar($conexion,'g_dossier_pecuario.clasificacion_subtipos','id_clasificacion_subtipo',$id_clasificacion_subtipo);

				$mensaje['mensaje'] = $id_clasificacion_subtipo;
				break;
			case "borrarStandar":	//Elimina items de las tablas
				$clase=htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');
			   $tipo=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
			   $tabla=htmlspecialchars ($_POST['tabla'],ENT_NOQUOTES,'UTF-8');

			   $claveValor=htmlspecialchars ($_POST[$clase],ENT_NOQUOTES,'UTF-8');
			   $tablaNombres=explode(',',$tabla);
			   $tablaNombre=array_slice($tablaNombres,0,1);

			   $ce->eliminarTablaStandar($conexion,$tablaNombre[0],$clase,$claveValor);
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

