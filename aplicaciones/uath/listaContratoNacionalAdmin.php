<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cd  = new ControladorCatastro();
$cc = new ControladorCatalogos();

$sitios = $cc->listarDireccionyProvincia($conexion);


$provincia=$_POST['localizacion'];

?>
<header>
	<h1>Nivel Nacional</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="uath" data-opcion="listaContratoNacionalAdmin" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 400px;'>
				<tbody>
					<tr>
						<th colspan="3">Buscar por provincia</th>
					</tr>
					<tr>
						<td>Provincia:</td>
						<td><select name="localizacion" id="localizacion" style="width: 100%">
								<?php
								foreach ($sitios as $sitio ){
                                               
                                            if(strcmp($sitio['nombre'],$provincia)==0) 
											  echo '<option value="'.$sitio['nombre'].'-'.$sitio['tipo'].'" selected="selected">' . $sitio['nombre'] . '</option>';
                                            else 
                                              echo '<option value="'.$sitio['nombre'].'-'.$sitio['tipo'].'">' . $sitio['nombre'] . '</option>';
										}
								?>

						</select>
						</td>
					</tr>

					<tr>
						<td id="mensajeError"></td>
						<td colspan="5">
							<button id='buscar'>Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</nav>

</header>
<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Tiempo</th>
			<th>Estado</th>

		</tr>
	</thead>
	<?php 
	if($provincia!=''){
		
		$res = $cd->obtenerDatosEstado($conexion, $provincia);
		$contador = 0;
		while($estado = pg_fetch_assoc($res)){
			echo '<tr 	id="'.$estado['identificador'].'" class="item">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$estado['apellido_nombre'].'</b></td>
				<td>'.$estado['tiempo'].'</td>';
			if($estado['dias_trabajados']>=606 ){
				echo '<td><span class= "advertencia"> Por caducar </span></td>';
			}if ($estado['dias_trabajados']<605 && $estado['dias_trabajados']>=0){
				echo '<td><span class= "exito">Vigente</span></td>';
			}
			
			echo '</tr>';
				
		}
	}
	
	?>
</table>

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();


	});
	
	$("#filtrar").submit(function(event){
		abrir($('#filtrar'),event, false);
	});
	
	
	</script>
