<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
$conexion = new Conexion();
?>
<header>
	<h1>Lista de eventos</h1>
	<nav>
		<?php			
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

 
 <div id="paginacion" class="normal">
 </div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Especie</th>
			<th>Tipo Certificado</th>
			<th>Certificado</th>
			<th>Fecha Registro</th>
			<th>Fecha Modificación</th>			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<?php

	$cm = new ControladorMovilizacionAnimal();
    $res = $cm->listaActivarCertificadosMovilizacion($conexion);
    $contador = 0;
    $itemsFiltrados[] = array();
    if (pg_num_rows($res)>0){
	while($fila = pg_fetch_assoc($res)){

       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_serie_documento'].'"
				class="item"
				data-rutaAplicacion="movilizacionAnimal"
				data-opcion="abrirActivarCertificadosMovilizacion"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
       			<td>'.$fila['nombre_especie'].'</td>
				<td>'.$fila['tipo_documento'].'</td>
				<td style="white-space:nowrap;"><b>'.$fila['numero_documento'].'</b></td>
				<td>'.$fila['fecha_registro'].'</td>
				<td>'.$fila['fecha_modificacion'].'</td>
			</tr>');
       	}
       	}else{
	echo "No hay resultados para la consulta";
	}
 ?>
 

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);			
	});

</script>