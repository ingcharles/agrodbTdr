<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 24/01/18
 * Time: 22:12
 */

?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">
</head>
<body>
<header>
    <h1>Configuración</h1>
</header>
    <div class="container">
        <br>
        <br>
        <h3 class="submenu-title">Configuración de Catálogos del Sistema</h3>
        <br>
        <br>
        <nav class="submenu-nav">
            <a class="submenu" id="_admin-insumos"
               href="#"
               data-destino="areaTrabajo #listadoItems"
               data-opcion="vistas/adminInsumos"
               data-rutaaplicacion="inocuidad">
                <div class="submenu-text">
                    <label>Administración Insumos</label>
                    <i class="material-icons">extension</i>
                </div>
            </a>
        </nav>

        <nav class="submenu-nav">
            <a class="submenu" id="_admin-lmr"
               href="#"
               data-destino="areaTrabajo #listadoItems"
               data-opcion="vistas/adminLmrs"
               data-rutaaplicacion="inocuidad">
                <div class="submenu-text">
                    <label>Administración LMRs</label>
                    <i class="material-icons">language</i>
                </div>
            </a>
        </nav>

        <nav class="submenu-nav">
            <a class="submenu" id="_admin-productos"
               href="#"
               data-destino="areaTrabajo #listadoItems"
               data-opcion="vistas/adminProductos"
               data-rutaaplicacion="inocuidad">
                <div class="submenu-text">
                    <label>Administración Productos</label>
                    <i class="material-icons">shopping_basket</i>
                </div>
            </a>
        </nav>
        
    </div>
<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("programas");
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una ficha para editarla.</div>');
    });
</script>
</body>
</html>