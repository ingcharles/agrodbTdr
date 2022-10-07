<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$experiencia_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->listaCapacitacionFuncionario($conexion,$experiencia_seleccionado,'IDENTIFICADOR');

$identificador=$_SESSION['usuario'];;
?>

<header>
	<h1>Validar Capacitaciones</h1>
</header>

<form id="datosCapacitacion" data-rutaAplicacion="uath" data-opcion="verificaCapacitacionAdmin" data-accionEnExito="ACTUALIZAR"> 
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" /> <input
		type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
		 <input type="hidden" id="archivo" name="archivo" value="" />
		 <input type="hidden" id="identificadorServidor"  name="identificadorServidor" value="<?php echo $experiencia_seleccionado;?>" />

	<p>
		<button id="actualizar" type="submit" class="guardar">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<?php
	while($capacitacion = pg_fetch_assoc($res)){
	echo  '<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Capacitación</legend>
		            <input	type="hidden" id="capacitacion_seleccionado" value="'.$capacitacion['id_datos_capacitacion'].'"	name="capacitacion_seleccionado[]" />
					<div data-linea="1">
						<label>Título capacitación</label> <input type="text"
							name="titulo_capacitacion" id="titulo_capacitacion"
							value="'.$capacitacion['titulo_capacitacion'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="2">
						<label>Institucion</label> <input type="text" name="institucion"
							id="institucion"
							value="'.$capacitacion['institucion'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="2">
						<label>País</label> <input type="text"
							name="pais"
							value="'.$capacitacion['pais'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="3">
						<label>Horas</label> <input type="text" name="horas" id="horas"
							value="'.$capacitacion['horas'].'" readonly="readonly"
							disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Fecha Inicio</label> <input type="text"
							name="fecha_inicio" readonly="readonly"
							value="'.date('j/n/Y',strtotime($capacitacion['fecha_inicio_capacitacion'])).'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Fecha Fin</label> <input type="text" name="fecha_fin"
							readonly="readonly"
							value="'.date('j/n/Y',strtotime($capacitacion['fecha_fin_capacitacion'])).'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="6">
						<label>Certificado</label>';?>
									<?php echo ($capacitacion['archivo_capacitacion']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo_capacitacion'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div>
					<div data-linea="7">
						<label>Estado</label> <select name="estado_item[]" id="estado_item">
							<option value="Aceptado">Aceptado</option>
							<option value="Rechazado">Rechazado</option>
						</select>
					</div>
					<div data-linea="8">
						<label>Observaciones</label> <input type="text"
							name="observaciones[]" id="observaciones" required="required"
							value="" placeholder="'.$capacitacion['observaciones'].'" />
					</div>
				</fieldset>
			</td>
		</tr>';
	}?>
	</table>
</form>

<script type="text/javascript">

	$("#datosCapacitacion").submit(function(event){
		event.preventDefault();
		if(confirm('Se va a dejar en estado '+$('#estado_item').val()+' la solicitud. Desea Continuar?')){
			ejecutarJson($(this));
		}
	});
	
	   	
	$(document).ready(function(){
			
		$( "#fecha_ingreso" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$( "#fecha_salida" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		construirValidador();
		distribuirLineas();
	});

</script>

