<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 21/02/18
 * Time: 8:37
 */

session_start();
require_once '../controladores/ControladorCatalogosInc.php';
require_once '../controladores/ControladorRequerimiento.php';
require_once '../controladores/ControladorMuestra.php';
require_once '../controladores/ControladorLaboratorio.php';
require_once '../controladores/ControladorEvaluacion.php';
require_once '../controladores/ControladorComite.php';

$objIds = $_POST['id'];
$ids = json_decode($objIds);

$comite = null;
$evaluacion = null;
$laboratorio = null;
$muestra=null;

if($ids->{'ic_evaluacion_comite_id'}!=null){
    $controladorComite = new ControladorComite();
    $comite = $controladorComite->getComite($ids->{'ic_evaluacion_comite_id'});
}
if ($ids->{'ic_evaluacion_analisis_id'}!=null){
    $controladorEvaluacion = new ControladorEvaluacion();
    $evaluacion = $controladorEvaluacion->getEvaluacion($ids->{'ic_evaluacion_analisis_id'});
}
if ($ids->{'ic_analisis_muestra_id'}!=null){
    $controladorLaboratorio= new ControladorLaboratorio();
    $laboratorio = $controladorLaboratorio->getLaboratorio($ids->{'ic_analisis_muestra_id'});
}
if ($ids->{'ic_muestra_id'}!=null){
    $controladorMuestra= new ControladorMuestra(null);
    $muestra = $controladorMuestra->getMuestra($ids->{'ic_muestra_id'});
}
$controladorRequerimiento = new ControladorRequerimiento();
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">

</head>
<body>
<div id="estado"></div>
<table class="soloImpresion">
    <tr>
        <td>
            <div>
                <h2>Resumen del caso</h2>
            </div>
            <?php
                echo $controladorRequerimiento->getCasoRO($ids->{'ic_requerimiento_id'});
                if($muestra!=null)
                    echo $controladorMuestra->getMuestraRO($muestra->getIcMuestraId());
                if($laboratorio!=null)
                    echo $controladorLaboratorio->getLaboratorioRO($laboratorio->getIcAnalisisMuestraId());
                if($evaluacion!=null) {
                    echo $controladorEvaluacion->getEvaluacionRO($evaluacion->getIcEvaluacionAnalisisId());
                    echo "<table style=\"width: 100%\" border=\"1\">
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
                            ".$controladorEvaluacion->getResultadoDatos($evaluacion->getIcAnalisisMuestraId())."
                        </tbody>
                    </table>";
                }if($comite!=null)
                    echo $controladorComite->getComiteRO($comite->getIcEvaluacionComiteId());
            ?>
        </td>
    </tr>
</table>

</body>
</html>
