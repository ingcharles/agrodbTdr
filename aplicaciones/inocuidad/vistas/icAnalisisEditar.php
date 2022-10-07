<?php
/**
 * User: ccarrera
 * Date: 28/01/18
 * Time: 22:12
 */
session_start();
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorRequerimiento.php';
require_once '../controladores/ControladorMuestra.php';
require_once '../controladores/ControladorLaboratorio.php';
require_once '../controladores/ControladorEvaluacion.php';

$ic_analisis_muestra_id = $_POST['id'];

$controladorMuestra= new ControladorMuestra(null);
$controladorRequerimiento = new ControladorRequerimiento();
$controladorLaboratorio = new ControladorLaboratorio();
$controladorEvaluacion = new ControladorEvaluacion();

$controladorCatalogos = new ControladorCatalogosInc();
$resultados=$controladorCatalogos->obtenerComboCatalogosOpciones("RESULTADO_DESICION");

$evaluacion = ($ic_analisis_muestra_id=='_nuevo'?null:$controladorEvaluacion->getEvaluacion($ic_analisis_muestra_id));
$muestra = null;
$laboratorio = null;
if($evaluacion!=null){
    $laboratorio = $controladorLaboratorio->getLaboratorio($evaluacion->getIcAnalisisMuestraId());
    $muestra = $controladorMuestra->getMuestra($laboratorio->getIcMuestraId());
}

?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">
</head>
<body>
<header>
</header>

    <table class="soloImpresion" style="display: table;width: 100%;">
        <tr>
            <td>
                <div>
                    <h2>Resumen del caso</h2>
                </div>
                <?php echo $controladorRequerimiento->getCasoRO($muestra->getIcRequerimientoId()); ?>
                <?php echo $controladorMuestra->getMuestraRO($laboratorio->getIcMuestraId()); ?>
                <?php echo $controladorLaboratorio->getLaboratorioRO($laboratorio->getIcAnalisisMuestraId()); ?>
            </td>
        </tr>
        <tr>
            <td style="display: table-cell;width: 100%;">
                <div>
                    <h2>Evaluación de Análisis</h2>
                </div>
                <fieldset id="fs_detalle" style="width: 100%;">
                    <legend>Análisis</legend>
                    <table style="display: table;width: 100%;" border="1">
                        <thead>
                            <tr>
                                <td>Insumo</td>
                                <td>LMR</td>
                                <td>UM</td>
                                <td>Lim. Mínimo</td>
                                <td>Valor</td>
                                <td>Lim. Máximo</td>
                                <td>Observaciones</td>
                                <td>Resultado</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $controladorEvaluacion->getResultadoDatos($evaluacion->getIcAnalisisMuestraId())?>
                        </tbody>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td>
                <form id="actualizaEvaluacion" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarEvaluacion">
                    <input type="hidden" id="ic_evaluacion_analisis_id" name="ic_evaluacion_analisis_id" value="<?php echo $evaluacion->getIcEvaluacionAnalisisId() ?>"/>
                    <input type="hidden" id="ic_analisis_muestra_id" name="ic_analisis_muestra_id" value="<?php echo $laboratorio->getIcAnalisisMuestraId() ?>"/>
                    <input type="hidden" id="ic_muestra_id" name="ic_muestra_id" value="<?php echo $laboratorio->getIcMuestraId() ?>"/>
                    <fieldset id="fs_detalle">
                        <legend>Evaluación Análisis</legend>

                        <label for="observaciones">Observaciones</label>
                        <div data-linea="1">
                            <textarea id="observaciones" name="observaciones" cols="10" rows="10"><?php echo $evaluacion->getObservacion(); ?></textarea>
                        </div>
                        <div data-linea="2">
                            <label for="resultadoDecision">Resultado de la decisión</label>
                            <select id="resultadoDecision" name="resultadoDecision" data-required>
                            </select>
                        </div>
                    </fieldset>
                    <div id="controls">
                        <table style="display: table;width: 100%;">
                            <tr>
                                <td><button id="guardar" type="submit" class="guardar">Guardar</button></td>
                                <td><button id="enviar" type="button" class="guardar" <?php echo $evaluacion->getIcEvaluacionAnalisisId()<=0?  "disabled='disabled'":"" ?>>Enviar</button></td>
                                <td><button id="file-attach" type="button" class="subirArchivo adjunto"
                                            data-view='[{"tabla":"g_inocuidad.ic_evaluacion_analisis", "registro":"<?php echo $evaluacion->getIcEvaluacionAnalisisId();?>"}]'
                                            data-tabla="g_inocuidad.ic_evaluacion_analisis"
                                            data-registro="<?php echo $evaluacion->getIcEvaluacionAnalisisId();?>">Adjuntos</button></td>
                            </tr>
                        </table>
                    </div>
                </form>
                <form id="enviarEvaluacion" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarEvaluacionSegunAccion" method="post">
                    <input type="hidden" id="ic_evaluacion_analisis_id" name="ic_evaluacion_analisis_id" value="<?php echo $evaluacion->getIcEvaluacionAnalisisId() ?>"/>
                    <input type="hidden" id="ic_resultado_decision_id" name="ic_resultado_decision_id" value="<?php echo $evaluacion->getIcResultadoDecisionId() ?>"/>
                </form>
            </td>
        </tr>
    </table>
<div id="includedAdjunto"></div>
</body>
<script src="aplicaciones/inocuidad/js/icAnalisisEditar.js"/>
<script>
    array_Combo =<?php echo json_encode($resultados);?>;
    for(var i=0; i<array_Combo.length; i++){
        $('#resultadoDecision').append(array_Combo[i]);
    }
    cargarValorDefecto("resultadoDecision","<?php echo $evaluacion->getIcResultadoDecisionId();?>");
</script>
</html>