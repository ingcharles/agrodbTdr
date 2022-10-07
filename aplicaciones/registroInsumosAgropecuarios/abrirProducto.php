<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRIA.php';
require_once '../../clases/ControladorCatalogos.php';

$idProducto = htmlspecialchars($_GET['id'], ENT_NOQUOTES, 'UTF-8');

$conexion = new Conexion();
$cr = new ControladorRIA();
$cc = new ControladorCatalogos();

$producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
$fabricante = pg_fetch_assoc($cr->abrirFabricante($conexion, ($producto['id_fabricante'] == null ? 0 : $producto['id_fabricante'])));
$pais = pg_fetch_assoc($cr->abrirPais($conexion, ($producto['id_pais'] == null ? 0 : $producto['id_pais'])));
$empresa = pg_fetch_assoc($cr->abrirEmpresa($conexion, ($producto['id_operador'] == null ? 0 : $producto['id_operador'])));
$composicion = pg_fetch_assoc($cr->abrirComposicion($conexion, ($producto['id_composicion'] == null ? 0 : $producto['id_composicion'])));
$ingredientesFabricantes = $cr->listarIngredientesProducto($conexion, $idProducto);
$aditivos = $cr->listarAditivosProducto($conexion, $idProducto);
$usos = $cr->listarUsosProducto($conexion, $idProducto);
$codigo = $cr->obtenerCodigoProducto($conexion, $idProducto);
$url = 'http://' . $_SERVER['SERVER_NAME'] . '/agrodb/aplicaciones/general';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Panel de control GUIA</title>
    <script src="<?php echo $url; ?>/funciones/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="<?php echo $url; ?>/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
    <script src="<?php echo $url; ?>/funciones/agrdbfunc.js" type="text/javascript"></script>
    <script src="<?php echo $url; ?>/funciones/jquery.inputmask.js" type="text/javascript"></script>
    <script src="<?php echo $url; ?>/funciones/jquery.numeric.js"></script>
    <link rel='stylesheet' href='<?php echo $url; ?>/estilos/agrodb_papel.css'>
    <link rel='stylesheet' href='<?php echo $url; ?>/estilos/agrodb.css'>
    <link rel='stylesheet' href='estilos/estiloapp.css'>
    <link rel='stylesheet' href='<?php echo $url; ?>/estilos/jquery-ui-1.10.2.custom.css'>
</head>
<body class="ventanaExterna">
<div id="barra">
    <div id="estado"></div>
    <div id="codigoGenerado">Código: <?php echo $codigo; ?></div>
</div>
<header>
    <h1>Detalle de producto</h1>
</header>
    <fieldset id="datosGenerales">
        <legend>Datos generales</legend>
        <div data-linea="0">
            <label for="subtipo">Subtipo</label>
            <?php echo $producto['nombre_subtipo']; ?>
        </div>

        <div data-linea="1">
            <label for="nombre_comun">Nombre de producto</label>
            <?php echo $producto['nombre_comun']; ?>
        </div>
        <div data-linea="3">
            <label for="viaAdministracion">Vía de Administración</label>
            <?php echo $producto['via_administracion']; ?>
        </div>
        <hr/>
        <div data-linea="2">
            <label for="partida_arancelaria">Partida Arancelaria</label>
            <?php echo $producto['partida_arancelaria']; ?>
        </div>
        <div data-linea="2">
            <label for="codigo_arancel">Código VUE</label>
            <?php echo $producto['codigo_producto']; ?>
        </div>
        <hr/>
        <label>Origen del producto</label>
        <div data-linea="4" id="datosFabricanteProducto">
            <div>
                <label for="fabricanteProducto">Fabricante</label>
                <?php echo $fabricante['nombre']; ?>
            </div>
        </div>
        <div data-linea="5">
            <label for="idPaisProducto">País</label>
            <?php echo $pais['nombre']; ?>
        </div>
    </fieldset>

    <fieldset id="datosEmpresa">
        <legend>Empresa</legend>
        <div data-linea="4">
            <div>
                <label for="empresa">Identificación de empresa</label>
                <?php echo $empresa['identificador']; ?>
            </div>
            <div>
                <label for="empresa">Nombre de empresa</label>
                <?php echo $empresa['razon_social']; ?>
            </div>
        </div>


        <div data-linea="5" id="resultadosEmpresa">
        </div>
    </fieldset>

    <fieldset id="datosComposicion">
        <legend>Composición</legend>
        <div data-linea="4">
            <label for="composicion">Composición</label>
            <?php echo $composicion['nombre']; ?>
        </div>
    </fieldset>
    <fieldset id="fabricantes">
        <legend>Fabricante por ingrediente</legend>
        <div data-linea="1">
            <?php
            while ($ingredienteFabricante = pg_fetch_assoc($ingredientesFabricantes)) {
                echo "<div>" . $ingredienteFabricante['ingrediente_activo'] . ": " . $ingredienteFabricante['nombre_fabricante'] . " (" . $ingredienteFabricante['nombre_pais'] . ")</div>";
            }
            ?>
        </div>
    </fieldset>
    <fieldset id="aditivos">
        <legend>Aditivos</legend>
        <div data-linea="1">
            <?php
            while ($aditivo = pg_fetch_assoc($aditivos)) {
                echo "<div>" . $aditivo['nombre'] . " (" . $aditivo['concentracion'] . $aditivo['unidad_medida'] . ")</div>";
            }
            ?>
        </div>
    </fieldset>
    <fieldset id="usos">
        <legend>Declaración de usos</legend>
        <div data-linea="1">
            <table>
            <?php
            while ($uso = pg_fetch_assoc($usos)) {
                echo '<tr><td><span class="uso_como">' . $uso['nombre_uso'] . '</span><span class="uso_contra">' . $uso['nombre'] . '</span><span class="uso_para">' . $uso['aplicacion_producto'] . '</span><span class="uso_dosis">' . $uso['dosis'] . '</span><span class="uso_producto_consumo">' . $uso['producto_consumo'] . '</span><span class="uso_periodo">' . $uso['periodo'] . '</span></td></tr>';
            }
            ?>
            </table>
        </div>
    </fieldset>
</body>
<script>
    $('document').ready(function () {
        distribuirLineas();
    });
</script>
</html>