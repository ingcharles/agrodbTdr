<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	$idOpcionArea = $_POST['id'];
	
	$idServicio = pg_fetch_assoc($cc->obtenerIdServicioXarea($conexion, $idOpcionArea, 'activo'));
	$res = $cc->obtenerServicioXarea($conexion, $idServicio['id_servicio'],'DOCUMENTOS');

?>

<header>
	<h1>Nuevo documento</h1>
</header>

<div id="estado"></div>
<table class="soloImpresion">
	<tr>
		<td>
			<form id="nuevoDocumento" data-rutaAplicacion="financiero" data-opcion="guardarNuevoServicio" >
				<input type="hidden" id="area" name="area" value="<?php echo $idOpcionArea;?>" />
				<fieldset>
					<legend>Unidad administrativa</legend>	
						<div data-linea="1">
							<label for="nombreUnidadAdministrativa" >Nombre</label>
								<?php 
									if($idOpcionArea== 'SV') $unidadArea = 'Sanidad Vegetal'; 
									if($idOpcionArea== 'SA') $unidadArea = 'Sanidad Animal';
									if($idOpcionArea== 'IA') $unidadArea = 'Inocuidad de los alimentos';
									if($idOpcionArea== 'LT') $unidadArea = 'Análisis de laboratorios';
									if($idOpcionArea== 'CGRIA') $unidadArea = 'Control de Insumos Agropecuarios';
									if($idOpcionArea== 'AGR') $unidadArea = 'Otros Ingresos';
								?>
							<input name="nombreUnidadAdministrativa" id="nombreUnidadAdministrativa" type="text" value="<?php echo $unidadArea; ?>" disabled="disabled"/>
						</div>
				</fieldset>
												
				<fieldset>
						<legend>Documento</legend>	
						<div data-linea="1">
							<label for="nombreDocumento">Documento</label>
							<input name="nombreDocumento" id="nombreDocumento" type="text" required="required" />
							<button type="submit" class="mas">Añadir nuevo documento</button>
						</div>
				</fieldset>
			</form>
			<fieldset>
				<legend>Lista de documentos</legend>
				<table id="listaDocumentos">
						<?php 
							while ($documento = pg_fetch_assoc($res)){
								echo $cf->imprimirLineaDocumento($documento['id_servicio'], $documento['codigo'], $documento['concepto'],'financiero',$idOpcionArea);
							}
						?>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<script type="text/javascript">
				
$(document).ready(function(){
	acciones("#nuevoDocumento","#listaDocumentos");
	distribuirLineas();
});
      
</script>


