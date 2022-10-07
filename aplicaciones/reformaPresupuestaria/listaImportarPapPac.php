<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorReformaPresupuestaria.php';
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		$idAreaFuncionario = $_SESSION['idArea'];
		$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
	}
	
	//print_r($_SESSION);
?>

	<header>
		<h1>Importar PAP - PAC</h1>
		<nav>
		<?php 
			
			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);
			
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}
		?>
		</nav>
	</header>
	
	<div id="estadoSesion"></div>
	
	<div id="activo">
		<h2>Ejercicios Activos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="inactivo">
		<h2>Ejercicios Antiguos</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
	if($identificador != ''){
		$crp = new ControladorReformaPresupuestaria();
		$res = $crp->listarImportacionPapPac($conexion);
			
		while($fila = pg_fetch_assoc($res)){
			//Planificacion Anual
			if($fila['estado']=='activo'){
				$categoria ="activo";
			}else if($fila['estado']=='inactivo'){
				$categoria ="inactivo";
			}
			
			$contenido ='<article 
								id="'.$fila['anio'].'"
								class="item"
								data-rutaAplicacion="reformaPresupuestaria"
								data-opcion="abrirEjercicioMigrado" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span><b>Ejercicio: </b>'.$fila['anio'].'</span>
							<aside><small>Fecha: '.$fila['fecha_importacion'].'</small></aside>
						</article>';		
	?>
			
			<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
			</script>
	<?php	
			}
		}				
	?>

<script>
var usuario = <?php echo json_encode($usuario); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	

		$("#activo div> article").length == 0 ? $("#activo").remove():"";
		$("#inactivo div> article").length == 0 ? $("#inactivo").remove():"";	
	});

	if(usuario == '0'){
		$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#_nuevo").hide();
		$("#_eliminar").hide();
		$("#_confirmarImportarPapPac").hide();
	}
</script>