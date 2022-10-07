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
require_once '../controladores/ControladorComite.php';

$ic_evaluacion_comite_id = $_POST['id'];

$controladorMuestra= new ControladorMuestra(null);
$controladorRequerimiento = new ControladorRequerimiento();
$controladorLaboratorio = new ControladorLaboratorio();
$controladorEvaluacion = new ControladorEvaluacion();
$controladorComite  = new ControladorComite();

$comite = ($ic_evaluacion_comite_id=='_nuevo'?null:$controladorComite->getComite($ic_evaluacion_comite_id));
$muestra = null;
$laboratorio = null;
$evaluacion = null;
if($comite!=null){
    $evaluacion = $controladorEvaluacion->getEvaluacion($comite->getIcEvaluacionAnalisisId());
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

    <table class="soloImpresion">
        <tr>
            <td>
                <div>
                    <h2>Resumen del caso</h2>
                </div>
                <?php echo $controladorRequerimiento->getCasoRO($muestra->getIcRequerimientoId()); ?>
                <?php echo $controladorMuestra->getMuestraRO($laboratorio->getIcMuestraId()); ?>
                <?php echo $controladorLaboratorio->getLaboratorioRO($laboratorio->getIcAnalisisMuestraId()); ?>
                <?php echo $controladorEvaluacion->getEvaluacionRO($evaluacion->getIcEvaluacionAnalisisId()); ?>
            </td>
        </tr>
        <tr>
            <td style="display: table-cell;width: 100%;">
                <div>
                    <h2>Evaluación Comité</h2>
                </div>
                <form id="actualizarComite" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarComite">
                    <input type="hidden" id="ic_evaluacion_analisis_id" name="ic_evaluacion_analisis_id" value="<?php echo $evaluacion->getIcEvaluacionAnalisisId() ?>"/>
                    <input type="hidden" id="ic_analisis_muestra_id" name="ic_analisis_muestra_id" value="<?php echo $laboratorio->getIcAnalisisMuestraId() ?>"/>
                    <input type="hidden" id="ic_evaluacion_comite_id" name="ic_evaluacion_comite_id" value="<?php echo $ic_evaluacion_comite_id ?>"/>
                    <fieldset id="fs_detalle" style="width: 100%;">
                        <legend>Comité</legend>
                        <table style="display: table;width: 100%" border="1">
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
                    <fieldset id="fs_detalle" style="width: 100%">
                        <legend>Evaluación Comité</legend>

                        <label for="observaciones">Observaciones</label>
                        <div data-linea="1">
                            <textarea id="observaciones" name="observaciones" cols="10" rows="10"></textarea>
                        </div>

                    </fieldset>
                    <div id="controls">
                        <table style="display: table;width: 100%">
                            <tr>
                                <td><button id="enviar" type="submit" class="guardar">Enviar</button></td>
                                <td><button id="file-attach" type="button" class="subirArchivo adjunto"
                                            data-view='[{"tabla":"g_inocuidad.ic_evaluacion_comite", "registro":"<?php echo $comite->getIcEvaluacionComiteId();?>"}]'
                                            data-tabla="g_inocuidad.ic_evaluacion_comite"
                                            data-registro="<?php echo $comite->getIcEvaluacionComiteId();?>">Adjuntos</button></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </td>
        </tr>
    </table>
<div id="includedAdjunto"></div>
</body>
<script src="aplicaciones/inocuidad/js/icComiteEditar.js"/>
</html>