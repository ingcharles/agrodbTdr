<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../controladores/ControladorDashboard.php';
require_once '../../../clases/Constantes.php';

$constg = new Constantes();

$conexion = new Conexion();
$_SESSION['_ABSPATH_']=$_SERVER['DOCUMENT_ROOT'] . '/'.$constg::RUTA_APLICACION.'/';
$controladorDashboard = new ControladorDashboard();
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">
    <script src="aplicaciones/inocuidad/js/gauge.min.js"/>
    <script src="aplicaciones/inocuidad/js/jquery.dataTables.min.js"/>
    <link href="aplicaciones/inocuidad/estilos/jquery.dataTables.min.css" rel="stylesheet"></link>
</head>
<body>

<header>
    <h1>Panel de Control</h1>
    <nav>

        <?php
        $ca = new ControladorAplicaciones();
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

<div>
    <table style="width: 100%">
        <tr>
            <td style="text-align: center">
                <div id="recibidos_container">
                    <canvas id="gauge_recibidos"></canvas>
                    <div id="recibidos-textfield" style="font-size: 20px;"></div>
                </div>
            </td>
            <td style="text-align: center">
                <div id="despachados_container">
                    <canvas id="gauge_despachados"></canvas>
                    <div id="despachados-textfield" style="font-size: 20px;"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="font-size: 18px; text-align: center">Recibidos vs Atendidos</td>
            <td style="font-size: 18px; text-align: center">Atendidos vs Despachados</td>
        </tr>
        <tr>
            <table id="example" class="display" width="100%" data-page-length="25" data-order="[[ 1, &quot;asc&quot; ]]">
                <thead>
                <tr>
                    <th>Programa</th>
                    <th>Provincia/País</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th data-orderable="false">Estado</th>
                    <th>Usuario</th>
                    <th>Código</th>
                    <th data-orderable="false">Acciones</th>
                </tr>
                </thead>
                <tfoot>
               <tbody>
                <?php
                echo $controladorDashboard->listDashboard($_SESSION['usuario']);
                ?>

                </tbody>
            </table>
        </tr>
    </table>
</div>
<div id="dialogCancelar" title="Cancelar Registro" style="display: none">
    <p>Se dispone a cancelar el registro seleccionado</p>
    <label for="detalle_cancelacion">Detalle de la cancelación</label>
    <textarea id="detalle_cancelacion"></textarea>
</div>
<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("programas");
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Seleccione un elemento de la tabla para ver el detalle.</div>');

        $('#example').DataTable( {
            "pagingType": "full_numbers",
            "searchable":true,
            language: {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
    });

    <?php echo $controladorDashboard->recibidosVsDespachados()?>
</script>
<script src="aplicaciones/inocuidad/js/icHomeDashboard.js"/>

</body>
</html>