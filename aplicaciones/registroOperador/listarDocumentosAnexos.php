<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorAplicaciones.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $conexion = new Conexion();
    
    function reemplazarCaracteres($cadena){
    	
    	$cadena = str_replace('á', 'a', $cadena);
    	$cadena = str_replace('é', 'e', $cadena);
    	$cadena = str_replace('í', 'i', $cadena);
    	$cadena = str_replace('ó', 'o', $cadena);
    	$cadena = str_replace('ú', 'u', $cadena);
    	$cadena = str_replace('ñ', 'n', $cadena);
    
    	$cadena = str_replace('Á', 'A', $cadena);
    	$cadena = str_replace('É', 'E', $cadena);
    	$cadena = str_replace('Í', 'I', $cadena);
    	$cadena = str_replace('Ó', 'O', $cadena);
    	$cadena = str_replace('Ú', 'U', $cadena);
    	$cadena = str_replace('Ñ', 'N', $cadena);
    
    	return $cadena;
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<header>
    <h1>Proveedores</h1>
    <nav>

        <?php
            $ca = new ControladorAplicaciones('registroOperador', 'abrirDocumentoAnexo');
            $res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);

            while ($fila = pg_fetch_assoc($res)) {
                echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
            }
        ?>
    </nav>
</header>

<?php

    $cr = new ControladorRegistroOperador();

    $res = $cr->listarDocumentosAnexos($conexion, $_SESSION['usuario']);
    $contador = 0;
    while ($documento = pg_fetch_assoc($res)) {

		$descripcion = reemplazarCaracteres($documento['nombre_documento']);
		$descripcion = (strlen($descripcion)>50?(substr($descripcion,0,50).'...'):(strlen($descripcion)>0?$descripcion:'Sin descripción'));

        echo $ca->imprimirArticulo($documento['id'], ++$contador, $documento['descr'], $descripcion);

    }

?>

</body>
<script>
    $(document).ready(function () {
        //$("#listadoItems").removeClass("comunes");
        //$("#listadoItems").addClass("lista");
        $("#listadoItems").addClass("comunes");
    });

</script>
</html>