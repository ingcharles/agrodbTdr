<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportesCSV.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE_ORNAMENTALES_PROTOCOLO_ROYA_BLANCA.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cr = new ControladorReportesCSV();

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];

$campos = array(
    'numero_reporte',
    'fecha_inspeccion',
    'ruc',
    'razon_social',
    'sitio_produccion',
    'provincia',
    'canton',
    'parroquia',
    'pregunta1',
    'pregunta2',
    'pregunta3',
    'pregunta4',
    'pregunta5',
    'pregunta6',
    'pregunta7',
    'pregunta8',
    'pregunta9',
    'pregunta10',
    'pregunta11',
    'pregunta12',
    'pregunta13',
    'pregunta14',
    'pregunta15',
    'resultado',
    'observaciones',
    'usuario',
    'representante',
);

$res = $cr->generarReporteOrnamentalesProtocoloRoyaBlanca($conexion, $fechaInicio, $fechaFin);
?>


<html LANG="es">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">

    <style type="text/css">
        h1, h2 {
            margin: 0;
            padding: 0;
        }

        #tablaReporte {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            display: inline-block;
            width: auto;
            margin: 1em 0;
            padding: 0;
            border-collapse: collapse;
        }

        #tablaReporte td, #tablaReporte th {
            font-size: 1.2em;
            border: 1px solid #98bf21;
            padding: 3px 7px 2px 7px;
        }

        #tablaReporte th {
            text-align: left;
            padding-top: 5px;
            padding-bottom: 4px;
            background-color: #A7C942;
            color: #ffffff;
        }

        @page {
            margin: 5px;
        }

        .formato {
            mso-style-parent: style0;
            mso-number-format: "\@";
        }

        .formatoNumero {
            mso-style-parent: style0;
            mso-number-format: "0.000000";
        }

        .colorCelda {
            background-color: #FFE699;
        }

    </style>

</head>
<body>


<h1>Reporte de Ornamentales Protocolo Roya Blanca</h1>
<h2>Período <?= $fechaInicio ?> - <?= $fechaFin ?></h2>
<div id="tabla">
    <table id="tablaReporte" class="soloImpresion">
        <thead>
        <?php
        echo $cr->construirEncabezadoReporte($conexion, 'certificacionf05', $campos);
        ?>
        </thead>
        <tbody>
        <?php

        $var = 0;
        $auxPago = 0;
        $aux1Pago = 0;
        $auxColor = 'pintado';
        $auxImpresion = 0;

        While ($fila = pg_fetch_assoc($res)) {
            echo '<tr>';
            foreach ($campos as $campo) {
                if(substr($campo,0,3) == 'ruc'){
                    echo "<td>&nbsp;" . $fila[$campo] . "</td>";
                } else {
                    echo "<td>" . $fila[$campo] . "</td>";
                }            }
            echo '</tr>';
        }
        ?>

        </tbody>
    </table>

</div>
</body>
</html>



