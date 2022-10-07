<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 31/01/18
 * Time: 21:10
 */
    session_start();
    require_once '../controladores/ControladorCatalogosInc.php';
    require_once '../controladores/ControladorProducto.php';

    $ic_producto_id = $_POST['id'];

    $controladorProducto= new ControladorProducto();
    $producto = $ic_producto_id=='_nuevo'?null:$controladorProducto->getProducto($ic_producto_id);
    $ic_producto_id = $ic_producto_id=='_nuevo'?null:$ic_producto_id;
    $arr_insumos=null;
    $arr_muestra=null;
    if($producto!=null){
        $_insumos = $controladorProducto->listarProductosInsumosJSON($producto->getIcProductoId());
        $arr_insumos = json_encode($_insumos);//str_replace("\"","\\\"",json_encode($_insumos));
        $_muestras = $controladorProducto->listarMuestraRapidaJSON($producto->getIcProductoId());
        $arr_muestra = json_encode($_muestras);
    }

    $controladorCatalogos = new ControladorCatalogosInc();
    $insumos = $controladorCatalogos->obtenerComboCatalogosOpciones("INSUMOS");
    $lmrCatalogo=$controladorCatalogos->obtenerComboCatalogosOpciones("LMRS");
    $um=$controladorCatalogos->obtenerComboCatalogosOpciones("UNIDAD_MEDIDA");
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">
    <link rel='stylesheet' href='aplicaciones/inocuidad/estilos/global.css' >
    <link rel='stylesheet' href='aplicaciones/inocuidad/estilos/adminProductosEditar.css' >
</head>
<body>
<header>
    <h1>Detalle Producto Inocuidad</h1>
</header>
<form id="editarProducto" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarProducto">
    <input type="hidden" id="ic_producto_id" name="ic_producto_id" value="<?php echo $ic_producto_id;?>">
    <input type="hidden" id="arr_insumos" name="arr_insumos" value=""/>
    <input type="hidden" id="arr_muestra" name="arr_muestra" value=""/>
    <fieldset>
        <legend>Producto Inocuidad</legend>

        <div data-linea="1">
            <label for="area_id">Área Temática</label>
            <select id="area_id" name="area_id"  data-required >
                <option value="">Seleccione ....</option>
                <option value="SA">Sanidad Animal</option>
                <option value="SV">Sanidad Vegetal</option>
                <option value="LT">Laboratorios</option>
                <option value="AI">Inocuidad de los alimentos</option>
                <option value="IAP">Registro de Insumos Agricolas</option>
                <option value="IAF">Registro de Insumos Fertilizantes</option>
                <option value="IAV">Registro de Insumos Veterinarios</option>
            </select>
        </div>
        <div data-linea="2">
            <label for="id_tipo_producto">Tipo Producto</label>
            <select id="id_tipo_producto" name="id_tipo_producto"  data-required disabled="disabled">
                <option value="">Seleccione ....</option>
            </select>
        </div>
        <div data-linea="3">
            <label for="id_subtipo_producto">Subtipo Producto</label>
            <select id="id_subtipo_producto" name="id_subtipo_producto"  data-required disabled="disabled">
                <option value="">Seleccione ....</option>
            </select>
        </div>
        <div data-linea="4">
            <label for="producto_id">Producto</label>
            <select id="producto_id" name="producto_id"  data-required disabled="disabled">
                <option value="">Seleccione ....</option>
            </select>
        </div>
        <div data-linea="5">
            <label for="cuenta_producto_insumos">Insumos Disponibles</label>
            <input id="cuenta_producto_insumos" name="cuenta_producto_insumos" disabled="disabled"/>
        </div>
        <div data-linea="6">
            <label for="nombre_producto">Nombre del Producto de Inocuidad</label>
            <input type="text" id="nombre_producto" name="nombre_producto"  required value="<?php echo $producto==null?'':$producto->getNombre(); ?>"/>
        </div>
        <div data-linea="7">
            <input type="button" id="generar_insumos" name="generar_insumos" value="Generar Insumos del Producto"
                   <?php if($producto!=null){ ?>onclick="generarInsumos(<?php echo $producto->getIcProductoId()?>, <?php echo $producto->getProductoId()?>)" <?php }else echo "disabled='disabled'" ?>/>
        </div>
        <div data-linea="8">
            <label for="muestra_rapida">Muestra Rápida</label>
            <input type="checkbox" id="muestra_rapida" name="muestra_rapida"  <?php echo $producto==null?'':($producto->getMuestraRapida()=="S"?'checked':'') ?>/>
        </div>
    </fieldset>
    <fieldset>
        <legend>Registro Valores</legend>
        <table id="registroValores" style="width:95%"  class="tablaMatriz">
            <thead>
            <tr>
                <th>Insumo</th>
                <th>LMR</th>
                <th>Unidad Medida</th>
                <th>Lim. Mínimo</th>
                <th>Lim. Máximo</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
    <fieldset id="muestraRapidaFieldset">
        <legend>Muestra Rapida</legend>
        <table id="muestraRapidaInput" style="width:100%"  class="tablaMatriz">
            <tr>
                <td>
                    <select id="ic_insumo_id" name="ic_insumo_id" style="width:110px">
                        <option value="">Insumo ....</option>
                    </select>
                </td>
                <td>
                    <select id="um" name="um"  style="width:110px">
                        <option value="">Unidad Med ....</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="decimal" id="limite_minimo" name="limite_minimo"  placeholder="Lim. Mínimo" style="width:60px"/>
                </td>
                <td>
                    <input type="text" class="decimal" id="limite_maximo" name="limite_maximo"  placeholder="Lim. Máximo" style="width:60px"/>
                </td>
            </tr>
        </table>
        <div style="width: 100%;text-align: center;">
            <input type="button" class="add-row" id="agregarMuestra" value="Agregar"/>
        </div>
        <table id="muestraRapida" style="width:95%"  class="tablaMatriz">
            <thead>
            <tr>
                <th>Insumo</th>
                <th>Unidad Medida</th>
                <th>Lim. Mínimo</th>
                <th>Lim. Máximo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
    <button id="guardar" type="submit" class="guardar">Guardar</button>
