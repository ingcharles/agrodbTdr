<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';


$conexion = new Conexion();
$cc = new ControladorCatastro();
$res = $cc->obtenerDatosContrato($conexion,$_POST['id']);
$contrato = pg_fetch_assoc($res);


?>

<header>
	<h1>Datos Contrato</h1>
</header>

<form id="datosContrato" data-rutaAplicacion="uath"
	data-opcion="guardarContrato">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />

	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Detalles de contrato</legend>
					<div data-linea="1">
						<label>Tipo de Contrato:</label>
						<?php echo $contrato['tipo_contrato'];?>
					</div>
					<div data-linea="2">
						<label>Número de contrato:</label>
						<?php echo $contrato['numero_contrato'];?>
					</div>
					<div data-linea="3">
						<label>Lugar de trabajo:</label>
						<?php echo $contrato['coordinacion'];?>
					</div>
					<div data-linea="4">
						<label>Puesto que ocupa:</label>
						<?php echo $contrato['nombre_puesto'];?>
					</div>
					<div data-linea="5">
						<label>Sueldo:</label>
						<?php echo $contrato['remuneracion'];?>
					</div>
					<div data-linea="6">
						<label>Regimen Laboral:</label>
						<?php echo $contrato['regimen_laboral'];?>
					</div>
					<hr />
					<div data-linea="7">
						<label>Inicio de contrato:</label>
						<?php echo date('j/n/Y',strtotime($contrato['fecha_inicio']));?>
					</div>
					<div data-linea="7">
						<label>Fin de contrato:</label>
						<?php 						
						if($contrato['fecha_fin']!='')
							$FechaFin = date('j/n/Y',strtotime($contrato['fecha_fin']));
						else
							$FechaFin= "Trabaja hasta la actualidad";
						echo $FechaFin;
						
						?>
					</div>
					<hr />
					<div data-linea="8">
						<label>Declaración: </label> Notaría
						<?php if($contrato['numero_notaria']==''){
							echo '0';
						} else {echo $contrato['numero_notaria'];
						};?>
						,
						<?php echo $contrato['lugar_notaria'];?>
						(
						<?php 
						
						if($contrato['fecha_declaracion']!='')
							$FechaDeclaracion = date('j/n/Y',strtotime($contrato['fecha_fin']));
						else
							$FechaDeclaracion= "No se ha registrado la fecha de la declaración";
						echo $FechaDeclaracion;
						
						?>
						)
					</div>
					<div data-linea="100">
						<label>Observación:</label>
						
					</div>
					</fieldset>
					<fieldset>
					<legend>Archivo Contrato</legend>
					<div data-linea="5" >
					<?php 
			    	echo $contrato['archivo_contrato']=='0' ? '<span class="alerta">No existe ningún archivo.</span>':'<a href="'.$contrato['archivo_contrato'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
			    	?>
					
   					</div>


					</fieldset>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">


	$(document).ready(function(){

		distribuirLineas();
		
			});

	
</script>
