<?php
	session_start();
	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorAplicaciones.php';
	
	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once './clases/Perfil.php';
	require_once './clases/Flujo.php';

	$conexion = new Conexion();
	
	$ca = new ControladorAplicaciones();

	$identificador=$_SESSION['usuario'];


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Administración de catálogos</h1>
		<nav>
		<?php 
			
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
	<div id="configuracion">
		<h2>Catálogos de configuracion</h2>
		<div class="elementos"></div>
	</div>
	<div id="utiles">
		<h2>Utilitarios</h2>
		<div class="elementos"></div>
	</div>
	<div id="simple">
		<h2>Catálogos esenciales</h2>
		<div class="elementos"></div>
	</div>
	<div id="extendido">
		<h2>Catálogos específicos</h2>
		<div class="elementos"></div>
	</div>
	
	
	

	<?php

		$ce = new ControladorEnsayoEficacia();

		//Miro que perfil es el usuario logeado
		$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
		$perfil=new Perfil($perfiles);

		$tramites=array();
		$paginaSiguiente='';
		if($perfil->EsAnalistaCentral()){
			$fila=array('tipo'=>'configuracion','clase'=>'DIVISION','nombre'=>'Divisiones distritales','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'configuracion','clase'=>'EE_OPERA','nombre'=>'Operaciones del operador para Ensayos de Eficacia','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;

			$fila=array('tipo'=>'utiles','clase'=>'id_tecnicos_reconocido','nombre'=>'Técnicos reconocidos por la ANC','tabla'=>'g_ensayo_eficacia.tecnicos_reconocidos,identificador,nombres','pagina'=>'abrirCatalogoStandar');
			$tramites[]=$fila;

			$fila=array('tipo'=>'utiles','clase'=>'id_uso','nombre'=>'Plagas objeto de ensayo','tabla'=>'g_catalogos.usos,nombre_uso','where'=>'id_area;IAP','pagina'=>'abrirCatalogoStandar');
			$tramites[]=$fila;

			$fila=array('tipo'=>'utiles','clase'=>'id_uso','nombre'=>'Nombre común complejos fúngicos','tabla'=>'g_catalogos.usos,nombre_uso','where'=>'clasificacion;CF','pagina'=>'abrirCatalogoStandar');
			$tramites[]=$fila;

			$fila=array('tipo'=>'simple','clase'=>'P1C30','nombre'=>'Normas de implementación','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C1','nombre'=>'Objetivos de ensayos','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C2','nombre'=>'Motivos de ensayos','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			
			$fila=array('tipo'=>'simple','clase'=>'P1C9','nombre'=>'Condiciones del experimento','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C14','nombre'=>'Modo de acción','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C15','nombre'=>'Tipo de aplicación','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C16','nombre'=>'Tipo de equipo usado','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C17','nombre'=>'Cantidad de aplicaciones del plaguicida','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C20','nombre'=>'Equipo de protección','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C21','nombre'=>'Aplicación según estadío del insecto o ácaro','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C22','nombre'=>'Aplicación del fungicida','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C23','nombre'=>'Aplicación del herbicida','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C24','nombre'=>'Condición del suelo','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C25','nombre'=>'Condiciones ambientales','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C27','nombre'=>'Variables a evaluar','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C28','nombre'=>'Eficacia a evaluar','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1C29','nombre'=>'Información y evaluaciones adicionales que se remitirá en el informe final','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;

			$fila=array('tipo'=>'simple','clase'=>'P1C32','nombre'=>'Unidades de muestreo','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P1U1','nombre'=>'Diseño del experimento','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;

			$fila=array('tipo'=>'extendido','clase'=>'ANEXOS','nombre'=>'Tipos de anexos','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;


		}

		foreach($tramites as $key=>$fila)
		{
			$categoria = $fila['tipo'];

				$contenido = '<article
							id="'.$fila['clase'].'"
							data-flujo="'.$fila['tipo'].'"
							data-idOpcion="'.$fila['tabla'].'"
							data-nombre="'.$fila['nombre'].'"
							data-elementos="'.$fila['where'].'"


							class="item"
							data-rutaAplicacion="ensayoEficacia"
							data-opcion="'.$fila['pagina'].'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Catálogo')).'</span></br>

							<aside><small>'.$fila['tipo'].'</small></aside>
						</article>';
	?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+" div.elementos").append(contenido);
				</script>

	<?php 				
		}
	
    ?>	
</body>
<script>
$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#configuracion div> article").length == 0 ? $("#configuracion").remove():"";
		$("#utiles div> article").length == 0 ? $("#utiles").remove():"";		
		$("#simple div> article").length == 0 ? $("#simple").remove():"";
		$("#extendido div> article").length == 0 ? $("#extendido").remove():"";
		
	});

</script>
</html>