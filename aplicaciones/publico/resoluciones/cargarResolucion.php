
<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorResoluciones.php';

$conexion = new Conexion();
$cr = new ControladorResoluciones();

$resolucion = htmlspecialchars($_GET['resolucion']);

$detalleResolucion = pg_fetch_assoc($cr->abrirResolucion($conexion, $resolucion));

$documento = $cr->cargarResolucion($conexion, $resolucion);

$estructuraDeDocumento = array();
$contenidoDeDocumento = array();

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
<body>

	<section id="indice"></section>
	<section id="resolucion">
		<header>
			<div id="detalle">
				Resolución
				<?php echo $detalleResolucion['numero_resolucion']?>
				del
				<?php echo $detalleResolucion['fecha']?>
			</div>
			<div id="nombre">
				<?php echo $detalleResolucion['nombre']?>
			</div>
			<div id="estado">
				<?php echo $detalleResolucion['estado']?>
			</div>
			<span class="archivo1"><a
				href="../../../<?php echo $detalleResolucion['ruta_archivo']?>" download="resolucion.pdf">[Descargar
					resolución]</a> </span> <span class="archivo2"><a
				href="../../../<?php echo $detalleResolucion['ruta_anexo']?>"  download="anexo.pdf">[Descargar anexo]</a>
			</span>

		</header>
		<section>
			<?php 

			while ($parte = pg_fetch_assoc($documento)){
			$estructuraDeDocumento[] = $parte['id_estructura_padre'];
			$contenidoDeDocumento[] = '<div id="' . $parte['id_estructura'] . '" class="' . $parte['nivel'] . '"><span class="nombre_seccion">' . $parte['nivel'] . ' ' . $parte['numero'] . '</span> ' . $parte['contenido'] . '<div class="contenido"></div></div>';
		}
		?>
		</section>
	</section>
</body>
<script type="text/javascript">
	var contenido = <?php echo json_encode($contenidoDeDocumento);?>;
	var estructura = <?php echo json_encode($estructuraDeDocumento);?>;

	imprimir(contenido,estructura, 0, "despues");

	function imprimir(contenido, estructura, elemento, modo){
		//verificar si hay elementos en el arreglo
		if (contenido[elemento] != null){
			//alert(estructura[elemento]);
			item = "";
			//determinar el elemento en el que se añadirá el código
			if (estructura[elemento] == null){
				item = "#resolucion section";
			} else {
				item = "#resolucion div#" + estructura[elemento] + " > .contenido";
			}
			//determinar si el elemento existe para añadir si no, continuar
			if ($(item).length > 0){
				if(modo == "despues")
					$(item).append(contenido[elemento]);
				else
					$(item).prepend(contenido[elemento]);
				estructura.splice(elemento,1);
				contenido.splice(elemento,1);
				imprimir(contenido, estructura,elemento,"despues");
			}else{
				imprimir(contenido, estructura,elemento+1,"despues");
				imprimir(contenido, estructura,elemento,"antes");
			}		
		} else
			return;
	}
</script>
</html>
