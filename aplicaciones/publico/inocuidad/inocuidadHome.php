<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 25/03/18
 * Time: 20:34
 */

require_once 'controladores/ControladorReportePublico.php';

$controladorReporte = new ControladorReportePublico();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../estilos/estiloapp.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="../../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jqBarGraph.1.1.js"></script>
</head>
<body>
    <div class="contenido">
        <div class="title">Sistema de Alerta Rápida (SIAR)</div>
        <div class="date">Al <?php echo date("d  m  y") ?></div>

        <div class="items">
            <ul>
                <li>Total Notificaciones: <strong id="totalNotificaciones">0</strong></li>
                <li>Provincias con Notificación: <strong id="totalProvincias">0</strong></li>
                <li>Contaminante más frecuente: <strong id="contaminanteFrecuente"></strong></li>
            </ul>
        </div>

        <div class="graficos">
            <div class="grafico">
                <div class="titulo-grafico">Resumen de notificaciones por provincia</div>
                <div id="notificacionesProvincia"></div>
            </div>
            <br>
            <div class="separator"></div>
            <br>
            <div class="grafico">
                <div class="titulo-grafico">Resumen de notificaciones por producto</div>
                <div id="notificacionesProducto"></div>
            </div>
            <br>
            <div class="separator"></div>
            <br>
            <div class="grafico">
                <div class="titulo-grafico">Resumen de notificaciones por origen</div>
                <div id="notificacionesOrigen"></div>
            </div>
            <br>
            <div class="separator"></div>
            <br>
            <div class="grafico">
                <div class="titulo-grafico">Resumen de Programas Nacional de vigilancia y Control </div>
                <div id="notificacionesPrograma"></div>
            </div>
            <div>
                &nbsp;
                <br>
                &nbsp;
                <br>
                &nbsp;
                <br>
                &nbsp;
                <br>
            </div>
        </div>
    </div>
    <script>

            <?php echo $controladorReporte->obtenerDatosPrincipales() ?>
            $('#totalNotificaciones').html(objDatosPrincipales.totalNotificaciones);
            $('#totalProvincias').html(objDatosPrincipales.totalProvincias);
            $('#contaminanteFrecuente').html(objDatosPrincipales.contaminanteFrecuente);


            arrayOfNotificacionesProvincia = new Array(
                <?php echo $controladorReporte->notificaciones("PROVINCIA") ?>
            );
            $('#notificacionesProvincia').jqBarGraph({
                data: arrayOfNotificacionesProvincia,
                colors: ['#FF985E','#4f81bc'] ,
                legends: ['2017','2018'],
                legend: true,
                type: 'multi',
                width: '100%'
            });

            arrayOfNotificacionesProducto = new Array(
                <?php echo $controladorReporte->notificaciones("PRODUCTO") ?>
            );
            $('#notificacionesProducto').jqBarGraph({
                data: arrayOfNotificacionesProducto,
                colors: ['#8EB349','#c0504e'] ,
                legends: ['2017','2018'],
                legend: true,
                type: 'multi',
                width: '100%'
            });

            arrayOfNotificacionesOrigen = new Array(
                <?php echo $controladorReporte->notificaciones("ORIGEN") ?>
            );
            $('#notificacionesOrigen').jqBarGraph({
                data: arrayOfNotificacionesOrigen,
                colors: ['#6D4386','#9bbb58'] ,
                legends: ['2017','2018'],
                legend: true,
                type: 'multi',
                width: '100%'
            });

            //Replace: Programa Nacional de Vigilancia y Control de
            arrayOfProgramas = new Array(
                <?php echo $controladorReporte->notificaciones("PROGRAMA") ?>
            );
            $('#notificacionesPrograma').jqBarGraph({
                data: arrayOfProgramas,
                colors: ['#EED68C','#8064a1'] ,
                legends: ['2017','2018'],
                legend: true,
                type: 'multi',
                width: '100%'
            });
    </script>
</body>
</html>