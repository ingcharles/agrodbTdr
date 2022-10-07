<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$conexion = new Conexion();
$cf = new ControladorFinanciero();

$identificador = $_SESSION['usuario'];

$certificados = $cf->obtenerServiciosXTipo($conexion, 'Externo');

$serviciosAsignados = $cf->listarServiciosAsignados($conexion, $identificador);
?>

<header>
	<h1>Gestión de descuento de cupos</h1>
</header>

<form id="nuevoServicio" data-rutaAplicacion="financiero" data-opcion="asignarServicio" >
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>">
			
	<fieldset>
		<legend>Servicios disponibles</legend>	
		<div data-linea="1">
			<label for="tipoServicio">Servicio</label>
			<select id="tipoServicio" name="tipoServicio">
				<option value="">Seleccione....</option> 
				<?php 
					while($fila = pg_fetch_assoc($certificados)){
						echo '<option value="'.$fila[id_servicio].'">'.$fila[concepto].'</option>';
					}				
				?>
			</select>
			
			<input type="hidden" id="nombreServicio" name="nombreServicio" />
		</div>
		
		<div>
			<button type="submit" class="mas">Añadir requisito</button>
		</div>
	</fieldset>
</form>

<fieldset>
	<legend>Servicios asignados para descuento de cupos</legend>
	<table id="servicios">
		<?php
			while ($servicio = pg_fetch_assoc($serviciosAsignados)){
				echo $cf->imprimirLineaServicio($servicio['id_descuento_cupo'], $servicio['id_servicio'], $servicio['concepto'], $servicio['estado']);
			}
		?>
	</table>
</fieldset>
	

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();		
		actualizarBotonesOrdenamiento();
		acciones("#nuevoServicio", "#servicios");
		acciones();
	});

	$("#tipoServicio").change(function(){
		$('#nombreServicio').val($('#tipoServicio option:selected').text());
	});
	
</script>
