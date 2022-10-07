<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$academico_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->obtenerDatosAcadémicos($conexion, '', $academico_seleccionado);


$identificador=$_SESSION['usuario'];

?>

<header>
	<h1>Modificar Datos Academicos</h1>
</header>

<form id="datosAcademicos" data-rutaAplicacion="uath" data-opcion="verificaDatosAcademicosAdmin" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
	<input type="hidden" id="archivo" name="archivo" value="0" />
    <input type="hidden" id="identificadorServidor"  name="identificadorServidor" value="<?php echo $academico_seleccionado;?>" />
	<p>
		<button id="actualizar" type="submit" class="guardar">Actualizar</button>
	</p>
	<div id="estado"></div>
	
	<table class="soloImpresion">
	<?php
	while($academico = pg_fetch_assoc($res)){
	echo  '<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Académica</legend>
		            <input type="hidden" id="academico_seleccionado" value="'.$academico['id_datos_academicos'].'" name="academico_seleccionado[]" />
					<div data-linea="1">
						<label>Nivel de Instrucción</label> 
							<input type="text"	name="nivel_instruccion" id="nivel_instruccion" value="'.$academico['nivel_instruccion'].'" disabled="disabled" readonly="readonly" />
					</div>
					<div data-linea="2">
						<label>País</label> 
							<input type="text" name="titulo" value="'.$academico['pais'].'" disabled="disabled" readonly="readonly" /> 
					</div>
					<div data-linea="2">
						<label>Institución</label> 
							<input type="text" name="institucion" value="'.$academico['institucion'].'" disabled="disabled" readonly="readonly" />
					</div>
					<div data-linea="3">
						<label>Título</label> 
							<input type="text" name="titulo" value="'.$academico['titulo']. '" disabled="disabled" readonly="readonly" />
					</div>
					<div data-linea="3">
						<label>No. Certificado</label> 
							<input type="text" name="num_certificado" id="num_certificado" value="'.$academico['num_certificado'].'" disabled="disabled" readonly="readonly" />
					</div>
					
					<div data-linea="4">
						<label>Años de Estudio</label> 
							<input type="text" name="años_estudio" id="años_estudio"  value="'.$academico['anios_estudio']. '" disabled="disabled" readonly="readonly" />
					</div>
					<div data-linea="4">
						<label>Egresado</label> 
							<input type="text" name="egresado" value="'.$academico['egresado'].'" disabled="disabled" readonly="readonly" />
					</div>
					
					
					<div data-linea="5">		
						<label>Certificado</label>';?>
						
	                <?php echo ($academico['archivo_academico']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$academico['archivo_academico'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					<?php echo '</div>
					<div data-linea="6">
						<label>Estado</label> <select name="estado_item[]" id="estado_item">
							<option value="Aceptado">Aceptado</option>
							<option value="Rechazado">Rechazado</option>
						</select>
					</div>
					<div data-linea="7">
						<label>Observaciones</label> <input type="text"
							name="observaciones[]" id="observaciones" required="required"
							value="" placeholder="'.$academico['observaciones_rrhh'].'" />
					</div>

				</fieldset>
			</td>
		</tr>';
	}?>
	</table>
</form>

<script type="text/javascript">

	$("#datosAcademicos").submit(function(event){
		event.preventDefault();
		if(confirm('Se va a dejar en estado '+$('#estado_item').val()+' la solicitud. Desea Continuar?')){
			ejecutarJson($(this));
		}
		
	});
  
 
	$(document).ready(function(){
		cargarValorDefecto("nivel_instruccion","<?php echo $academico['nivel_instruccion']?>");
		$('#numero_contrato').ForceNumericOnly();
		$('#numero_notaria').ForceNumericOnly();
		construirValidador();
		distribuirLineas();
	});

</script>
