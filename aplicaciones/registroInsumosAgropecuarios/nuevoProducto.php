<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRIA.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRIA();
$cc = new ControladorCatalogos();


//traer la única composición
$tipos = $cr->listarTiposSubtipos($conexion, array('IAV', 'IAP'), 1);

//con la composición traer usos (cómo y para(+dosis))
//con producto traer sus usos (contra)
//traer aditivos
$aditivos = $cr->listarAditivos($conexion, array('IAV', 'IAP'), 1);

$unidades = $cr->listarUnidadesMedida($conexion);
$opcionesUnidades = "";
while ($unidad = pg_fetch_assoc($unidades)) {
    $opcionesUnidades .= '<option value="' . $unidad["codigo"] . '">' . $unidad["nombre"] . '</option>';
}

$paises = $cc->listarLocalizacion($conexion, 'PAIS');
$opcionesPaises = "";
while ($pais = pg_fetch_assoc($paises)) {
    $opcionesPaises .= '<option value="' . $pais["id_localizacion"] . '">' . $pais["nombre"] . '</option>';
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Panel de control GUIA</title>
    <script src="../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="../general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
    <script src="../general/funciones/agrdbfunc.js" type="text/javascript"></script>
    <script src="../general/funciones/jquery.inputmask.js" type="text/javascript"></script>
    <script src="../general/funciones/jquery.numeric.js"></script>
    <link rel='stylesheet' href='../general/estilos/agrodb_papel.css'>
    <link rel='stylesheet' href='../general/estilos/agrodb.css'>
    <link rel='stylesheet' href='estilos/estiloapp.css'>
    <link rel='stylesheet' href='../general/estilos/jquery-ui-1.10.2.custom.css'>
</head>
<body class="ventanaExterna">
<div id="barra">
    <div id="estado"></div>
    <div id="codigoGenerado"></div>
</div>
<header>
    <h1>Ingreso de nuevo producto</h1>
</header>

<fieldset>
<form
    id="guardarProducto"
    data-rutaAplicacion="../../registroInsumosAgropecuarios"
    data-opcion="guardarProducto"
    data-accionEnExito="">
    <fieldset id="datosGenerales">
        <legend>Datos generales</legend>
        <div data-linea="0">
            <label for="subtipo">Subtipo</label>
            <select id="subtipo" name="subtipo">
                <?php
                foreach ($tipos['array_to_json'] as $tipo) {
                    echo "<optgroup label='" . $tipo['nombre'] . "'>";
                    foreach ($tipo['array_to_json'] as $subtipo) {
                        echo "<option value='" . $subtipo['id_subtipo_producto'] . "'>" . $subtipo['nombre'] . "</option>";
                    }
                    echo "</optgroup>";
                }
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="nombre_comun">Nombre de producto</label>
            <input id="nombre_comun" name="nombre_comun" type="text"/>
        </div>
        <div data-linea="2">
            <label for="partida_arancelaria">Partida Arancelaria</label>
            <input id="partida_arancelaria" name="partida_arancelaria" type="text"/>
        </div>
        <div data-linea="3">
            <label for="viaAdministracion">Vía de Administración</label>
            <select id="viaAdministracion" name="viaAdministracion">
            <option value="Intravenosa">Intravenosa</option>
            <option value="Intramuscular">Intramuscular</option>
            <option value="Subcutánea">Subcutánea</option>
            <option value="Oral">Oral</option>
            <option value="Ocular">Ocular</option>
            <option value="Nasal">Nasal</option>
            <option value="Tópica">Tópica</option>
            <option value="Transdérmica">Transdérmica</option>
            <option value="Parenteral">Parenteral</option>
            <option value="N/A">No aplica</option>
            </select>
        </div>
        <hr/>
        <label>Origen del producto</label>

        <div data-linea="4" id="datosFabricanteProducto">
            <div>
                <label for="fabricanteProducto">Fabricante/Formulador</label>
                <input id="fabricanteProducto" name="fabricanteProducto" type="text" data-distribuir="no"
                       placeholder="Al menos 5 letras"/>
                <button
                    type="button"
                    class="buscarFabricanteProducto"
                    data-rutaAplicacion="../../registroInsumosAgropecuarios"
                    data-opcion="listarFabricantesPorCoincidencia"
                    data-destino="resultadosFabricanteProducto"
                    id="">Buscar coincidencias
                </button>
            </div>
            <div data-linea="4" id="resultadosFabricanteProducto">
            </div>
        </div>
        <div data-linea="5">
            <label for="idPaisProducto">País</label>
            <select id="idPaisProducto" name="idPaisProducto" data-distribuir="no">
                <?php
                echo $opcionesPaises;
                ?>
            </select>
        </div>
    </fieldset>

    <fieldset id="datosEmpresa">
        <legend>Empresa</legend>
        <div data-linea="4">
            <label for="empresa">Nombre de empresa</label>
            <input id="empresa" name="empresa" type="text" data-distribuir="no" placeholder="Al menos 5 letras"/>
            <button
                type="button"
                class="buscarEmpresa"
                data-rutaAplicacion="../../registroInsumosAgropecuarios"
                data-opcion="listarEmpresasPorCoincidencia"
                data-destino="resultadosEmpresa"
                id="">Buscar coincidencias
            </button>
        </div>


        <div data-linea="5" id="resultadosEmpresa">
        </div>
    </fieldset>

    <fieldset id="datosComposicion">
        <legend>Composición</legend>
        <div data-linea="4">
            <label for="composicion">Composición</label>
            <input id="composicion" name="composicion" type="text" data-distribuir="no"
                   placeholder="Al menos 5 letras"/>
            <button
                type="button"
                class="buscarComposicion"
                data-rutaAplicacion="../../registroInsumosAgropecuarios"
                data-opcion="listarComposicionesPorCoincidencia"
                data-destino="resultadosComposicion"
                id="">Buscar coincidencias
            </button>
        </div>
        <div data-linea="5" id="resultadosComposicion">
        </div>
    </fieldset>

    <div>
        <button id="guardar" type="submit" class="guardar">Guardar</button>
    </div>
</form>
</fieldset>


<fieldset>
<div id="fabricantes" style="display: none !important">
    <form id="nuevoFabricante" data-rutaAplicacion="../../registroInsumosAgropecuarios"
          data-opcion="nuevoFabricanteIngredienteProducto">
        <input name="idProducto" type="hidden"/>
        <input id="nombreFabricante" name="nombreFabricante" type="hidden"/>
        <input id="nombreIngredienteActivo" name="nombreIngredienteActivo" type="hidden"/>
        <input id="nombrePais" name="nombrePais" type="hidden"/>
        <fieldset id="formFabricante">
            <legend>Fabricante</legend>
            <div data-linea="1">
                <label for="idIngredienteActivo">Ingrediente activo</label>
                <select id="idIngredienteActivo" name="idIngredienteActivo">
                </select>
            </div>
            <div data-linea="2">
                <label for="idPais">País</label>
                <select id="idPais" name="idPais" data-distribuir="no">
                    <?php
                    echo $opcionesPaises;
                    ?>
                </select>
            </div>


            <div data-linea="3" id="datosFabricante">
                <label for="fabricante">Nombre de fabricante</label>
                <input id="fabricante" name="fabricante" type="text" data-distribuir="no"
                       placeholder="Al menos 5 letras"/>
                <button
                    type="button"
                    class="buscarFabricante"
                    data-rutaAplicacion="../../registroInsumosAgropecuarios"
                    data-opcion="listarFabricantesPorCoincidencia"
                    data-destino="resultadosFabricante"
                    id="">Buscar coincidencias
                </button>
                <div id="resultadosFabricante">
                </div>
            </div>


            <hr/>
            <button type="submit" class="mas">Añadir fabricante</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registrosFabricantes">
        </table>
    </fieldset>
</div>

<div id="aditivos" style="display: none !important">
    <form id="nuevoAditivo" data-rutaAplicacion="../../registroInsumosAgropecuarios" data-opcion="nuevoProductoAditivo">
        <input name="idProducto" type="hidden"/>
        <input id="nombreAditivo" name="nombreAditivo" type="hidden"/>
        <fieldset id="formAditivo">
            <legend>Aditivo</legend>
            <div data-linea="1">
                <label for="idAditivo">Aditivo</label>
                <select id="idAditivo" name="idAditivo">
                    <?php
                    while ($aditivo = pg_fetch_assoc($aditivos)) {
                        echo '<option value="' . $aditivo["id_aditivo"] . '">' . $aditivo["nombre"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div data-linea="2">
                <label for="concentracion">Concentración</label>
                <input id="concentracion" name="concentracion" type="text"/>
            </div>
            <div data-linea="3">
                <label for="unidad">Unidad de medida</label>
                <select id="unidad" name="unidad">
                    <?php echo $opcionesUnidades; ?>
                </select>
            </div>
            <button type="submit" class="mas">Añadir aditivo</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registrosAditivos">
        </table>
    </fieldset>
</div>

<div id="uso" style="display: none !important">
    <form id="nuevoUso" data-rutaAplicacion="../../registroInsumosAgropecuarios" data-opcion="nuevoProductoUso">
        <input name="idProducto" type="hidden"/>
        <input id="nombreUso" name="nombreUso" type="hidden"/>
        <input id="nombreEnfermedad" name="nombreEnfermedad" type="hidden"/>
        <input id="nombreAplicacion" name="nombreAplicacion" type="hidden"/>
        <fieldset id="formUso">
            <legend>Declaración de uso</legend>
            <div data-linea="1">
                <label for="idUso">Uso permitido</label>
                <select id="idUso" name="idUso">
                </select>
            </div>
            <div data-linea="2">
                <label for="dosis">Dosis</label>
                    <span>
                        <input id="dosis" name="dosis" type="text" data-distribuir="no"/>
                        <select id="unidad_dosis" name="unidad_dosis" data-distribuir="no">
                            <?php echo $opcionesUnidades; ?>
                        </select> /
                        <input id="dosis2" name="dosis2" type="text" data-distribuir="no"/>
                        <select id="unidad_dosis2" name="unidad_dosis2" data-distribuir="no">
                            <?php echo $opcionesUnidades; ?>
                        </select>
                    </span>
            </div>
            <hr/>
            <div data-linea="3" id="datosAplicacion">
                <div>
                    <label for="aplicacion">Aplicado a</label>
                    <input id="aplicacion" name="aplicacion" type="text" placeholder="Al menos 5 letras"
                           data-distribuir="no"/>
                    <button
                        type="button"
                        class="buscarAplicacion"
                        data-rutaAplicacion="../../registroInsumosAgropecuarios"
                        data-opcion="listarAplicacionesPorCoincidencia"
                        data-destino="resultadosAplicacion"
                        id="">Buscar coincidencias
                    </button>
                    <div id="resultadosAplicacion">
                    </div>
                </div>
            </div>


            <hr/>
            <div data-linea="4" id="datosEnfermedad">
                <label for="enfermedad">Plaga/Trastornos/Otros</label>
                <input id="enfermedad" name="enfermedad" type="text" placeholder="Al menos 5 letras"
                       data-distribuir="no"/>
                <button
                    type="button"
                    class="buscarEnfermedad"
                    data-rutaAplicacion="../../registroInsumosAgropecuarios"
                    data-opcion="listarEnfermedadesPorCoincidencia"
                    data-destino="resultadosEnfermedad"
                    id="">Buscar coincidencias
                </button>
                <div data-linea="5" id="resultadosEnfermedad">
                </div>
            </div>
            <hr/>
            <div data-linea="5">
                <label for="productoConsumo">Producto de consumo</label>
                <select id="productoConsumo" name="productoConsumo">
                    <option value="No aplica">No aplica</option>
                    <option value="Carne">Carne</option>
                    <option value="Huevo">Huevo</option>
                    <option value="Leche">Leche</option>
                </select>
            </div>
            <hr/>
            <div data-linea="6">
                <label for="periodo">Período retiro/carencia</label>
                <input id="periodo" name="periodo" type="text" data-distribuir="no"/>
                <select id="periodoUnidad" name="periodoUnidad" data-distribuir="no">
                    <option value="días">días</option>
                    <option value="semanas">semanas</option>
                </select>
            </div>
            <hr/>
            <button type="submit" class="mas">Añadir uso</button>
        </fieldset>
    </form>
    <fieldset>
        <table id="registrosProductoUsos">
        </table>
    </fieldset>
</div>
<div>

    <form
        id="finalizacion"
        data-rutaAplicacion="../../registroInsumosAgropecuarios"
        data-opcion="finalizarProducto"
        data-accionEnExito=""
        style="display: none !important;">
        <hr/>
        <input id="idProductoCreado" name="idProductoCreado" type="hidden">
        <button type="submit">Finalizar ingreso de producto</button>
    </form>
</div>
</fieldset>
</body>
<script>


$('document').ready(function () {

});

$("button.buscarFabricanteProducto").click(function () {
    if ($("#fabricanteProducto").val().length > 4) {
        $(this).attr("id", $("#fabricanteProducto").val());
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("button.buscarEmpresa").click(function () {
    if ($("#empresa").val().length > 4) {
        $(this).attr("id", $("#empresa").val());
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("button.buscarComposicion").click(function () {
    if ($("#composicion").val().length > 4) {
        $(this).attr("id", $("#composicion").val());
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("button.buscarFabricante").click(function () {
    if ($("#fabricante").val().length > 4) {
        $(this).attr("id", $("#fabricante").val());
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("button.buscarEnfermedad").click(function () {
    if ($("#enfermedad").val().length > 4) {
        $(this).attr("id", $("#enfermedad").val());
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("button.buscarAplicacion").click(function () {
    if ($("#aplicacion").val().length > 4) {
        $(this).attr("id", $("#aplicacion").val() +
            "|" + $("input[name='id_composicion']").attr("data-area"));
        abrir($(this), null, false);
    } else {
        mostrarMensaje("Ingrese al menos 5 letras para buscar las coincidencias.", "FALLO");
    }
});

$("form#guardarProducto").submit(function (e) {
    e.preventDefault();
    if ($("#fabricanteProducto").is(':focus')) {
        $("button.buscarFabricanteProducto").trigger("click");
    } else if ($("#empresa").is(':focus')) {
        $("button.buscarEmpresa").trigger("click");
    } else if ($("#composicion").is(':focus')) {
        $("button.buscarComposicion").trigger("click");
    } else {
        if (!verificarInputs()) {
            ejecutarJson($(this), new exitoGuardado());
            //new exitoGuardado().ejecutar('hola');
        } else {
            mostrarMensaje("Llene todos los datos del formulario", "FALLO");
        }
    }
});

$("#finalizacion").submit(function (e) {
    e.preventDefault();
    ejecutarJson($(this), new exitoFinalizacion());
});

function exitoFinalizacion() {
    this.ejecutar = function (msg) {
        mostrarMensaje(msg.mensaje, "EXITO");
    };
}

function verificarInputs() {
    var error = false;
    $(".alertaCombo").removeClass("alertaCombo");

    if ($("#nombre_comun").val().trim() == "") {
        $("#nombre_comun").addClass("alertaCombo");
        error = true;
    }
    if ($("#partida_arancelaria").val().trim() == "") {
        $("#partida_arancelaria").addClass("alertaCombo");
        error = true;
    }
    if ($("#viaAdministracion").val().trim() == "") {
        $("#viaAdministracion").addClass("alertaCombo");
        error = true;
    }
    if (!$("#guardarProducto input[name='idFabricante']").is(':checked')) {
        $("#datosFabricanteProducto").addClass("alertaCombo");
        error = true;
    }
    if (!$("input[name='id_empresa']").is(':checked')) {
        $("#datosEmpresa").addClass("alertaCombo");
        error = true;
    }
    if (!$("input[name='id_composicion']").is(':checked')) {
        $("#datosComposicion").addClass("alertaCombo");
        error = true;
    }
    return error;
}

function verificarInputsNuevoFabricante() {
    this.ejecutar = function () {

        var elemento = $("#resultadosFabricante input:checked");
        var indice = $("#resultadosFabricante input").index(elemento);

        $("#nombreFabricante").val($("#resultadosFabricante label").eq(indice).text().trim());
        $("#nombreIngredienteActivo").val($("#idIngredienteActivo option:selected").text());
        $("#nombrePais").val($("#idPais option:selected").text());

        var error = false;

        if ($("#fabricante").is(':focus')) {
            $("button.buscarFabricante").trigger("click");
            error = true;
        } else {
            $(".alertaCombo").removeClass("alertaCombo");
            if (!$("#nuevoFabricante input[name='idFabricante']").is(':checked')) {
                $("#datosFabricante").addClass("alertaCombo");
                error = true;
            }
        }
        return !error;
    };

    this.mensajeError = function () {
        if (!$("#fabricante").is(':focus')) {
            mostrarMensaje("Llene todos los datos del formulario", "FALLO");
        }
    }
}

function verificarInputsNuevoAditivo() {
    this.ejecutar = function () {

        $("#nombreAditivo").val($("#idAditivo option:selected").text());

        var error = false;

        $(".alertaCombo").removeClass("alertaCombo");

        if ($("#idAditivo").val() == "") {
            $("#idAditivo").addClass("alertaCombo");
            error = true;
        }
        if ($("#concentracion").val().trim() == "") {
            $("#concentracion").addClass("alertaCombo");
            error = true;
        }
        if ($("#unidad").val() == "") {
            $("#unidad").addClass("alertaCombo");
            error = true;
        }
        return !error;
    };

    this.mensajeError = function () {
        mostrarMensaje("Llene todos los datos del formulario", "FALLO");
    }
}

function verificarInputsNuevoUso() {
    this.ejecutar = function () {

        $("#nombreUso").val($("#idUso option:selected").text());

        var elemento = $("#resultadosEnfermedad input:checked");
        var indice = $("#resultadosEnfermedad input").index(elemento);
        $("#nombreEnfermedad").val($("#resultadosEnfermedad label").eq(indice).text().trim());

        elemento = $("#resultadosAplicacion input:checked");
        indice = $("#resultadosAplicacion input[type='radio']").index(elemento);
        $("#nombreAplicacion").val($("#resultadosAplicacion label").eq(indice).text().trim());

        var error = false;
        if ($("#enfermedad").is(':focus')) {
            $("button.buscarEnfermedad").trigger("click");
            error = true;
        } else if ($("#aplicacion").is(':focus')) {
            $("button.buscarAplicacion").trigger("click");
            error = true;
        } else {
            $(".alertaCombo").removeClass("alertaCombo");
            if ($("#idUso").val() == "") {
                $("#idUso").addClass("alertaCombo");
                error = true;
            }
            if ($("#aplicacion").val().trim() == "") {
                $("#aplicacion").addClass("alertaCombo");
                error = true;
            }
            if ($("#dosis").val().trim() == "") {
                $("#dosis").addClass("alertaCombo");
                error = true;
            }
            if ($("#dosis2").val().trim() == "") {
                $("#dosis2").addClass("alertaCombo");
                error = true;
            }
            if ($("#unidad_dosis").val() == "") {
                $("#unidad_dosis").addClass("alertaCombo");
                error = true;
            }
            if ($("#unidad_dosis2").val() == "") {
                $("#unidad_dosis2").addClass("alertaCombo");
                error = true;
            }
            if ($("#productoConsumo").val() == "") {
                $("#productoConsumo").addClass("alertaCombo");
                error = true;
            }

            if (!$("input[name='idEnfermedad']").is(':checked')) {
                $("#datosEnfermedad").addClass("alertaCombo");
                error = true;
            }

            if (!$("input[name='idAplicacion']").is(':checked')) {
                $("#datosAplicacion").addClass("alertaCombo");
                error = true;
            }
        }
        return !error;
    };

    this.mensajeError = function () {
        var enf = $("#enfermedad").is(':focus');
        var apl = $("#aplicacion").is(':focus');
        /*console.log("APL = " + apl);
         console.log("ENF = " + enf);
         console.log("|| = " + (enf || apl));
         console.log("! || = " + (!(enf || apl)));

         console.log("! && ! = " + ((!enf && !apl)));
         console.log("--------");*/
        if (!(enf || apl)) {
            mostrarMensaje("Llene todos los datos del uso de producto1", "FALLO");
        }
    }
}

function exitoGuardado() {
    this.ejecutar = function (msg) {
        $("#datosFabricanteProducto input").not(":checked").each(function () {
            $(this).parent().remove();
        });
        $("#datosFabricanteProducto button").remove();
        $("#datosEmpresa input").not(":checked").each(function () {
            $(this).parent().remove();
        });
        $("#datosEmpresa button").remove();
        $("#datosComposicion input").not(":checked").each(function () {
            $(this).parent().remove();
        });
        $("#datosComposicion button").remove();
        mostrarMensaje("Se ingresaron los datos con éxito", "EXITO");

        $("#guardar").remove();
        //MOSTRAR ADITIVOS
        $("#aditivos").removeAttr("style");
        //MOSTRAR APLICACIÓN
        $("#uso").removeAttr("style");
        //MOSTRAR FABRICANTE
        if ($("#co_" + msg.idComposicion).attr("data-area") == 'P') {
            $("#fabricantes").removeAttr("style");
        }
        //MOSTRAR FINALIZAR
        $("#finalizacion").removeAttr("style");
        //CARGAR USOS
        if (typeof msg.usos !== 'undefined') {
            $.each(msg.usos, function (i, uso) {
                $("#idUso").append('<option value="' + uso['id_uso'] + '">' + uso['nombre_uso'] + '</option>');
            });
        }
        //ASIGNAR ID PRODUCTO
        $("input[name = 'idProducto'").val(msg.idProducto);
        $("#idProductoCreado").val(msg.idProducto);
        //ASIGNAR CODIGO SECUENCIAL GENERADO
        $("#codigoGenerado").html(msg.idComposicion + $("#co_" + msg.idComposicion).attr("data-area") + msg.secuencia);
        //AGREGAR LISTADO DE INGREDIENTES ACTIVOS
        for (ingrediente of
        JSON.parse(msg.ingredientes)
        )
        {
            $("#idIngredienteActivo").append('<option value="' + ingrediente.id_ingrediente_activo + '">' + ingrediente.ingrediente_activo + '</option>');
        }
        distribuirLineas();
    }
}

function exitoNuevoUso() {
    this.ejecutar = function (msg) {
        mostrarMensaje("Nuevo registro agregado", "EXITO");
        var fila = msg.mensaje;
        $("#registrosProductoUsos").append(fila);
        //ASIGNAR CODIGO GENERADO
        $("#codigoGenerado").html(msg.codigoProducto);
        $("#resultadosAplicacion").html("");
    };
}

function exitoNuevoFabricante() {
    this.ejecutar = function (msg) {
        mostrarMensaje("Nuevo registro agregado", "EXITO");
        var fila = msg.mensaje;
        $("#registrosFabricantes").append(fila);
        $("#resultadosFabricante").html("");
    };
}

function exitoBorradoUso() {
    this.ejecutar = function (msg) {
        $("#registrosProductoUsos #R" + msg.mensaje).fadeOut("fast", function () {
            $(this).remove();
        });
        mostrarMensaje("Elemento borrado", "EXITO");
        //ASIGNAR CODIGO GENERADO
        $("#codigoGenerado").html(msg.codigoProducto);
    };
}

acciones('#nuevoUso', '#registrosProductoUsos', null, null, new exitoNuevoUso(), new exitoBorradoUso(), null, new verificarInputsNuevoUso());
acciones('#nuevoAditivo', '#registrosAditivos', null, null, null, null, null, new verificarInputsNuevoAditivo());
acciones('#nuevoFabricante', '#registrosFabricantes', null, null, new exitoNuevoFabricante(), null, null, new verificarInputsNuevoFabricante());
distribuirLineas();

</script>
</html>