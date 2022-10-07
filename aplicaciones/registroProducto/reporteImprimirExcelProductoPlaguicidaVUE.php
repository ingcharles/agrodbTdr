<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';

$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');

$bProducto = false;
$bProductoInocuidad = false;
$bPartidas = false;
$mensaje = "Debe completar la siguiente información para crear el reporte: ";

$conexion = new Conexion();
$cr = new ControladorRequisitos();
$cc = new ControladorCatalogos();

//Producto
if(pg_num_rows($cr->abrirProducto($conexion, $idProducto)) != 0){
    $producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
    
    if($producto['nombre_comun']!=''){
        $bProducto = true;
    }else{
        $bProducto = false;
        $mensaje .= "nombre del producto, ";
    }
}else{
    $bProducto = false;
}

//Producto Inocuidad
if(pg_num_rows($cr->buscarProductoInocuidad($conexion,$idProducto)) != 0){
    $productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProducto));
    
    if(($productoInocuidad['formulacion']!='') && ($productoInocuidad['numero_registro']!='') &&
        ($productoInocuidad['categoria_toxicologica']!='') && ($productoInocuidad['fecha_registro']!='') &&
        ($productoInocuidad['id_operador']!='') && ($productoInocuidad['declaracion_venta']!='') &&
        ($productoInocuidad['razon_social']!='') && ($productoInocuidad['estabilidad']!='')){
            $bProductoInocuidad = true;
    }else{
        $bProductoInocuidad = false;
        $mensaje .= "formulación, num registro, categoría toxicológica, fecha registro, operador, razón social, declaración de venta, estabilidad, ";
    }
}else{
    $bProductoInocuidad = false;
}

//Partidas, Códigos Comp Supl, Presentaciones
if(pg_num_rows($cr->listarPartidasCodigosPresentaciones($conexion, $idProducto)) != 0){
    $bPartidas = true;
}else{
    $bPartidas = false;
    $mensaje .= "partidas arancelarias, códigos complementarios/suplementarios y presentaciones del producto.";
}

//Generar reporte
if($bProducto === true && $bProductoInocuidad === true && $bPartidas === true){
    $ext   = '.xls';
    $idProducto = $_POST['idProducto'];
    
    $nomReporte = 'Producto' . $idProducto . $ext;
    
    //indicamos al navegador que se está devolviendo un archivo
    //header("Content-type: application/octet-stream");
    //header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".$nomReporte."");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo '
                <html LANG="es">
                    <head>
                        <meta http-equiv="Content-Type" content="vnd.ms-excel;charset=utf-8">
                        <style type="text/css">
                            .formato {
                            	mso-style-parent: style0;
                            	mso-number-format: "\@";
                            }
                        </style>
                    </head>
                    <body>
                    	<div id="tabla">
                    		<table id="tablaReporteVacunaAnimal" class="soloImpresion">
                    			<thead>
                    				<tr>
                    					<th style="text-align:center;">Subpartida Arancelaria</th>
                    					<th style="text-align:center;">Codigo de Producto</th>
                    					<th style="text-align:center;">Descripcion de Producto</th>
                    					<th style="text-align:center;">Utilizacion</th>
                    				</tr>
                                    <tr>
                    					<th style="text-align:center;">-</th>
                    					<th style="text-align:center;">-</th>
                    					<th style="text-align:center;">-</th>
                    					<th style="text-align:center;">-</th>
                    				</tr>
                                    <tr>
                    					<th style="text-align:center;">prdt_hc</th>
                    					<th style="text-align:center;">prdt_cd</th>
                    					<th style="text-align:center;">prdt_desc</th>
                    					<th style="text-align:center;">use_fg</th>
                    				</tr>
                    			</thead>
                    			<tbody>
             ';
    $registros = null;
    $res = $cc-> reporteProductoPlaguicidaVUE($conexion, $idProducto);
    
    if(pg_num_rows($res) > 0){
        while ($registros = pg_fetch_assoc($res)) {
            echo '<tr>';
            echo '    <td class="formato">'.$registros['partida_arancelaria'].$registros['codigo_complementario'].$registros['codigo_suplementario'].'</td>';
            echo '    <td class="formato">A'.$registros['codigo_producto'].'</td>';
            echo '    <td class="formato">'.$registros['nombre_comun'].'</td>';
            echo '    <td class="formato">S</td>';
            echo '</tr>';
            
            $presentaciones = null;
            $res1 = $cc-> reporteProductoPresentacionPlaguicidaVUE($conexion, $idProducto, $registros['partida_arancelaria'], $registros['codigo_complementario'], $registros['codigo_suplementario']);
            
            if(pg_num_rows($res1) > 0){
                while ($presentaciones = pg_fetch_assoc($res1)) {
                    echo '<tr>';
                    echo '    <td class="formato">'.$presentaciones['partida_arancelaria'].$presentaciones['codigo_complementario'].$presentaciones['codigo_suplementario'].'</td>';
                    echo '    <td class="formato">A'.$presentaciones['codigo_producto'].$presentaciones['codigo_presentacion'].'</td>';
                    echo '    <td class="formato">'.$presentaciones['nombre_comun'].';'.$presentaciones['presentacion'].' '.$presentaciones['unidad'].'</td>';
                    echo '    <td class="formato">S</td>';
                    echo '</tr>';
                }
            }
        }
    }
    
    echo '
                    			</tbody>
                    		</table>
                    	</div>
                	</body>
                </html>
            ';
}else{
    echo $mensaje;
}
?>