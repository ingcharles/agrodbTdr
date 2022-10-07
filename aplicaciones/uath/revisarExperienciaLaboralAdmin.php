<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$experiencia_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->obtenerExperienciaLaboral($conexion, $_SESSION['usuario'], $experiencia_seleccionado);

$identificador=$_SESSION['usuario'];;
?>

<header>
	<h1>Modificar Experiencia Laboral</h1>
</header>

<form id="datosExperiencia" data-rutaAplicacion="uath" data-opcion="verificaExperienciaLaboralAdmin" data-accionEnExito="ACTUALIZAR"> 
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
	while($experiencia = pg_fetch_assoc($res)){
	$salida=$experiencia['fecha_salida']!=''?date('j/n/Y',strtotime($experiencia['fecha_salida'])):'';
	$marcado=$salida==''? ' checked="true"':'';
		
	echo  '<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Experiencia</legend>
		            <input	type="hidden" id="laboral_seleccionado" value="'.$experiencia['id_experiencia_laboral'].'"	name="laboral_seleccionado[]" />
					<div data-linea="1">
						<label>Tipo Institucion</label> <input type="text"
							name="tipo_institucion" id="tipo_institucion"
							value="'.$experiencia['tipo_institucion'].'"
							readonly="readonly" disabled="disabled" />
					</div>
		            <div data-linea="1">
						<label>Trabajo hasta la fecha actual</label> 
							<input type="checkbox" id="trabajoActual" name="trabajoActual"'.$marcado.' disabled="disabled"  />
					</div>
					<div data-linea="2">
						<label>Institucion</label> <input type="text" name="institucion"
							id="institucion"
							value="'.$experiencia['institucion'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="2">
						<label>Unidad Administrativa</label> <input type="text"
							name="unidad_administrativa"
							value="'.$experiencia['unidad_administrativa'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="3">
						<label>Puesto</label> <input type="text" name="puesto" id="puesto"
							value="'.$experiencia['puesto'].'" readonly="readonly"
							disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Fecha Ingreso</label> <input type="text"
							name="fecha_ingreso" readonly="readonly"
							value="'.date('j/n/Y',strtotime($experiencia['fecha_ingreso'])).'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Fecha Salida</label> <input type="text" name="fecha_salida"
							readonly="readonly"
							value="'.$salida.'"
							readonly="readonly" disabled="disabled" />
					</div>
					
					<div data-linea="5">
						<label>Motivo Salida</label> <input type="text"
							name="movimiento_salida"
							value="'.$experiencia['motivo_salida'].'"
							readonly="readonly" disabled="disabled" />
					</div>
					<div data-linea="6">
						<label>Certificado</label>';?>
									<?php echo ($experiencia['archivo_experiencia']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$experiencia['archivo_experiencia'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
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
							value="" placeholder="'.$experiencia['observaciones_rrhh'].'"/>
					</div>
					
				</fieldset>
			</td>
		</tr>';
	}
	
	?>
	</table>
</form>

<script type="text/javascript">

	$("#datosExperiencia").submit(function(event){
		event.preventDefault();
		if(confirm('Se va a dejar en estado '+$('#estado_item').val()+' la solicitud. Desea Continuar?')){
			ejecutarJson($(this));
		}
	});
	
	$(document).ready(function(){
		cargarValorDefecto("nivel_instruccion","<?php echo $academico['nivel_instruccion']?>");
		
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
