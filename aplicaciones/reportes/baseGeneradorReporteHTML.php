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
            text-align: left;
        }

        #tablaReporte th {
            text-align: left;
            padding-top: 5px;
            padding-bottom: 4px;
            background-color: #A7C942;
            color: #ffffff;
        }

        #tablaReporte th.lab {
            text-align: left;
            padding-top: 5px;
            padding-bottom: 4px;
            background-color: blue;
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


<h1><?= $tituloReporte ?></h1>
<h2>Período <?= $fechaInicio ?> - <?= $fechaFin ?></h2>
<div id="tabla">
    <table id="tablaReporte" class="soloImpresion">
        <thead>
        <?php
        echo $cr->construirEncabezadoReporte($conexion, $tablas, $campos);
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
                } else if($campo == 'coordenada_x' || $campo == 'coordenada_y' || $campo == 'coordenada_z' || $campo == 'longitud_imagen' || $campo == 'latitud_imagen' || $campo == 'altura_imagen'){
                    echo "<td class='formato'>" . $fila[$campo] . "</td>";
                } else{
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




