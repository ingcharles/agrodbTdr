<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';

	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$idSolicitud = $_POST['idSolicitud'];

	$conexion = new Conexion();
	$cr= new ControladorRegistroOperador();
    $formularioLaboratorio='

	<table id="analisisLaboratorio" style="width:100%">
		<thead>
			<tr>
				<th>Producto</th>
				<th>Parámetro</th>
				<th>Método</th>
				<th>Rango</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody>';
$res = $cr->obtenerProductosLaboratorios($conexion,$idOperadorTipoOperacion);
$contenido='';
while($fila = pg_fetch_assoc($res)){
$contenido .= '<tr>'.'<td>'.$fila['nombre_comun'].'</td>
				<td>'.$fila['nombre_parametro'].'</td>
				<td>'.$fila['nombre_metodo'].'</td>
				<td>'.$fila['descripcion_rango'].'</td>
				<td class="borrar">											
					<button type="button" class="icono" onclick="eliminarProducto('.$fila['id_operacion'].','.$fila['id_operacion_parametro_laboratorio'].','.$fila['id_tipo_operacion'].')"></button>
					</td>
				</tr>';				
}
$formularioLaboratorio.=$contenido.'
    </tbody>
	    </table>';
echo $formularioLaboratorio;
?>


