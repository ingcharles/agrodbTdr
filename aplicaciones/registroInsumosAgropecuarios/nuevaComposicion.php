<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $areas = array('IAV', 'IAP');
    $categorias_toxicologicas = $cr->listarCategoriasToxicologicas($conexion, $areas);
    $ingredientes = $cr->listarIngredientesActivos($conexion, $areas, 1);
    $usos = $cr->listarUsos($conexion, $areas);
    $unidades = $cr->listarUnidadesMedida($conexion);
?>

    <header>
        <h1>Nueva composición</h1>
    </header>
    <div id="estado"></div>

    <form id="nuevaComposicion" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="guardarComposicion" data-accionEnExito="ACTUALIZAR">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="0">
                <label for="area">Área</label>
                <select id="area" name="area">
                    <option value="IAP">Registro de Insumos Agrícolas</option>
                    <option value="IAV">Registro de Insumos Pecuarios</option>
                </select>
            </div>
            <div data-linea="1">
                <label for="nombreComposicion">Nombre</label>
                <input id="nombreComposicion" name="nombreComposicion" type="text" readonly="readonly" />
            </div>
            <div data-linea="2">
                <label for="codificacion">Codificación</label>
                <input id="codificacion" name="codificacion" type="text" readonly="readonly" />
            </div>
            <div data-linea="3">
                <label for="idCategoriaToxicologica">Categoría toxicológica</label>
                <select id="idCategoriaToxicologica" name="idCategoriaToxicologica">
                    <?php
                        while($categoria = pg_fetch_assoc($categorias_toxicologicas)) {
                            echo '<option value="' . $categoria["id_categoria_toxicologica"] . '">' . $categoria["categoria_toxicologica"] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <button type="submit" class="guardar">Guardar</button>
            </div>
        </fieldset>
    </form>

    <form id="nuevoRegistro" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="nuevoComposicionIngredienteActivo" >
        <input id="idComposicion" name="idComposicion" type="hidden" />
        <fieldset id="formIngrediente" disabled="disabled">
            <legend>Ingrediente/Principio activo</legend>
            <div data-linea="1">
                <label for="idIngredienteActivo">Ingrediente</label>
                <select id="idIngredienteActivo" name="idIngredienteActivo">
                    <?php
                    $listado = '';
                    while($ingrediente = pg_fetch_assoc($ingredientes)) {
                        $listado .= '<option value="' . $ingrediente["id_ingrediente_activo"] . '">' . $ingrediente["ingrediente_activo"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div data-linea="2">
                <label for="concentracion">Concentración</label>
                <input id="concentracion" name="concentracion" type="text" />
            </div>
            <div data-linea="3">
                <label for="unidad">Unidad de medida</label>
                <select id="unidad" name="unidad"">
                    <?php
                    while($unidad = pg_fetch_assoc($unidades)) {
                        echo '<option value="' . $unidad["codigo"] . '">' . $unidad["codigo"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div data-linea="4">
                <label for="tipoExpendio">Tipo de expendio</label>
                <input id="tipoExpendio" name="tipoExpendio" type="text" />
            </div>
            <button type="submit" class="mas">Añadir ingrediente</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registros">
        </table>
    </fieldset>

    <form id="nuevoUso" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="nuevoComposicionUso" >
        <input id="idComposicion2" name="idComposicion2" type="hidden" />
        <fieldset id="formUso" disabled="disabled">
            <legend>Uso</legend>
            <div data-linea="1">
                <label for="idUso">Uso</label>
                <select id="idUso" name="idUso">
                    <?php
                    while($uso = pg_fetch_assoc($usos)) {
                        echo '<option value="' . $uso["id_uso"] . '">' . $uso["nombre_uso"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="mas">Añadir uso</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registrosUsos">
        </table>
    </fieldset>

<script>
    $('document').ready(function() {
        distribuirLineas();
        $("#idIngredienteActivo").html(<?php echo json_encode($listado); ?>);
    });

    $("#nuevaComposicion").submit(function(e) {
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#categoriaToxicologica").val() == "" ) {
            error = true;
            $("#categoriaToxicologica").addClass("alertaCombo");
        }

        if (!error){
            //abrir($(this), e, false);
            ejecutarJson($(this), new exitoCreacionComposicion());

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

    function exitoCreacionComposicion() {
        this.ejecutar = function (msg) {
            $("#formIngrediente").removeAttr("disabled");
            $("#formUso").removeAttr("disabled");
            $("#fs_detalle").attr("disabled", "disabled");
            $("#idComposicion").val(msg.idComposicion);
            $("#idComposicion2").val(msg.idComposicion);
            mostrarMensaje("Elemento creado", "EXITO");
        }
    }

    //INGREDIENTES ACTIVOS

    acciones(null, null, null, null, new exitoCreacionComposicionIngredienteActivo(), new exitoBorradoComposicionIngredienteActivo());

    function exitoCreacionComposicionIngredienteActivo() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado","EXITO");
            $("#nombreComposicion").val(msg.nombreComposicion);
            $("#listadoItems article#" + msg.idComposicion + " > span:not([class='ordinal'])").html(msg.nombreComposicion);
            var fila = msg.mensaje;
            $("#registros").append(fila);
            $("#nuevoRegistro fieldset input:not(:hidden,[data-resetear='no'])").val('');
            $("#nuevoRegistro fieldset textarea").text('');
        }
    }

    function exitoBorradoComposicionIngredienteActivo() {
        this.ejecutar = function (msg) {
            $("#nombreComposicion").val(msg.nombreComposicion);
            $("#listadoItems article#" + msg.idComposicion + " > span:not([class='ordinal'])").html(msg.nombreComposicion);
            $("#registros #R" + msg.mensaje).fadeOut("fast", function() {
                $(this).remove();
            });
            mostrarMensaje("Elemento borrado","EXITO");
        }
    }

    //USOS

    acciones('#nuevoUso', '#registrosUsos');

</script>