<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cfe = new ControladorFitosanitarioExportacion();
$cc = new ControladorCatalogos();

$qLocalizacion = $cc->listarLocalizacion($conexion,'PAIS');

if ($_POST['identificadorExportador']!=''){
	$identificadorExportador=$_POST['identificadorExportador'];
}

if($_POST['razonSocial']!='' ){
	$razonSocial=$_POST['razonSocial'];
}

if($_POST['pais']!='')
{
	$idPais=$_POST['pais'];
}
?>

<header>
	<h1>Lista Notificaciones CFE</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="notificacionesCFE" data-opcion="listaNotificacionCFE" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<?php echo '
		<table class="filtro" style="width: 400px;" >
		<tbody>
		<tr>
		<th colspan="3">Buscar Notificaciones:</th>
		</tr>
		<tr>
		<td>Número de Cédula/RUC:</td>
		<td> <input id="identificadorExportador" type="text" name="identificadorExportador" maxlength="13" value="'.  $_POST['identificadorExportador'] .'">	</td>
		</tr>
		<tr>
		<td>Razón social:</td>
		<td> <input id="razonSocial" type="text" name="razonSocial" maxlength="128" value="'. $_POST['razonSocial'] .'">	</td>
		</tr>
		<tr>
		<td>País:</td>
		<td> <select id="pais" name="pais" style="width:174px">
		<option value="0" >Seleccionar...</option>';
while ($fila = pg_fetch_assoc($qLocalizacion)){
					echo  '<option  value="'. $fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
				}
				echo '</select></td>
					</tr>
					<tr>
					<td id="mensajeError"></td>
					<td colspan="5"> <button id="buscar">Buscar</button>	</td>
					</tr>
					</tbody>
					</table> ';
				?>
		</form>
	</nav>
</header>
<header>
	<nav>
		<?php			
		$contador = 0;
		$itemsFiltrados[] = array();
		$ca = new ControladorAplicaciones();
		$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
		while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
		id="' . $fila['estilo'] . '"
		data-destino="detalleItem"
		data-opcion="' . $fila['pagina'] . '"
		data-rutaAplicacion="' . $fila['ruta'] . '"
		>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
			?>
	</nav>
</header>


<table id="listaNotificaciones">
	<thead>
		<tr>
			<th>#</th>
			<th># CFE</th>
			<th>Operador</th>
			<th>Fecha notificación</th>

		</tr>
	</thead>
	<?php 


	$qNotificaciones = $cfe->buscarNotificacionesXRucXRazonsocialXPais($conexion, $identificadorExportador, $razonSocial, $idPais);


	$contador = 0;

	if(pg_num_rows($qNotificaciones) == 0){
					//echo 'El usuario no tiene notificaciones registradas.';
				}else{
					while($notificaciones = pg_fetch_assoc($qNotificaciones)){
						echo '<tr 	id="'.$notificaciones['id_notificacion'].'"
		class="item"
		data-rutaAplicacion="notificacionesCFE"
		data-opcion="abrirNotificacionCFE"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem"
		>
		<td>'.++$contador.'</td>
			<td>'.$notificaciones['numero_cfe'].'</td>
			<td>'.$notificaciones['identificador_exportador'].'</td>
			<td>'.substr($notificaciones['fecha_notificacion'],0,10).'</td>

				</tr>';
							}
					}

					?>
</table>


<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});


	$("#filtrar").submit(function(event){
		event.preventDefault();
		abrir($('#filtrar'),event, false);
	});
</script>
