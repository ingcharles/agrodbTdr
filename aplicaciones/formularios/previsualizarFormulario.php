<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorFormularios.php';

$conexion = new Conexion();
$cf = new ControladorFormularios();
$constg = new Constantes();

$idFormulario = htmlspecialchars($_POST['idFormulario'], ENT_NOQUOTES, 'UTF-8'); // send from other page

$categorias = $cf->listarCategorias($conexion, $idFormulario);
$preguntas = $cf->listarPreguntas($conexion, $idFormulario);
$opciones = $cf->listarOpciones($conexion, $idFormulario);
$formulario = pg_fetch_assoc($cf->abrirFormulario($conexion, $idFormulario));
$opcionesHTML = array();
$indicesHTML = array();



$categoriasHTML = '';
while ($categoria = pg_fetch_assoc($categorias)) {
	$categoriasHTML .= '<fieldset id="C' . $categoria['id_categoria'] . '" class="visualizacion_categoria">' .
		'<legend>' . $categoria['nombre'] . '</legend>' .
		'</fieldset>';
}

while ($opcion = pg_fetch_assoc($opciones)) {
	$indicesHTML[] = $opcion['id_pregunta'];
	$opcionesHTML[] = $opcion['opcion'];
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Panel de control GUIA</title>
	<!-- link
	href='http://fonts.googleapis.com/css?family=Text+Me+One|Poiret+One|Open+Sans'
	rel='stylesheet' type='text/css'-->
	<link rel='stylesheet' href='estilos/estiloapp.css'>


	<script src="../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="../general/funciones/agrdbfunc.js" type="text/javascript"></script>

</head>

<body>
	<div id="visualizacion">
		<section class="visualizacion_pagina">
			<header >
				<div>
					<img alt="Logo Magap" src="../general/img/cabeceraLineaGrafica.png" style="width: 100%" />
				</div>
				<div id="titulos">
					<h2>
						<?php echo $formulario['nombre']; ?>
					</h2>
				</div>
			</header>

			<section id="formulario">
				<h3>PARTE A: Datos generales del operador</h3>
				<fieldset>
					<div data-linea="1">
						<label>Fecha</label>
						<input class="texto">
					</div>
					<div data-linea="1">
						<label>Subproceso</label>
						<input class="texto">
					</div>
					<div data-linea="1">
						<label>Tipo de inspección</label>
						<input class="texto">
					</div>
					<hr />
					<div data-linea="3">
						<label>Registro de operador</label>
						<input class="texto">
					</div>
					<div data-linea="3">
						<label>Nombre</label>
						<input class="texto">
					</div>
					<div data-linea="4">
						<label>Teléfono</label>
						<input class="texto">
					</div>
					<div data-linea="4">
						<label>Correo</label>
						<input class="texto">
					</div>
					<div data-linea="5">
						<label>Dirección de oficina tributaria</label>
						<input class="texto">
					</div>
					<div data-linea="6">
						<label>Provincia</label>
						<input class="texto">
					</div>
					<div data-linea="6">
						<label>Cantón</label>
						<input class="texto">
					</div>
					<div data-linea="6">
						<label>Parroquia</label>
						<input class="texto">
					</div>
					<hr />
					<div data-linea="11">
						<label>Nombre del sitio</label>
						<input class="texto">
					</div>
					<div data-linea="7">
						<label>Punto de control</label>
						<input class="texto">
					</div>
					<div data-linea="7">
						<label>Lugar de inspección</label>
						<input class="texto">
					</div>
					<div data-linea="8">
						<label>Nombre de producto</label>
						<input class="texto">
					</div>
					<div data-linea="8">
						<label>Partida arancelaria</label>
						<input class="texto">
					</div>
					<div data-linea="9">
						<label>Paises de destino</label>
						<input class="texto">
					</div>
					<hr />
					<div data-linea="10">
						<label>Código de muestra</label>
						<input class="texto">
					</div>
					<div data-linea="10">
						<label>Tipo de análisis</label>
						<input class="texto">
					</div>
					<div data-linea="10">
						<label>Laboratorio</label>
						<input class="texto">
					</div>

				</fieldset>
			</section>
			<section></section>
			<section id="listadoPreguntas">
				<h3>PARTE B: Formulario</h3>
				<!--fieldset id="categoria1" class="categoria">
				<legend>Categoria</legend>
				<section id="pregunta1">
					<div class="pregunta">Pregunta 1</div>
				</section>
			</fieldset-->
			</section>
			<section id="resultado">
				<h3>PARTE C: Resultados de la inspección</h3>
				<fieldset>
					<div data-linea="1">
						<label>Nombre inspector</label>
						<input class="texto">
					</div>
					<div data-linea="2">
						<label>Estado de inspección</label>
						<input class="texto">
					</div>
					<div data-linea="2">
						<label>Resultado de la inspección</label>
						<input class="texto">
					</div>
					<div data-linea="3">
						<label>Observaciones</label>
						<input class="texto">
					</div>
				</fieldset>

			</section>
			<div>
				<img alt="Logo" src="../general/img/pieLineaGrafica.png" style="width: 100%; margin-top:10px" />
			</div>
		</section>
	</div>
</body>
<script type="text/javascript">
	var categoriasHTML = <?php echo json_encode($categoriasHTML); ?>;


	$("#listadoPreguntas").append(categoriasHTML);

	<?php

	$preguntaHMTL = '';
	$idCategoria = 0;
	$contador = 1;
	while ($pregunta = pg_fetch_assoc($preguntas)) {
		$idCategoria = $pregunta['id_categoria'];
		$preguntaHMTL = '<div data-linea="' . $contador . '" id="P' . $pregunta['id_pregunta'] . '" >' .
			'<label class="pregunta">' . $pregunta['nombre'] . '</label>';
		switch ($pregunta['tipo_pregunta']) {
			case 1: //informativa
				$preguntaHMTL .= '<input class="texto"/>';
				break;
				/*case 2: //informativa, multimple
				$preguntaHMTL .= '<input />';
				break;
			case 3: //multiple
				$preguntaHMTL .= '<input />';
				break;*/
			case 4: //rango
				$preguntaHMTL .= '<input class="texto"/>';
				break;
		}

		$preguntaHMTL .= '</div>';
	?>
		var idCategoria = <?php echo json_encode($idCategoria); ?>;
		var pregunta = <?php echo json_encode($preguntaHMTL); ?>;
		$("#C" + idCategoria).append(pregunta);
	<?php

		$contador++;
	} ?>

	var opcionesHTML = <?php echo json_encode($opcionesHTML); ?>;
	var indicesHTML = <?php echo json_encode($indicesHTML); ?>;

	for (opcion in opcionesHTML) {
		$("#P" + indicesHTML[opcion]).append('<span class="opcion"><input type="checkbox">' + opcionesHTML[opcion] + '</span>');
	}

	$('document').ready(function() {
		distribuirLineas();
	});
</script>

</html>