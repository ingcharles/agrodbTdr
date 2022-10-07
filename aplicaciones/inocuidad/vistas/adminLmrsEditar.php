<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 31/01/18
 * Time: 21:21
 */
session_start();
require_once '../controladores/ControladorLmr.php';

$ic_lmr_id = $_POST['id'];
$controladorLmr= new ControladorLmr();
$lmr = $ic_lmr_id=='_nuevo'?null:$controladorLmr->getLmr($ic_lmr_id);
$ic_lmr_id = $ic_lmr_id=='_nuevo'?null:$ic_lmr_id;
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Detalle LMRs</h1>
</header>
<div id="estado"></div>
<table class="soloImpresion">
    <tr>
        <td>
            <form id="editarLmr" data-rutaAplicacion="inocuidad" data-opcion="controladores/editarLmr" >
                <input type="hidden" id="ic_lmr_id" name="ic_lmr_id" value="<?php echo $ic_lmr_id;?>">
                <fieldset>
                    <legend>LMR</legend>

                    <div data-linea="1">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre"  required value="<?php echo $lmr==null?'':$lmr->getNombre(); ?>"/>
                    </div>
                    <div data-linea="2">
                    	<label for="descripcion">Descripción</label>
                    </div>
                    <div data-linea="3">
                        <textarea rows="3" id="descripcion" name="descripcion"  data-required><?php echo $lmr==null?'':$lmr->getDescripcion(); ?></textarea>
                    </div>
                </fieldset>
                <button id="guardar" type="submit" class="guardar">Guardar</button>
            </form>
        </td>
    </tr>
</table>

</body>

<script>
    $("#editarLmr").submit(function(event){
        event.preventDefault();
        if(validarRequeridos($("#editarLmr"))) {
            ejecutarJson($(this), new resetFormulario($("#editarLmr")));
        }
    });
</script>
<script src="aplicaciones/inocuidad/js/globals.js"/>
</html>