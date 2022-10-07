<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

try {
    $conexion = new Conexion();
    $cv = new ControladorVacaciones();

    $tmp = explode('.', $_POST['id']);
    $identificador = $tmp[0];
    $estado = $tmp[1];

    //$listaReporte = $cv->filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estado, '', '', '', 'individual');
    
    
    $listaReporte = $cv->filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estado, '', '', '', '');
    
} catch (Exception $e) {
   // echo $e;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

	<fieldset>
		<legend>Saldo de vacaciones</legend>
		<table style="width: 100%">
			<thead>
				<tr>
					<th>Año</th>
					<th>Mes</th>
					<th colspan="2">Cantidad disponible</th>
					<th>Total</th>
				</tr>
				<tr> <td colspan="2" ><th>Días laborables</th>
					<th>Días no laborables</th> 
					<th></th>
				</tr>
			</thead>

			<?php
            echo PHP_EOL;
            $datos = $cv->devolverTiempoActual($conexion, $listaReporte, $identificador,$estado);
            foreach ($datos as $fila) {
                echo '<tr>
            				
            					<td align="left">' . $fila['anio'] . '</td>
								<td align="left">' . $fila['mes'] . '</td>
                                <td align="left">' . $fila['utilizado'] . '</td>
                                <td align="left">' . $fila['libre'] . '</td>
            					<td align="right">' . $fila['tiempo'] . ' día(s) </td>
            				</tr>';
           }
           echo'<tr>
               <th colspan="5" align="right"> Total: '.$fila['tiempoTotal'].'</th>
               </tr>';
?>
		</table>

	</fieldset>
</body>

</html>
