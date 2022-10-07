<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$res=$cc->listarRequerimientoRevisionIngreso($conexion);

while($fila = pg_fetch_assoc($res)){
	$contenido ='<article
		    		id="'.$fila['id_requerimiento'].'"
		    		class="item"
					data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="abrirRequerimientoSAA"
		    		ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
		    		<span><strong>Requerimiento</strong></span><br/>
					<span><small><strong>Tipo: </strong>'.$fila['nombre'].'</small></span><br/>
					<aside style="padding-left: 5px;" ><small>'	.$fila['fecha_registro'].'<br/>
					<strong>Estado: </strong>'	.$fila['estado'].'</small></aside>
				</article>';
	?>
	<script type="text/javascript">
		var contenido = <?php echo json_encode($contenido);?>;
		$("#listado div.elementos").append(contenido);
	</script>
	<?php	
}
?>
<header>
	<h1>Administración de Requerimientos de Revisión - Ingreso</h1>
	<nav>
		<a id="_nuevo" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="nuevoRequerimientoSAA" data-destino="detalleItem" href="#">Nuevo</a>
		<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="listaRequerimientoSAA" data-destino="listadoItems" href="#">Actualizar</a>
		<a id="_seleccionar" data-rutaaplicacion="serviciosInformacionTecnica" href="#"><?php echo '<div id="cantidadItemsSeleccionados">0</div>'; ?>Seleccionar</a>
		<a id="_eliminar" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="notificarRequerimientoSAA" data-destino="detalleItem" href="#">Eliminar</a>				
	</nav>
</header>

<div id="listado">	
	<div class="elementos"></div>
</div>
<script>			
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		
	});
	$("#_eliminar").click(function(event){
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro a la vez").addClass('alerta');
				return false;
		}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro").addClass('alerta');
			return false;
		}
	});
</script>
