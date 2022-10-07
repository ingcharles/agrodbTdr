<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();

$identificador=$_SESSION['usuario'];

$valores_accidentes=pg_fetch_array($cai->listarDatosAccidente($conexion,'', '','','',3,'',$identificador));
echo $solicitud=$valores_accidentes['cod_datos_accidente'];
if($solicitud != ''){
	$datosCitaMedica=pg_fetch_array($cai->buscarCitaMedica($conexion,$solicitud));

	?>
<header>
	<h1>Cita Médica</h1>
</header>


<div id="estado"></div>

<fieldset>
	<legend>Información de Cita Médica Programada</legend>
	<div data-linea="1">
		<label>Fecha de Atención:</label> <input type="text" id="fechaCita"
			name="fechaCita" value="<?php echo $datosCitaMedica['fecha_cita'];?>" />
	</div>
	<div data-linea="1">
		<label>Hora:</label> <input id="horaCita" name="horaCita"
			class="menores" value="<?php echo $datosCitaMedica['hora_cita'];?>"
			type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" />
	</div>
	<div data-linea="3">
		<label>Nombre Médico/a:</label> <input type="text" id="nombreMedico"
			name="nombreMedico"
			value="<?php echo $datosCitaMedica['nombre_medico'];?>" />
	</div>
	<div data-linea="4">
		<label>Dirección de Atención:</label> <input type="text"
			id="direccionMedico" name="direccionMedico"
			value="<?php echo $datosCitaMedica['direccion_medico'];?>" />
	</div>

</fieldset>
<?php 

}else{
	?>
<header>
	<h1>Cita Médica</h1>
</header>
<fieldset>
	<legend>Información...!!</legend>
	<div data-linea="1">
		<label>No existen registros</label>
	</div>
</fieldset>
<?php 	
}
?>

<script type="text/javascript">

	$(document).ready(function(){
		$('input[type="text"], textarea').attr('readonly','readonly'); 	    
		    
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
	});

</script>
