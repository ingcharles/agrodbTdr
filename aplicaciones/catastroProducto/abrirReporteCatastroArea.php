<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$cp = new ControladorCatastroProducto();
$idSitio =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');

?>
<header>
	<h1>Resumen de Catastro</h1>
	<fieldset>
		<legend>Resumen de Catastro</legend>
		<div >
			<?php 
			$qProductosCatastros=$cp->listaProductosCatastro1($conexion, $idSitio);
			while($filass=pg_fetch_assoc($qProductosCatastros)){
				echo '<center ><label style="text-align:center;font-size:120%">'.$filass['nombre_area'].' </label></center>';
				echo '<table width="100%">
						<tr style="border-collapse:collapse; border:none">
							<th>OPERACIÓN</td>
							<th>PRODUCTO</th>
							<th>UNIDAD</th>
							<th>EXISTENTES</th>
						</tr>';

	  			$qProductosCatastro=$cp->listaProductosCatastro($conexion, $idSitio ,$filass['id_area']);
				while($filas=pg_fetch_assoc($qProductosCatastro)){
					echo '<tr style="border-collapse:collapse; border:none">
						<th><label style="font-weight:400;">'.$filas['nombre_operacion'].'</label> </th>
						<th><label style="font-weight:400;">'.$filas['nombre_subtipo'].'-'.$filas['nombre_producto'].'</label></th>
						<th><label style="font-weight:400;">'.$filas['nombre_unidad_comercial'].' </label></th>
			  			<th><label style="font-weight:400;"><center>'.$filas['cantidad'].'</center></label></th>';
					echo '</tr>';
				}	
				echo '</table><hr />';

			}

			?>
		</div>
	</fieldset>

</header>
