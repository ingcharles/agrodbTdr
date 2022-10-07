<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReformaPresupuestaria.php';
	
	$conexion = new Conexion();
	$crp = new ControladorReformaPresupuestaria();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		$idAreaFuncionario = $_SESSION['idArea'];
		$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
	}//$usuario=0;
	
	$idEjercicio = $_POST['id'];
	
	$papPac = pg_fetch_assoc($crp->buscarImportacionPapPac($conexion, $idEjercicio));
	
	?>
	
	<header>
		<h1>Ejercicio <?php echo $idEjercicio; ?></h1>
	</header>

	<div id="estado"></div>
	
	<div class="pestania">
		<div id="informacion">
			<fieldset>
				<legend>PAP - PAC <?php echo $idEjercicio; ?></legend>
				
				<div data-linea="2">
					<label>Fecha de Importación:</label>
					<?php echo $papPac['fecha_importacion'];?>
				</div>
				
				<div data-linea="3">
					<label>Observaciones:</label>
					<?php echo $papPac['observaciones'];?>
				</div>
				
				<div data-linea="4">
					<label>Estado:</label>
					<?php echo $papPac['estado'];?>
				</div>
		
			</fieldset>
			
		</div>
	</div>
	
<script type="text/javascript">
	var usuario = <?php echo json_encode($usuario); ?>;
	var estadoProgramacionAnual = <?php echo json_encode($estadoProgramacionAnual); ?>;
	
		$("document").ready(function(){
			construirValidador();
			distribuirLineas();

			
			if(usuario == '0'){
				$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
				$("#botonGuardar").attr("disabled", "disabled");
			}
		});
</script>