<?php 

	//header('Location: ../../../../agrodbOut.html');

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorDirectorio.php';
require_once '../../../clases/GoogleAnalitica.php';

$conexion = new Conexion();
$cd = new ControladorDirectorio();

$oficinas = $cd->obtenerOficinas($conexion);


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
<script src="../../general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="../../general/funciones/agrdbfunc.js"
	type="text/javascript"></script>
<script src="../../general/funciones/jquery-ui-1.10.2.custom.js"
	type="text/javascript"></script>

</head>
<body id="paginabusqueda">

	<section id="busqueda">
		<fieldset>
			<form id="buscarResoluciones"
				data-rutaAplicacion="../../../publico/resoluciones"
				data-opcion="buscarResoluciones" data-destino="resultados">
				<label for="numero">Número de resolución</label> <input id="numero"
					name="numero" type="text" /> <label for="numero">Nombre de
					resolución</label> <input id="nombre" name="nombre" type="text" />
				<label for="numero">Palabras clave</label> <input
					id="palabras_clave" name="palabras_clave" type="text" />
				<hr />
				<label for="numero">Fecha de inicio</label> <input id="fecha1"
					name="fecha1" type="text" /> <label for="numero">Fecha de fin</label>
				<input id="fecha2" name="fecha2" type="text" />
				<hr />
				<button>Buscar</button>
			</form>
		</fieldset>

		<div id="listado"></div>


		<div class="acerca">
			<p>Sistema Gestionador Unificado de Información</p>
			<p>Agrocalidad 2013</p>
			<p>Gestión Tecnológica</p>
		</div>
	</section>
	<section id="resultados">
			Ingrese los datos de busqueda en la parte izquierda.
	

	</section>

</body>

<script type="text/javascript">

$("#buscarResoluciones").submit(function(e){
	abrir($(this),e,false);
});

$(document).ready(function(){
	$( "#fecha1" ).datepicker({
	    changeMonth: true,
	    changeYear: true
	  });

	$( "#fecha2" ).datepicker({
	    changeMonth: true,
	    changeYear: true
	  });
});



	
</script>
</html>

