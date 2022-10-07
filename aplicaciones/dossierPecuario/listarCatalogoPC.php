<?php
	session_start();
	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	
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
	<div id="esencial">
		<h2>Esenciales</h2>
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


		$tramites=array();


			$fila=array('tipo'=>'configuracion','clase'=>'DIVISION','nombre'=>'Divisiones distritales','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'configuracion','clase'=>'DP_OPERA','nombre'=>'Operaciones del operador para Ensayos de Eficacia','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;

			$fila=array('tipo'=>'esencial','clase'=>'id_clasificacion_subtipo','nombre'=>'Clasificación de subtipos de productos','tabla'=>'g_dossier_pecuario.clasificacion_subtipos','pagina'=>'abrirCatalogoClasificacion');
			$tramites[]=$fila;
			$fila=array('tipo'=>'esencial','clase'=>'id_especie_consumible','nombre'=>'Especies consumibles','tabla'=>'g_dossier_pecuario.especie_consumibles','pagina'=>'abrirCatalogoEspecies');
			$tramites[]=$fila;
			$fila=array('tipo'=>'esencial','clase'=>'id_fabricante_extranjero','nombre'=>'Fabricantes extranjeros','tabla'=>'g_dossier_pecuario.fabricantes_extranjeros','pagina'=>'abrirCatalogoExtranjeros');
			$tramites[]=$fila;
			$fila=array('tipo'=>'esencial','clase'=>'id_ingrediente_activo_grupo','nombre'=>'Agrupación de ingredientes activos','tabla'=>'g_dossier_pecuario.ingrediente_activo_grupos','pagina'=>'abrirCatalogoGrupoIA');
			$tramites[]=$fila;
			$fila=array('tipo'=>'esencial','clase'=>'id_subtipo_producto_grupo','nombre'=>'Agrupación de sub tipos de productos','tabla'=>'g_dossier_pecuario.subtipo_producto_grupos','pagina'=>'abrirCatalogoGrupoSubTipo');
			$tramites[]=$fila;



			$fila=array('tipo'=>'simple','clase'=>'IA_GRUPO','nombre'=>'Grupos de ingredientes activos','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P_ESP_FA','nombre'=>'Tipos de familias pecuarias','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P_TIEMPO','nombre'=>'Períodos de tiempo','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P_VIA','nombre'=>'Vías de administración','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'P4C07','nombre'=>'Efectos biológicos no deseados','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'simple','clase'=>'PPC-06','nombre'=>'Productos consumibles','tabla'=>'g_catalogos.catalogo_ef','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;

			
			$fila=array('tipo'=>'extendido','clase'=>'ANEXO_PC','nombre'=>'Tipos de anexos para pecuarios','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'extendido','clase'=>'P_DOSIS','nombre'=>'Configuración de visualizar dosis','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'extendido','clase'=>'P_ESPECI','nombre'=>'Especies pecuarias','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'extendido','clase'=>'P_USOS','nombre'=>'Configuración de usos por subtipo de producto','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;
			$fila=array('tipo'=>'extendido','clase'=>'PC_DE_VE','nombre'=>'Restricciones para venta','tabla'=>'g_catalogos.catalogo_ef_ex','pagina'=>'abrirCatalogoSimple');
			$tramites[]=$fila;



		foreach($tramites as $key=>$fila)
		{
			$modulo='';
			$categoria = $fila['tipo'];
			switch($categoria){
				case 'esencial':
					$modulo='dossierPecuario';
					break;
				default:
					$modulo='ensayoEficacia';
					break;
			}

				$contenido = '<article
							id="'.$fila['clase'].'"
							data-flujo="'.$fila['tipo'].'"
							data-idOpcion="'.$fila['tabla'].'"
							data-nombre="'.$fila['nombre'].'"

							class="item"
							data-rutaAplicacion="'.$modulo.'"
							data-opcion="'.$fila['pagina'].'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['nombre'])>45?(mb_substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Catálogo')).'</span></br>

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
		$("#esencial div> article").length == 0 ? $("#esencial").remove():"";	
		
		$("#simple div> article").length == 0 ? $("#simple").remove():"";
		$("#extendido div> article").length == 0 ? $("#extendido").remove():"";
		
	});

</script>
</html>