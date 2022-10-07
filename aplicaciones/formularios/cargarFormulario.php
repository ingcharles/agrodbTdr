<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idFormulario = htmlspecialchars ($_POST['id_formulario'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		//**********************TRAER LOS DATOS
		$formulario = pg_fetch_assoc($cf->abrirFormulario($conexion, $idFormulario));
		$categorias = $cf->listarCategorias($conexion, $idFormulario);
		$preguntas = $cf->listarPreguntas($conexion, $idFormulario);
		$opciones = $cf->listarOpciones($conexion, $idFormulario);
		
		//**********************CARGAR LOS DATOS DENTRO DE LOS OTROS DATOS
		$categoriasJS = array();
		
		while ($categoria = pg_fetch_assoc($categorias)){
			$categoriasJS[$categoria['id_categoria']] = $categoria;
		}
		
		while ($pregunta = pg_fetch_assoc($preguntas)){
			$categoriasJS[$pregunta['id_categoria']]['preguntas'][$pregunta['id_pregunta']] = $pregunta;
		}
		
		while ($opcion = pg_fetch_assoc($opciones)){
			$categoriasJS[$opcion['id_categoria']]['preguntas'][$opcion['id_pregunta']]['opciones'][] = $opcion;
		}
		
		//***********************RETIRAR LOS INDICES DE CADA UNO DE LO REGISTRO
		$categoriasJS = array_values($categoriasJS);
		
		foreach ($categoriasJS as $indice => $categoria){
			if($categoria['preguntas'] != null)
				$categoriasJS[$indice]['preguntas'] = array_values($categoria['preguntas']);
		}
		
		
		
		$formulario['categorias'] = $categoriasJS;
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $formulario;
		//$mensaje['mensaje'] = $categoriasJS;
		
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