</form>
<div id="dialog" title="Error">
    <p id="errDialogMsg"></p>
</div>
</body>
<script src="aplicaciones/inocuidad/js/adminProductosEditar.js"/>
<script>

    var array_comboLmrs = <?php echo json_encode($lmrCatalogo);?>;
    for(var i=0; i<array_comboLmrs.length; i++){
        $('#ic_lmr_id').append(array_comboLmrs[i]);
    }

    var array_comboInsumos = <?php echo json_encode($insumos);?>;
    for(var i=0; i<array_comboInsumos.length; i++){
        $('#ic_insumo_id').append(array_comboInsumos[i]);
    }

    var array_comboUm = <?php echo json_encode($um);?>;
    for(var i=0; i<array_comboUm.length; i++){
        $('#um').append(array_comboUm[i]);
    }

    <?php if($producto!=null){?>
        cargarValorDefecto("area_id","<?php echo $producto->getIdArea();?>");

        refreshOpciones('<?php echo $producto->getIdArea();?>',$('#id_tipo_producto'),'TIPO_PRODUCTO',true,function(){
            cargarValorDefecto("id_tipo_producto","<?php echo $producto->getIdTipoProducto();?>");
        });

        refreshOpciones(<?php echo $producto->getIdTipoProducto();?>,$('#id_subtipo_producto'),'SUBTIPO_PRODUCTO',true,function(){
            cargarValorDefecto("id_subtipo_producto","<?php echo $producto->getIdSubtipoProducto();?>");
        });

        refreshOpciones(<?php echo $producto->getIdSubtipoProducto();?>,$('#producto_id'),'PRODUCTO_BASE',true,function(){
            cargarValorDefecto("producto_id","<?php echo $producto->getProductoId();?>");
        });

        cargarValoresTabla("registroValores",<?php echo $arr_insumos;?>);
        cargarValoresTabla("muestraRapida",<?php echo $arr_muestra;?>);
    <?php }?>

    $("#editarProducto").submit(function(event){
        $("#producto_id").prop('disabled', false);
        event.preventDefault();
        if(validarRequeridos($("#editarProducto"))){
            if(validarInsumos()){
                ejecutarJson($(this),new resetFormulario($("#editarProducto")));
                cleanRows();
            }else
                mostrarMensaje("El producto debe tener límites en insumos para poder medirlo.","FALLO");
        }else
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
    });
</script>
</html>