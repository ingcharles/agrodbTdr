<?php

    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorFormularios.php';

    $idOperacion = $_POST['id'];

    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $cf = new ControladorFormularios();
    //$ca = new ControladorAuditoria();

    $operacion = pg_fetch_assoc($cc->obtenerOperacion($conexion, $idOperacion));
    $formulariosAsginados = $cf->cargarFormularios($conexion, $idOperacion);
    $formulariosDisponibles = $cf->obtenerFormulariosDisponibles($conexion, $idOperacion);

?>

<header>
    <h1>Formularios por operacion</h1>
</header>

<div id = "estado"></div>

<fieldset id = "fs_detalle">
    <legend>Detalle de Operación</legend>

    <div data-linea = "1">
        <label for = "codigo">Código:</label>
        <?php echo $operacion['id_area'] ?> - <?php echo $operacion['codigo'] ?>
    </div>

    <div data-linea = "2">
        <label for = "codigo">Nombre:</label>
        <?php echo $operacion['nombre'] ?>
    </div>

</fieldset>

<form id = "nuevoRegistro" data-rutaAplicacion = "formularios" data-opcion = "nuevaAsignacion">
    <input id = "operacion" name = "operacion" type = "hidden" value = "<?php echo $idOperacion ?>" />
    <input id = "nombreFormulario" name = "nombreFormulario" type = "hidden" />
    <fieldset>
        <legend>Formularios disponibles</legend>
        <div data-linea = "1">
            <label for = "formulario">Formularios</label>
            <select id = "formulario" name = "formulario">
                <?php
                    while ($formulario = pg_fetch_assoc($formulariosDisponibles)) {
                        echo '<option value="' . $formulario['id_formulario'] . '">' . $formulario['nombre'] . ' [' . $formulario['codigo'] . ']</option>';
                    }
                ?>
            </select>
            <button type = "submit" class = "mas">Agregar formulario</button>
        </div>

    </fieldset>
</form>
<fieldset>
    <table id = "registros">
        <?php
            while ($formulario = pg_fetch_assoc($formulariosAsginados)) {
                echo $cf->imprimirLineaFormulario($formulario['id_formulario_asociado'], $formulario['nombre'] . '[' . $formulario['codigo'] . ']');
            }
        ?>
    </table>
</fieldset>

<script>
    $('document').ready(function () {
        distribuirLineas();
        actualizarBotonesOrdenamiento();
        $('#nombreFormulario').val($('#formulario option:selected').text());
    });

    acciones(null,null,null,null,new exitoAsignacion(), new exitoDesasignacion);

    $('#formulario').change(function(){
        $('#nombreFormulario').val($('#formulario option:selected').text());
    });

    function exitoAsignacion(){
        this.ejecutar = function(msg){
            $("#formulario option:selected").remove();
            $('#nombreFormulario').val($('#formulario option:selected').text());
            var fila = msg.mensaje;
            $("#registros").append(fila);
            mostrarMensaje("Elemento asignado correctamente","EXITO");
        }
    }

    function exitoDesasignacion(){
        this.ejecutar = function(msg){
            var fila = msg.mensaje;
            $("#registros" + " #R" + fila).fadeOut("fast",function(){
                $(this).remove();
            });
            mostrarMensaje("Elemento removido correctamente","EXITO");
        }
    }

</script>
