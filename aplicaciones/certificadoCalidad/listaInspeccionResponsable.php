<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cc = new ControladorCertificadoCalidad();

//Obtener usuarios por provincia de acuerdo al perfil

	$identificador =  $_SESSION['usuario'];
	$nombreOpcion = $_POST['nombreOpcion'];
	
	$estado = "'inspeccionResponsable'";
	
	$qOperadores = $cc->obtenerSolicitudesCertificadoCalidad($conexion, $provincia, $estado, 'OPERADORES');
?>

<header>
	<nav>
	<form id="listaRevision" data-rutaAplicacion="certificadoCalidad" data-opcion="listaRevisionResponsableGrupo" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Operador:</th>
			
				<td>
					<select id="identificadorOperador" name="identificadorOperador">
						<option value="" >Seleccione....</option>
						<?php 
							while ($fila = pg_fetch_assoc($qOperadores)){
								echo '<option value="'.$fila['identificador'].'">'.$fila['nombre_operador'].'</option>';
							}
						?>
								
					</select>
					
					<input type="hidden" name="opcion" value= "<?php echo $_POST["opcion"];?>">
					
				</td>
			</tr>
				
				<tr >	
					<td id="operador" colspan="2"></td>
					<td colspan="5"><button>Filtrar lista</button></td>
				</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>

<script>
	$("#listaRevision").submit(function(e){
		abrir($(this),e,false);		
	});

	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	});


</script>
