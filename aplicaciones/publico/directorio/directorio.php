<?php 

	//header('Location: ../../../../agrodbOut.html');

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorAreas.php';
require_once '../../../clases/ControladorDirectorio.php';
require_once '../../../clases/GoogleAnalitica.php';

$conexion = new Conexion();
$cd = new ControladorDirectorio();
$ca = new ControladorAreas();

//$oficinas = $cd->obtenerOficinas($conexion);
$areas = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");


/*$qCategoriaPais = $cr->consultarCategoriaPais($conexion);

while($fila = pg_fetch_assoc($qCategoriaPais)){
$categoriaPais[]= array(idLocalizacion=>$fila['id_localizacion'], nombrePais=>$fila['nombre'],categoria=>$fila['tipo']);
}*/

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<link rel='stylesheet' href='../estilos/estiloapp.css'>
<script src="../../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
<script src="../../general/funciones/agrdbfunc.js" type="text/javascript"></script>
<script src="../../general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>

</head>
<body id="paginabusqueda">

	<section id="busqueda">
		<fieldset>
			<legend>Buscar por oficina</legend>
			<form id="buscarPorOficina" data-rutaAplicacion="../../../publico/directorio" data-opcion="listarDirectorioPorOficina" data-destino="resultados">
				<input type="hidden" id="categoriaArea" name="categoriaArea" />
				<select id="area" name="area">
					<option value="" data-categoria="">Seleccione una opción....</option>
					<?php 
						while($area = pg_fetch_assoc($areas)){
								
							if($area['id_area'] != 'DE'){
								echo '<option value="' . $area['id_area'] . '" data-categoria="' . $area['categoria_area'] . '">' . $area['nombre'] . '</option>' ;
							}							
						}
					?>
				</select>
				<button>Listar</button>
			</form>
		</fieldset>

		<fieldset>
			<legend>Buscar por apellido</legend>
			<form id="buscarPorApellido" data-rutaAplicacion="../../../publico/directorio" data-opcion="listarDirectorioPorOficina" data-destino="resultados">
				<input type="text" name="apellido">
				<button>Listar</button>
			</form>
		</fieldset>

		<div id="listado"></div>

		<div class="acerca">
			<p>Sistema Gestionador Unificado de Información</p>
			<p>Agrocalidad 2016</p>
			<p>Gestión Tecnológica</p>
		</div>
	</section>
	<section id="resultados">
		Ingrese los datos de busqueda en la parte izquierda.

	</section>

</body>

<script type="text/javascript">

$("#buscarPorOficina").submit(function(e){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	abrir($(this),e,false);
});

$("#buscarPorApellido").submit(function(e){
	abrir($(this),e,false);
});

/*var array_categoriaPais= <?php echo json_encode($categoriaPais); ?>;

$(document).ready(function(){
	
});

$("#categoria").change(function(){
	spais = '<option value="">Seleccione un país...</option>';
    for(var i=0;i<array_categoriaPais.length;i++){
	    if ($("#categoria").val()==array_categoriaPais[i]['categoria']){
	    	spais += '<option value="'+array_categoriaPais[i]['idLocalizacion']+'">'+array_categoriaPais[i]['nombrePais']+'</option>';
	    }
    }
    $('#pais').html(spais);
    $("#pais").removeAttr("disabled");
});

$("#pais").change(function(e){
	abrir($("#datosCategoriaProducto"),e,false);
});*/
	
</script>
</html>

