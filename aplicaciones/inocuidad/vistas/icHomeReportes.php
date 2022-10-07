<?php
require_once '../controladores/ControladorCatalogosInc.php';
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 24/01/18
 * Time: 22:12
 */
$controladorCatalogos = new ControladorCatalogosInc();
$tipoRequerimiento=$controladorCatalogos->obtenerComboCatalogosOpciones("REQUERIMIENTOS");
$provincias=$controladorCatalogos->obtenerComboCatalogosOpciones("PROVINCIAS");
$programasCatalogo=$controladorCatalogos->obtenerComboCatalogosOpciones("PROGRAMAS");
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Reportes</h1>
</header>
<table class="filtro" style="width: 400px">
    <tbody>
        <tr>
            <th colspan="2">Reporte 360:</th>
        </tr>
        <tr>
            <td>Número de Caso:</td>
            <td><input type="number" maxlength="10" id="ic_requerimiento_id" name="ic_requerimiento_id" value=""></td>
        </tr>
        <tr>
            <td id="mensajeError"></td>
            <td><button class="r360-launcher"
                        id='link_360'
                        data-rutaAplicacion='inocuidad'
                        data-opcion='./vistas/icReporte360'
                        ondragstart='drag(event)'
                        draggable='true'
                        data-destino='detalleItem'>Buscar</button>
                </td>
        </tr>
    </tbody>
</table>

<table class="filtro" style="width: 400px">
    <tbody>
        <tr>
            <th colspan="3">Reporte Detallado:</th>
        </tr>
        <tr>
            <td>Fecha Inicio:</td>
            <td colspan="2"><input type="text" id="fecha_inicio" name="fecha_inicio" readonly="readonly" data-required/></td>
        </tr>
        <tr>
            <td>Fecha Fin:</td>
            <td colspan="2"><input type="text" id="fecha_fin" name="fecha_fin" readonly="readonly" data-required/></td>
        </tr>
        <tr>
            <td>Tipo de Requerimiento:</td>
            <td colspan="2">
                <select id="ic_tipo_requerimiento_id" name="ic_tipo_requerimiento_id" style="max-width: 260px;" data-required>
                    <option value="">Seleccione el tipo de caso ...</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Provincia:</td>
            <td colspan="2">
                <select id="provincia_id" name="provincia_id" style="max-width: 260px;" data-required>
                    <option value="">Seleccione una provincia ...</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Programa:</td>
            <td colspan="2">
                <select id="programa_id" name="programa_id" style="max-width: 260px;" data-required >
                    <option value="">Tipo programa....</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Inspector:</td>
            <td>
                <select id="inspector_id" name="inspector_id" data-required style="max-width: 260px;">
                    <option value="">Seleccione un inspector ...</option>
                </select>
            </td>
            <td>
                <a class="material_link" onclick="loadInspectores()"><i class="material-icons">search</i></a>
            </td>
        </tr>
        <tr>
            <td id="mensajeError"></td>
            <td colspan="2">
                <div id="controls">
                    <table style="width: 100%;">
                        <tbody>
                            <tr style="border-top: unset !important;">
                                <td>
                                    <div class='accion'>
                                        <a class="rdetalle-launcher report-launcher"
                                           href=''
                                           id='{}'
                                           draggable='false'>Generar reporte Excel</a>
                                    </div></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>
</body>
<script>
    var array_Requerimientos = <?php echo json_encode($tipoRequerimiento);?>;
    var array_Provincias =<?php echo json_encode($provincias);?>;
    var array_ComboProgramas = <?php echo json_encode($programasCatalogo);?>;
    for(var i=0; i<array_Requerimientos.length; i++){
        $('#ic_tipo_requerimiento_id').append(array_Requerimientos[i]);
    }
    for(var i=0; i<array_Provincias.length; i++){
        $('#provincia_id').append(array_Provincias[i]);
    }
    for(var i=0; i<array_ComboProgramas.length; i++){
        $('#programa_id').append(array_ComboProgramas[i]);
    }
    $(".r360-launcher").on("click",function(){
        validar360(function (e) {
            if(e){
                abrir($(".r360-launcher"),null,false);
                var idReq = document.getElementById("ic_requerimiento_id").value;
                this.id = idReq;
            }else{
                mostrarMensaje("No existen registros para generar el reporte","FALLO");
            }
        });
    });
    $(".rdetalle-launcher").on("click",function(){
        var objData = {};
        objData.fecha_inicio = $('#fecha_inicio').val();
        objData.fecha_fin = $('#fecha_fin').val();
        objData.ic_tipo_requerimiento_id = $('#ic_tipo_requerimiento_id').val();
        if($('#ic_tipo_requerimiento_id').val() && $('#ic_tipo_requerimiento_id').val()>0)
            objData.ic_tipo_requerimiento_id=$('#ic_tipo_requerimiento_id option:selected').attr("data-grupo");
        objData.provincia_id=$('#provincia_id').val();
        objData.programa_id=$('#programa_id').val();
        objData.inspector_id=$('#inspector_id').val();
        this.id = JSON.stringify(objData);
        this.href = "./aplicaciones/inocuidad/reportes/ReporteDetallado.php?where=" + this.id;
    });


</script>
<script src="aplicaciones/inocuidad/js/icReportes.js"/>
</html>