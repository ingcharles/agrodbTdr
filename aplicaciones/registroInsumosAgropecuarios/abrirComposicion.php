<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $idComposicion = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $areas = array('IAV', 'IAP');
    $composicion = pg_fetch_assoc($cr->abrirComposicion($conexion, $idComposicion));
    $composicionIngredienteActivo = $cr->listarComposicionIngredienteActivo($conexion, $idComposicion);
    $composicionUso = $cr->listarComposicionUsos($conexion, $idComposicion);
    $categorias_toxicologicas = $cr->listarCategoriasToxicologicas($conexion, $areas);
    $ingredientes = $cr->listarIngredientesActivos($conexion, $areas, 1);
    $usos = $cr->listarUsos($conexion, $areas);
    $unidades = $cr->listarUnidadesMedida($conexion);
?>

    <header>
        <h1>Detalle de composicion</h1>
    </header>
    <div id="estado"></div>

    <form id="detalleComposicion" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="modificarComposicion" data-accionEnExito="ACTUALIZAR">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="0">
                <label for="idComposicionCab">Código del sistema</label>
                <input id="idComposicionCab" name="idComposicionCab" type="text" readonly="readonly" value="<?php echo $composicion['id_composicion'] ?>" />
            </div>
            <div data-linea="1">
                <label for="area">Área</label>
                <select id="area" name="area">
                     <option value="IAP">Registro de Insumos Agrícolas</option>
                    <option value="IAV">Registro de Insumos Pecuarios</option>
                </select>
            </div>
            <div data-linea="2">
                <label for="nombreComposicion">Nombre</label>
                <input id="nombreComposicion" name="nombreComposicion" type="text" readonly="readonly" value="<?php echo $composicion['nombre'] ?>" />
            </div>
            <div data-linea="3">
                <label for="codificacion">Codificación</label>
                <input id="codificacion" name="codificacion" type="text" readonly="readonly" value="<?php echo $composicion['codificacion'] ?>" />
            </div>
            <div data-linea="4">
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
                <button type="submit" class="guardar">Actualizar</button>
            </div>
        </fieldset>
    </form>
    <form id="nuevoRegistro" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="nuevoComposicionIngredienteActivo" >
        <input id="idComposicion" name="idComposicion" type="hidden" value="<?php echo $idComposicion ?>"/>
        <fieldset>
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
                <label for="restriccion">Restricción comercial</label>
                <select id="restriccion" name="restriccion">
                    <option value="Ninguna - Venta libre">Ninguna - Venta libre</option>
                    <option value="Venta bajo receta">Venta bajo receta</option>
                    <option value="Venta bajo receta retenida">Venta bajo receta retenida</option>
                    <option value="Venta aplicada">Venta aplicada</option>
                </select>
            </div>
            <button type="submit" class="mas">Añadir ingrediente</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registros">
            <?php
            while ($ingredienteActivo = pg_fetch_assoc($composicionIngredienteActivo)) {
                $restriccion = $ingredienteActivo['restriccion'];
                echo $cr->imprimirLineaComposicionIngredienteActivo($ingredienteActivo['id_composicion'] . '_' .$ingredienteActivo['id_ingrediente_activo'], $ingredienteActivo['ingrediente_activo'] . ' (' . $ingredienteActivo['concentracion'] . ' ' . $ingredienteActivo['unidad_medida'] . ') - ' . $restriccion , $idComposicion);
            }
            ?>
        </table>
    </fieldset>

    <form id="nuevoUso" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="nuevoComposicionUso" >
        <input id="idComposicion2" name="idComposicion2" type="hidden" value="<?php echo $idComposicion ?>"/>
        <fieldset>
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
            <?php
            while ($uso = pg_fetch_assoc($composicionUso)) {
                echo $cr->imprimirLineaComposicionUso($uso['id_composicion'] . '_' .$uso['id_uso'], $uso['nombre_uso']);
            }
            ?>
        </table>
    </fieldset>

    <script>
        $('document').ready(function(){
            distribuirLineas();
            cargarValorDefecto("area", "<?php echo $composicion['id_area']; ?>");
            cargarValorDefecto("idCategoriaToxicologica", "<?php echo $composicion['id_categoria_toxicologica']; ?>");
            cargarValorDefecto("estadoComposicion", "<?php echo $composicion['estado']; ?>");
            $("#idIngredienteActivo").html(<?php echo json_encode($listado); ?>);
        });

        $("#detalleComposicion").submit(function(e){
            e.preventDefault();

            $(".alertaCombo").removeClass("alertaCombo");
            var error = false;

            if ($("#categoriaToxicologica").val() == "") {
                error = true;
                $("#estadoSubtipo").addClass("alertaCombo");
            }

            if ($("#estadoComposicion").val() == "") {
                error = true;
                $("#estadoSubtipo").addClass("alertaCombo");
            }

            if (!error) {
                ejecutarJson($(this), new exitoCreacionComposicion());
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }

        });

        function exitoCreacionComposicion() {
            this.ejecutar = function (msg) {
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