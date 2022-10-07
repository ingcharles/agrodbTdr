<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	$identificador=$_SESSION['usuario'];
?>	
		<header>
			<h1>Rol Pagos</h1>
			<nav>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listarRolPagos" data-destino="listadoItems" href="#">Actualizar</a>
			</nav>
		</header>
		<table>
			<?php 
			if($identificador != ''){
				$res = $cc->obtenerRolPagos($conexion, $identificador,'SI');
				while($fila = pg_fetch_assoc($res)){
					$contenido ='<article
					id="'.$fila['anio'].'"
					class="item"
					data-rutaAplicacion="uath"
					data-opcion="listarRolPagosIndividual"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="roles">
					<span class="ordinal">'.++$contador.'</span>
					<span><br><br> <strong>ROLES AÑO</strong> '.$fila['anio'].' </span>
					<aside></aside>
					</article>';
					?>
 						<script type="text/javascript">
 							var contenido = <?php echo json_encode($contenido);?>;
 							$("#roles div.elementos").append(contenido);
 						</script>
 					<?php	
				}
			}else {
				echo '<span class="alerta"><br><br> <strong>Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.</strong></span>';
			}		
			?>
		</table>
		<div id="roles">
		<div class="elementos"></div></div>
 <script type="text/javascript">
	$(document).ready(function(){
		$("#listadoItems").removeClass("lista");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial"></div>');
	});

	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});	
 </script>
