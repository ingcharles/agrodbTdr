<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$identificadorUser=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->obtenerDatosDeclaracionJuramentada($conexion, $identificadorUser,"Ingresado','Modificado");

$identificador=$_SESSION['usuario'];;
?>

<header>
	<h1>Declaración Juramentada</h1>
</header>

<form id="formDeclaracionJuramentada" data-rutaAplicacion="uath" data-opcion="verificarDeclaracionJuramentada" data-accionEnExito="ACTUALIZAR"> 
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" /> <input
		type="hidden" id="opcion" value="Actualizar" name="opcion" /> 
		 <input type="hidden" id="archivo" name="archivo" value="" />
		 <input type="hidden" id="identificadorServidor"  name="identificadorServidor" value="<?php echo $identificadorUser;?>" />

	<p>
		<button id="actualizar" type="submit" class="guardar">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<?php
	while($declaracionJuramentada = pg_fetch_assoc($res)){
	echo  '<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Declaracion Juramentada</legend>
		            <input	type="hidden" id="declaracion_seleccionado" value="'.$declaracionJuramentada['id_datos_declaracion_juramentada'].'"	name="declaracion_seleccionado[]" />
					<div data-linea="1">
						<label>Fecha de Ingreso</label> <input type="text"
							name="fecha" id="fecha"
							value="'.$declaracionJuramentada['fecha'].'"
							readonly="readonly" />
					</div>
				   <div data-linea="2">
						<label>Fecha declaración</label> <input type="text"
							name="fecha" id="fecha"
							value="'.$declaracionJuramentada['fecha_declaracion'].'"
							readonly="readonly" />
					</div>
					<div data-linea="6">
						<label>Archivo Adjunto</label>';?>
									<?php echo ($declaracionJuramentada['ruta_declaracion_juramentada']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$declaracionJuramentada['ruta_declaracion_juramentada'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
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
							value="" placeholder="'.$declaracionJuramentada['observacion'].'" />
					</div>
				</fieldset>
			</td>
		</tr>';
	}?>
	</table>
</form>

<script type="text/javascript">

	$("#formDeclaracionJuramentada").submit(function(event){
		event.preventDefault();
		if(confirm('Se va a dejar en estado '+$('#estado_item').val()+' la solicitud. Desea Continuar?')){
			ejecutarJson($(this));
		}
	});
	
	   	
	$(document).ready(function(){
			
		construirValidador();
		distribuirLineas();
	});

</script>

