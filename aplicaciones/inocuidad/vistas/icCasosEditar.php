<?php
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorRequerimiento.php';
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 28/01/18
 * Time: 19:09
 */
$controladorCatalogos = new ControladorCatalogosInc();
$controladorRequerimiento= new ControladorRequerimiento();

$productos=$controladorCatalogos->obtenerComboCatalogosOpciones("PRODUCTOS");
$programasCatalogo=$controladorCatalogos->obtenerComboCatalogosOpciones("PROGRAMAS");
$fuentesDenunciaCatalogo=$controladorCatalogos->obtenerComboCatalogosOpciones("FUENTE_DENUNCIA");
$tipoRequerimiento=$controladorCatalogos->obtenerComboCatalogosOpciones("REQUERIMIENTOS");
$provincias=$controladorCatalogos->obtenerComboCatalogosOpciones("PROVINCIAS");
$paises=$controladorCatalogos->obtenerComboCatalogosOpciones("PAISES");
$idCaso = $_POST['id'];
$disabled='false';
$caso=null;
if($idCaso!="_nuevo"){
    $caso = $controladorRequerimiento->recuperarRequerimiento($idCaso);
    $disabled='disabled';
}

?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">
    <link rel='stylesheet' href='aplicaciones/inocuidad/estilos/global.css' >
</head>
<body>
<header>
    <h1>Detalle del caso</h1>
</header>
<div id="estado"></div>
<table class="soloImpresion">
    <tr>
        <td>
            <form id="actualizarCaso" data-rutaAplicacion="inocuidad" data-opcion="controladores/editaCaso" method="post">
                <input type="hidden" id="ic_requerimiento_id" name="ic_requerimiento_id"
                       value="<?php echo $caso==null?'':$caso->getId()?>">
                <fieldset>
                    <legend>Caso</legend>
                    <div data-linea="2">
                        <label>Tipo de Requerimiento</label>
                        <select id="ic_tipo_requerimiento_id" name="ic_tipo_requerimiento_id" disabled="disabled" data-required>
                            <option value="">Seleccione el tipo de caso ...</option>

                        </select>
                    </div>
                    <div data-linea="3">
                        <label>Producto</label>
                        <select id="ic_producto_id" name="ic_producto_id"  disabled="disabled" data-required>
                            <option value="">Seleccione el producto ...</option>
                        </select>
                    </div>
                    <div data-linea="1">
                        <label>Fecha Solicitud</label>
                        <input type="text" id="fecha_solicitud" name="fecha_solicitud" readonly="readonly"
                               value="<?php if($caso!=null){
                                   $dateSol = new DateTime($caso->getFechaSolicitud());
                                   echo $dateSol->format('d/m/Y');
                               }?>" disabled="disabled" data-required/>
                    </div>
                    <div data-linea="5">
                        <label>N° de Muestras (Incl. Contramuestras)</label>
                        <input type="number" id="numero_muestras" name="numero_muestras" class="numeric"
                               value="<?php echo $caso==null?'2':$caso->getNumeroMuestras()?>" data-required/>
                    </div>
                    <?php if($caso==null) {?>
                        <div data-linea = "6" >
                            <label > Cantidad de Registros(Duplicador de Casos)</label >
                            <input type = "number" id = "numero_casos" name = "numero_casos" class="numeric" value="1" data-required />
                        </div >
                    <?php }?>
                </fieldset>

                <fieldset id="section_PV" style="display: none">
                    <legend>Plan de Vigilancia</legend>

                    <div data-linea="1">
                        <label>Programa</label>
                        <select id="programa_id" name="programa_id" data-required >
                            <option value="">Tipo programa....</option>
                        </select>
                    </div>

                    <div data-linea="2">
                        <label>Fecha Estimada de Inspección</label>
                        <input type="text" id="fecha_inspeccion" name="fecha_inspeccion" readonly="readonly"
                               value="<?php if($caso!=null){
                                   $dateSol = new DateTime($caso->getFechaInspeccion());
                                   echo $dateSol->format('d/m/Y');
                               }?>" data-required/>
                    </div>
                    <div data-linea="3">
                        <label>Provincia</label>
                        <select id="provincia_id" name="provincia_id" data-required>
                            <option value="">Seleccione una provincia ...</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset id="section_DN" style="display: none">
                    <legend>Denuncia</legend>

                    <div data-linea="1">
                        <label>Fecha Denuncia</label>
                        <input type="text" id="fecha_denuncia" name="fecha_denuncia" readonly="readonly"
                               value="<?php if($caso!=null){
                                   $dateSol = new DateTime($caso->getFechaDenuncia());
                                   echo $dateSol->format('d/m/Y');
                               }?> " data-required/>
                    </div>
                    <div data-linea="2">
                        <label>Fuente</label>
                        <select id="fuente_denuncia_id" name="fuente_denuncia_id"  data-required>
                            <option value="">Seleccione la fuente de la denuncia ...</option>
                        </select>
                    </div>
                    <div data-linea="3">
                        <label>Nombre Denunciante</label>
                        <input type="text" id="nombre_denunciante" name="nombre_denunciante"
                               value="<?php echo $caso==null?'':$caso->getNombreDenunciante()?>" data-required/>
                    </div>
                    <div data-linea="4">
                        <label>Datos Denunciante</label>
                        <input type="text" id="datos_denunciante" name="datos_denunciante"
                               value="<?php echo $caso==null?'':$caso->getDatosDenunciante()?>" data-required/>
                    </div>
                    <label>Descripción denuncia</label>
                    <div data-linea="5">
                        <textarea id="descripcion_denuncia" name="descripcion_denuncia" rows="3" />
                    </div>
                    <div data-linea="6">
                        <label>Provincia</label>
                        <select id="provincia_denuncia_id" name="provincia_denuncia_id" data-required>
                            <option value="">Seleccione una provincia ...</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset id="section_NE" style="display: none">
                    <legend>Notificación del Exterior</legend>

                    <div data-linea="1">
                        <label>Fecha Notificación</label>
                        <input type="text" id="fecha_notificacion" name="fecha_notificacion" readonly="readonly"
                               value="<?php if($caso!=null){
                                   $dateSol = new DateTime($caso->getFechaNotificacion());
                                   echo $dateSol->format('d/m/Y');
                               }?>" data-required/>
                    </div>
                    <div data-linea="2">
                        <label>País que notifica</label>
                        <select id="pais_notificacion_id" name="pais_notificacion_id"  data-required>
                            <option value="">Seleccione país ... </option>
                        </select>
                    </div>
                </fieldset>
                <fieldset id="section_OBS" style="display: none">
                    <legend>Responsable</legend>
                    <div>
                        <label>Inspector</label>
                        <select id="inspector_id" name="inspector_id" data-required>
                            <option value="">Seleccione un inspector ...</option>
                        </select>
                        <a class="material_link" onclick="loadInspectores()"><i class="material-icons">search</i></a>
                    </div>
                    <div data-linea="1">
                        <table id="inspector_props" style="width: 98%"></table>
                    </div>
                    <label>Observacion</label>
                    <div data-linea="2">
                        <textarea id="observacion" name="observacion" rows="3"><?php echo $caso!=null?$caso->getObservacion():""; ?></textarea>
                    </div>
                </fieldset>

                <div id="controls">
                    <table style="width: 100%">
                        <tr>
                            <td><button id="actualizar" type="submit" class="guardar">Guardar</button></td>
                            <td><button id="enviar" type="button" class="guardar" disabled="disabled">Enviar</button></td>
                            <td><button id="file-attach" type="button" class="subirArchivo adjunto" disabled="disabled"
                                        data-view='[{"tabla":"g_inocuidad.ic_requerimiento", "registro":"<?php echo $caso!=null?$caso->getId():"";?>"}]'
                                        data-tabla="g_inocuidad.ic_requerimiento"
                                        data-registro="<?php echo $caso!=null?$caso->getId():"";?>">Adjuntos</button></td>
                        </tr>
                    </table>
                </div>

            </form>
            <form id="enviarCaso" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarCasoCreaMuestra" method="post">
                <input type="hidden" id="ic_requerimiento_id" name="ic_requerimiento_id"
                       value="<?php echo $caso==null?'':$caso->getId()?>">
            </form>
        </td>
    </tr>
