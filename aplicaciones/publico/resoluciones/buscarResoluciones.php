<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorResoluciones.php';

$conexion = new Conexion();
$cr = new ControladorResoluciones();

$numeroResolucion = htmlspecialchars($_POST['numero']);
$nombreResolucion = htmlspecialchars($_POST['nombre']);
$fecha1 = htmlspecialchars($_POST['fecha1']);
$fecha2 = htmlspecialchars($_POST['fecha2']);
$palabrasClave = htmlspecialchars($_POST['palabras_clave']);

$resoluciones = $cr->buscarResoluciones($conexion, $numeroResolucion, $nombreResolucion, $fecha1, $fecha2, explode(' ', $palabrasClave));

echo '<h1>Listado</h1><table>';
echo '<div id="resultado">Se encontró '. pg_num_rows($resoluciones) .' registro(s).</div>';
echo '<thead><tr>'.
		'<th>Número</th>'.
		'<th>Nombre</th>'.
		'<th>Fecha</th>'.
		'<th>Estado</th>'.
		'<th>Ver</th>'.
			
		'</tr></thead><tbody>';

while ($resolucion = pg_fetch_assoc($resoluciones)){
	echo '<tr class="'.$resolucion['estado'].'">'.
			'<td>'. $resolucion['numero_resolucion'] . '</td>'.
			'<td>'. $resolucion['nombre'] . '</td>'.
			'<td class="no_salto">'. $resolucion['fecha'] . '</td>'.
			'<td class="no_salto">'. $resolucion['estado'] . '</td>'.
			'<td><a href="cargarResolucion.php?resolucion='. $resolucion['id_resolucion'] .'" class="abrir" target="_blank">Abrir</a></td>'.
		'</tr>';
}
echo '</tbody></table>';