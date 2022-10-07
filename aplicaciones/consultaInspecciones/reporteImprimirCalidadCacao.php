<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsultaInspecciones.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE_CALIFICACION_LOTES_CACAO_GRANO.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cci = new ControladorConsultaInspecciones();

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$identificadorUsuario = $_POST['identificadorUsuario'];
$nombreUsuario = $_POST['nombreUsuario'];
$tipoFormulario = isset($_POST['tipoFormulario'])?$_POST['tipoFormulario']:null;

$campos = array(
    'numero_reporte',
    'fecha_inspeccion',
    'numero_reporte_inspeccion',
    'ruc',
    'exportador',
    'centro_acopio',
    'lote',
    'comprador',
    'calidad',
    'sacos',
    'vapor',
    'destino',
    'fecha_analisis',
    'muestra_inspector',
    'contra_muestra',
    'tipo_inspeccion',
    'tipo_cacao',
    'tipo_produccion',
    'inspeccion_adicional',
    'fermentacion',
    'fermentados',
    'grano_violeta',
    'grano_pizarroso',
    'mohos',
    'danados_insectos',
    'vulnerado',
    'trinitario',
    'multiples',
    'partidos',
    'plano_granza',
    'total_defectos',
    'impurezas_cacao',
    'materia_extrana',
    'peso_pepas',
    'pepas_gramos',
    'humedad',
    'medidor_humedad',
    'balanza_utilizada',
    'representante',
    'observaciones',
    'usuario',
);

$res = $cci->generarReporteCalificacionLotesCacaoGrano($conexion, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario, $tipoFormulario);
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


<h1>Reporte de Calificación de Lotes de Cacao en Grano</h1>
<h2>Período <?= $fechaInicio ?> - <?= $fechaFin ?></h2>
<div id="tabla">
    <table id="tablaReporte" class="soloImpresion">
        <thead>
        <?php
        echo $cci->construirEncabezadoReporte($conexion, 'certificacionf10', $campos);
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
                }
            }
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>

</div>
</body>
</html>