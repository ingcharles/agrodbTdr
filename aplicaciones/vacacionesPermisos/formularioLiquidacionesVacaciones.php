<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatastro.php';

try {
    $conexion = new Conexion();
    $cv = new ControladorVacaciones();

    $tmp = explode('.', $_POST['id']);
    $identificador = $tmp[0];
    $estado = $tmp[1];
    $idLiquidacion = $tmp[2];

    $consulta = pg_fetch_assoc($cv->filtroObtenerSaldoFuncionarioLiquidar($conexion, $identificador, $estado,$idLiquidacion));
    
} catch (Exception $e) {
   // echo $e;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<header>
	<h1>Detalle liquidación de vacaciones</h1>
</header>
<body>
	<fieldset>
		<legend>Saldo de vacaciones a liquidar</legend>
		<div data-linea="1">
			<label>Cédula:</label> 
				<span><?php echo $identificador;?></span>
		</div>
		<div data-linea="2">
			<label>Nombre:</label> 
				<span><?php echo $consulta['funcionario'];?></span>
		</div>
		<div data-linea="3">
			<label>Área:</label> 
				<span><?php echo $consulta['gestion'];?></span>
		</div>
		<div data-linea="4">
			<label>Saldo liquidado:</label> 
				<span><?php echo $cv->devolverTiempoFormateadoDHM($consulta['minutos_liquidados']);?></span>
		</div>
        <div data-linea="5">
			<label>Número de CUR:</label> 
				 <span><?php echo $consulta['numero_cur'];?></span>
		</div>
		<div data-linea="6">
			<label>Fecha:</label> 
				<span><?php echo $consulta['fecha_liquidacion'];?></span>
		</div>
	</fieldset>
	
</body>
<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
	construirValidador();

    });

</script>
	</html>