</table>
<div id="includedAdjunto"></div>
</body>
<script src="aplicaciones/inocuidad/js/icCasosEditar_Inspectores.js"/>
<script>
    var array_comboTipoProducto = <?php echo json_encode($productos);?>;
    var array_ComboProgramas = <?php echo json_encode($programasCatalogo);?>;
    var array_Requerimientos = <?php echo json_encode($tipoRequerimiento);?>;
    var array_Provincias =<?php echo json_encode($provincias);?>;
    var array_Paises=<?php echo json_encode($paises)?>;
    var array_FuenteDenuncia=<?php echo json_encode($fuentesDenunciaCatalogo)?>;
    for(var i=0; i<array_comboTipoProducto.length; i++){
        $('#ic_producto_id'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ).append(array_comboTipoProducto[i]);
    }
    for(var i=0; i<array_ComboProgramas.length; i++){
        $('#programa_id').append(array_ComboProgramas[i]);
    }
    for(var i=0; i<array_Requerimientos.length; i++){
        $('#ic_tipo_requerimiento_id').append(array_Requerimientos[i]);
    }
    for(var i=0; i<array_Provincias.length; i++){
        $('#provincia_id').append(array_Provincias[i]);
        $('#provincia_denuncia_id').append(array_Provincias[i]);

    }
    for(var i=0; i<array_Paises.length; i++){

        $('#pais_notificacion_id').append(array_Paises[i]);

    }

    for(var i=0; i<array_FuenteDenuncia.length; i++){
        $('#fuente_denuncia_id').append(array_FuenteDenuncia[i]);
    }

    <?php if($caso!=null){?>
    cargarValorDefecto("ic_producto_id","<?php echo $caso->getProductoId();?>");

    var req=<?php echo $caso->getTipoRequerimientoId();?>;
    var tipoReq='';
    switch (req){
        case 1:
            tipoReq='PV';
            cargarValorDefecto("programa_id",<?php echo $caso->getProgramaId()?>);
            //cargarValorDefecto("inspector_id",);
            cargarValorDefecto("provincia_id",<?php echo $caso->getProvinciaId()?>);


            break;
        case 2:
            tipoReq='DN';
            cargarValorDefecto("fuente_denuncia_id",<?php echo $caso->getFuenteId()?>);
            cargarValorDefecto("provincia_id",<?php echo $caso->getProvinciaId()?>);
            cargarValorDefecto("provincia_denuncia_id",<?php echo $caso->getProvinciaId()?>);
            $("textarea#descripcion_denuncia").text('<?php echo $caso->getDescripcionDenuncia(); ?>');


            break;
        case 3:
            tipoReq='NE';
            cargarValorDefecto("pais_notificacion_id",<?php echo $caso->getPaisId()?>);
            cargarValorDefecto("origen_mercaderia_id",<?php echo $caso->getOrigenMercaderiaId()?>);


            break;
    }
    loadSelectedInspector("<?php echo $caso->getInspectorId()?>");
    cargarValorDefecto("ic_tipo_requerimiento_id",tipoReq);
     <?php }?>
</script>
<script src="aplicaciones/inocuidad/js/icCasosEditar.js"/>
</html>
