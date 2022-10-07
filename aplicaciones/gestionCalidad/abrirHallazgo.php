<?php
// session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';
// require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion ();
$cgc = new ControladorGestionCalidad ();
// $ca = new ControladorAuditoria();

// Validar sesion
// $conexion->verificarSesion();
$idHallazgo = htmlspecialchars ( $_POST ['id'], ENT_NOQUOTES, 'UTF-8' );
$hallazgo = pg_fetch_assoc($cgc->abrirHallazgo ( $conexion, $idHallazgo ));

$causas = $cgc->abrirCausas($conexion, $idHallazgo);
$acciones = $cgc->abrirAcciones($conexion, $idHallazgo);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Hallazgo</h1>
	</header>

	<div id="estado"></div>

	<fieldset>
		<legend>Detalle del hallazgo</legend>
		<div data-linea="0">
			<label for="area">Estado de tratamiento de hallazgo</label> 
			<span class="destacar"><?php echo $hallazgo['estado']?></span>
		</div>
		<hr/>
		<div data-linea="1">
			<label for="area">Área</label> 
			<?php echo $hallazgo['nombre']?>
		</div>
		<div data-linea="2">
			<label for="tipo">Tipo de hallazgo</label> 
			<?php echo $hallazgo['tipo']?>
		</div>
		<div data-linea="3">
			<label for="fecha">Fecha</label> 
			<?php echo $hallazgo['fecha_formateada']?>
		</div>
		<hr />
		<div data-linea="4">
			<label for="hallazgo">Hallazgo detectado</label>
			</div><div data-linea="5">
			<p><?php echo $hallazgo['hallazgo']?></p>
		</div>
		<div data-linea="6">
			<label for="norma">Norma y clausula</label>
			</div><div data-linea="7">
			<p><?php echo $hallazgo['norma']?></p>
		</div>
		
		<hr />
		<div data-linea="9">
			<label for="auditor">Auditor</label> <?php echo $hallazgo['auditor']?>
		</div>
		<div data-linea="10">
			<label for="auditor">Controlador</label> <?php echo $hallazgo['nombre_controlador']?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Causas propuestas</legend>
		<table id="causas">
			<tbody>
			<?php //Durante fase de ingreso de causas
							while ($causa = pg_fetch_assoc($causas)){
								echo $cgc->imprimirLineaCausa($causa['id_causa'], $causa['descripcion'],false);
							}
			?>
			</tbody>
		</table>
		<table>
			<!-- thead><tr><th></th></tr></thead-->
			<tbody>


			</tbody>
		</table>
		<hr />
		<label>Raíz</label>
	</fieldset>
	<fieldset>
		<legend>Acciones correctivas propuestas</legend>
		<table>
			<!-- thead><tr><th></th></tr></thead--><tbody>
			
			<?php 
				while ($accion = pg_fetch_assoc ($acciones)) {
					echo '<tr><td>' . $accion['descripcion'] . '</td><td>' . $acciones['fecha_culminacion'] . '</td><td>' . $acciones['estado'] . '</td></tr>';
				}
			?>
			</tbody>
		</table>
	</fieldset>
</body>
<script type="text/javascript">
$("document").ready(function(){
	distribuirLineas();
});
</script>
</html>