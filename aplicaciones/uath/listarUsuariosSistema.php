<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';


$conexion = new Conexion();
$ca = new ControladorAplicaciones('uath');
$opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

?>

<header>

    <h1>Usuarios</h1>
    <nav>
        <form id="filtrar" data-rutaAplicacion="uath" data-opcion="listarUsuariosSistemaFiltrado" data-destino="listadoFiltrado">
            <table class="filtro" style='width: 400px;'>
                <tbody>
                <tr>
                    <th colspan="3">Buscar usuario:</th>
                </tr>
                <tr>
                    <td>Número de Cédula:</td>
                    <td><input id="identificador" type="text" name="identificador" maxlength="10"
                               value="<?php echo $_POST['identificador']; ?>"></td>
                </tr>
                <tr>
                    <td>Apellido:</td>
                    <td><input id="apellidos" type="text" name="apellidos" maxlength="128"
                               value="<?php echo $_POST['apellido']; ?>"></td>
                </tr>
                <tr>
                    <td>Nombre:</td>
                    <td><input id="nombres" type="text" name="nombres" maxlength="128"
                               value="<?php echo $_POST['nombres']; ?>"></td>

                </tr>
                <tr>
                    <td id="mensajeError"></td>
                    <td colspan="5">
                        <button id='buscar'>Buscar</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </nav>
    <br/>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario);?>
</header>

<div id="listadoFiltrado">
    Ingrese parámetros de búsqueda
</div>

<script>
    $(document).ready(function () {
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $('#identificador').ForceNumericOnly();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un contrato para revisarlo.</div>');

    });


    $("#filtrar").submit(function (event) {
        event.preventDefault();
        var identificador = $.trim($('#identificador').val().length);
        var nombres = $.trim($('#nombres').val().length);
        var apellidos = $.trim($('#apellidos').val().length);
        if (identificador < 10 && identificador != 0 && nombres == 0 && apellidos == 0) {
            $('#mensajeError').html('<span class="alerta">La cédula ingresada no es válida!</span>');
        } else if (identificador == 10 || nombres != 0 || apellidos != 0) {
            $('#mensajeError').html('');
            abrir($(this), event, false);
        } else {
            $('#mensajeError').html('<span class="alerta">Ingrese datos de búsqueda</span>');
        }

    });


</script>
