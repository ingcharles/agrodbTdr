<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$res=$cc->listarEnfermedadesAnimales($conexion);

while($fila = pg_fetch_assoc($res)){
	$contenido ='<article
		    		id="'.$fila['id_enfermedad'].'"
		    		class="item"
					data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="abrirEnfermedadAnimalSAA"
		    		ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
		    		<span><strong>Enfermedades</strong></span><br/>
					<span>'.$fila['nombre'].'</span><br/>
			
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
	<h1>Administración de Enfermedades Animales</h1>
	<nav>
		<a id="_nuevo" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="nuevoEnfermedadAnimalSAA" data-destino="detalleItem" href="#">Nuevo</a>
		<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="listaEnfermedadAnimalSAA" data-destino="listadoItems" href="#">Actualizar</a>
		<a id="_seleccionar" data-rutaaplicacion="serviciosInformacionTecnica" href="#"><?php echo '<div id="cantidadItemsSeleccionados">0</div>'; ?>Seleccionar</a>
		<a id="_eliminar" data-rutaaplicacion="serviciosInformacionTecnica" data-opcion="notificarEnfermedadAnimalSAA" data-destino="detalleItem" href="#">Eliminar</a>				
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
		if($("#cantidadItemsSeleccionados").text()>1){	
			mostrarMensaje("Por favor seleccione un registro a la vez","FALLO");
			return false;
		}
		if($("#cantidadItemsSeleccionados").text()==0){
			mostrarMensaje("Por favor seleccione un registro","FALLO");
			return false;
		}
	});
	
</script>
