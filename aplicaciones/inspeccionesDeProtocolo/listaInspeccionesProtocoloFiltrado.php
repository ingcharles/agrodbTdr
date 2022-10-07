<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';

$conexion = new Conexion();
$cp = new ControladorProtocolos ();

echo '<table>
        <thead>
            <tr>
    			<th>#</th>
    			<th>Identificador operador</th>	
    			<th>Razón social</th>
    			<th>Código Sitio</th>
    			<th>Código área</th>
		  </tr>
        </thead>';

$qListaAreasProtocolo = $cp->filtroAreasProtocolos($conexion, $_POST['bIdentificador'], $_POST['bRazonSocial'], $_POST['bCodigoSitio']);
$contador = 0;

while($fila = pg_fetch_assoc($qListaAreasProtocolo)){
    
    echo '<tr
    		id="'.$fila['id_protocolo_area'].'"
    		class="item"
    		data-rutaAplicacion="inspeccionesDeProtocolo"
    		data-opcion="abrirInspeccionesProtocolo"
    		ondragstart="drag(event)"
    		draggable="true"
    		data-destino="detalleItem">
    		<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
    		<td>'.$fila['identificador_operador'].'</td>
    		<td>'.$fila['nombre_operador'].'</td>
    		<td>'.$fila['nombre_lugar'].'</td>
    		<td>'.$fila['nombre_area'].'</td>
    		</tr>';
}
echo '</table>';
