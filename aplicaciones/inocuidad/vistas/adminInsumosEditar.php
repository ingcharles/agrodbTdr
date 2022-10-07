<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 31/01/18
 * Time: 21:10
 */
session_start();
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorInsumo.php';

$ic_insumo_id = $_POST['id'];

$controladorCatalogos = new ControladorCatalogosInc();
$programasCatalogo=$controladorCatalogos->obtenerComboCatalogosOpciones("PROGRAMAS");

$controladorInsumo= new ControladorInsumo();
$insumo = $ic_insumo_id=='_nuevo'?null:$controladorInsumo->getInsumo($ic_insumo_id);
$ic_insumo_id = $ic_insumo_id=='_nuevo'?null:$ic_insumo_id;
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Detalle Insumos</h1>
</header>
<div id="estado"></div>
<table class="soloImpresion">
    <tr>
        <td>
            <form id="editarInsumo" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarInsumo">
                <input type="hidden" id="ic_insumo_id" name="ic_insumo_id" value="<?php echo $ic_insumo_id;?>">
                <fieldset>
                    <legend>Insumo</legend>

                    <div data-linea="1">
                        <label for="programa_id">Programa</label>
                        <select id="programa_id" name="programa_id"  data-required>
                            <option value="">Tipo programa....</option>
                        </select>
                    </div>
                    <div data-linea="2">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre"  required value="<?php echo $insumo==null?'':$insumo->getNombre(); ?>"/>
                    </div>
                    <label for="descripcion">Descripción</label>
                    <div data-linea="3">
                        <textarea rows="3" id="descripcion" name="descripcion"  data-required><?php echo $insumo==null?'':$insumo->getDescripcion(); ?></textarea>
                    </div>
                </fieldset>
                <button id="guardar" type="submit" class="guardar">Guardar</button>
            </form>
        </td>
    </tr>
</table>
<div id="dialog" title="Error">
    <p id="errDialogMsg"></p>
</div>
</body>

<script>
    var array_comboProgramas = <?php echo json_encode($programasCatalogo);?>;
    for(var i=0; i<array_comboProgramas.length; i++){
        $('#programa_id').append(array_comboProgramas[i]);
    }

    <?php if($insumo!=null){?>
        cargarValorDefecto("programa_id","<?php echo $insumo->getProgramaId();?>");
    <?php }?>
    $("#editarInsumo").submit(function(event){
        event.preventDefault();
        if(validarRequeridos($("#editarInsumo"))) {
            ejecutarJson($(this), new resetFormulario($("#editarInsumo")));
        }
    });

</script>
<script src="aplicaciones/inocuidad/js/globals.js"/>
</html>